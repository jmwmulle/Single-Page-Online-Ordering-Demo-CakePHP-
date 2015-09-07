<?php

	class CartComponent extends Component {
		public $portion_pricing = array( 'F' => 1, 'L' => 0.5, 'R' => 0.5, 'D' => 2 );
		public $components = array( 'Session' );
		public $Controller;
		private $invoice_template = [ 'subtotal'     => 0,
		                              'total'        => 0,
		                              'item_count'   => 0,
		                              'invoice_rows' => 0,
		                              'hst'          => 0 ];
		private $maxQuantity = 99;
		private $hst_rate = 0.15;
		private $bottle_deposit = 0.1;
		private $delivery_min = 10;


		public function __construct( ComponentCollection $collection, $settings = array() ) {
			$this->Controller = $collection->getController();
			parent::__construct( $collection, array_merge( $this->settings, (array)$settings ) );
		}

		public function startup( Controller $controller ) { }


		/**
		 * returns array of orbopt model records from values of orbopt_ids array
		 *
		 * @param $orbopt_ids
		 *
		 * @return array
		 */
		private function fetch_orbopts_from_ids( $orbopt_ids ) {
			$this->Controller->Orbopt->Behaviors->load( 'Containable' );
			foreach ( $orbopt_ids as $opt_id => $coverage ) {
				if ( $coverage != -1 ) {
					$conditions = [ 'recursive'  => 1,
                                     'conditions' => [ 'Orbopt.id' => $opt_id ],
                                     'contain'    => [ 'Pricelist',
                                                       'Optflag' => [
                                                           'conditions' => [ 'price_factor' => 1 ]
                                                       ] ] ];
					$orbopt_ids[ $opt_id ]                           = $this->Controller->Orbopt->find( 'first', $conditions );
					$orbopt_ids[ $opt_id ][ 'Orbopt' ][ 'coverage' ] = $coverage;
				} else {
					unset( $orbopt_ids[ $opt_id ] );
				}
			}

			return array_filter( $orbopt_ids );
		}


		/**
		 * @param $orbopts
		 * @param $price_rank
		 * @param $included
		 *
		 * @return int
		 */
		private function price_orbopts( $orbopts, $price_rank, $included ) {
			$price_map = [ 'sauce'     => [ 'count'    => 0,
			                                'included' => 1,
			                                'price'    => 0 ],
			               'sidesauce' => [ 'count'    => 0,
			                                'included' => $included[ 'sidesauce_count' ],
			                                'price'    => 0 ],
			               'cheeses'   => [ 'count'    => 0,
			                                'included' => $included[ 'cheese_count' ],
			                                'price'    => 0 ],
			               'premium'   => [ 'count'    => 0,
			                                'included' => $included[ 'premium_count' ],
			                                'price'    => 0 ],
			               'opt'       => [ 'count'    => 0,
			                                'included' => $included[ 'opt_count' ],
			                                'price'    => 0 ],
			];
			$this->Session->write("Cart.Debug.orbopts.data", $orbopts);
			$counting = [];
			foreach ( $orbopts as $id => $orbopt ) {
				$ot = $orbopt['Orbopt']['title'];
				$price = $orbopt[ 'Pricelist' ][ "p$price_rank" ];

				//ie. if opt !has flags but exists here (opts filtered by price_factor = true), it's a regular opt
				if ( empty($orbopt['Optflag']) ) array_push( $orbopt[ 'Optflag' ], [ 'title' => 'opt' ] );

				foreach ( $orbopt[ 'Optflag' ] as $fl ) {
					$index = array_key_exists( $fl[ 'title' ], $price_map ) ? $fl[ 'title' ] : "opt";
					$counting[$ot] = compact('fl', 'index');
					$price_map [ $index ][ 'count' ]++;
					if ($index == 'premium') $price_map['opt']++;
					if ( $price_map[ $index ][ 'price' ] == 0 ) $price_map[ $index ][ 'price' ] = $price;
				}
			}
			$this->Session->write("Cart.Debug.orbopts.counts", $counting);
			$unused_premium = $price_map['premium']['included'] - ($price_map['premium']['count'] - $price_map['premium']['included']);
			if ($unused_premium > 0) {
				$price_map['opt']['included'] += $unused_premium;
			} else {
				$price_map['opt']['included'] -= $price_map['premium']['included'];
			}
			$this->Session->write("Cart.Debug.orbopts.price_map", $price_map);
			$price_breakdown = [];
			$price = 0;
			foreach ( $price_map as $flag => $map) {
				$qty_diff = $map[ 'count' ] - $map['included' ];
				if ( $qty_diff > 0 ) {
					$price_breakdown[$flag] = $qty_diff;
					$price += ( $qty_diff * $map[ 'price' ] );
				}
			}
			$this->Session->write("Cart.Debug.orbopts.price_breakdown", $price_breakdown);

			return $price;
		}

		/**
		 * @param $candidate
		 *
		 * @return bool
		 */
		private function update_quantity( $candidate ) {
			$i = 0;
			foreach ( $this->Session->read( 'Cart.Order' ) as $uid => $oi ) {
				$rank_match = $oi['pricing']['rank'] == $candidate['pricing']['rank'];
				// slices orbs & opts for comparison
				$this->Session->write("Cart.Debug.qty.comparison.$i", compact('oi', 'candidate', 'rank_match'));
				if ( $rank_match ) {
					if ( array_slice( $oi, 0, 2 ) == array_slice( $candidate, 0, 2 ) ) {
						$candidate[ 'pricing' ][ 'quantity' ] += $oi[ 'pricing' ][ 'quantity' ];
						if ( $candidate[ 'pricing' ][ 'quantity' ] > $this->maxQuantity ) {
							$candidate[ 'pricing' ][ 'quantity' ] = $this->maxQuantity;
						}
						$candidate[ 'pricing' ][ 'net' ] = $candidate[ 'pricing' ][ 'quantity' ] * $candidate[ 'pricing' ][ 'configured_unit_price' ];
						$this->Session->write( "Cart.Order.$uid", $candidate );

						return true;
					}
				}
				$i++;
			}

			return false;
		}


		/**
		 * @param $orb_cfg
		 *
		 * @return mixed|null
		 */
		public function add( $orb_cfg ) {
			// $orb_cfg = [orb_id, uid, quantity, price_rank, orbopts]
			$orb_id     = $orb_cfg[ 'id' ];
			$uid        = $orb_cfg[ 'uid' ];
			$quantity   = $orb_cfg[ 'quantity' ];
			$price_rank = $orb_cfg[ 'price_rank' ] + 1; // table columns are p1...p5, indices are 0...4
			$orbopts    = array_key_exists( 'Orbopt', $orb_cfg ) ? $orb_cfg[ 'Orbopt' ] : [ ];

			$quantity = !is_numeric( $quantity ) ? 1 : abs( $quantity );
			if ( $quantity == 0 ) {
				return null;
			}
			if ( $quantity > $this->maxQuantity ) {
				$quantity = $this->maxQuantity;
			}

			// get orb details from id
			$orb                    = $this->Controller->Orb->find( 'first', [ 'recursive'  => 0,
			                                                                   'conditions' => [ 'Orb.id' => $orb_id ] ]
			);
			$orb[ 'Orb' ][ 'note' ] = $orb_cfg[ 'orb_note' ];

			if ( empty( $orb ) ) return null; // if bad orb_id passed, exit quietly

			// get orbopt data
			$orbopts = $this->fetch_orbopts_from_ids( $orbopts );

			// get the pricing info for each opt
			$opt_price = $this->price_orbopts( $orbopts, $price_rank, [ 'opt_count'       => $orb[ 'Orb' ][ 'opt_count' ],
			                                                            'cheese_count'    => $orb[ 'Orb' ][ 'cheese_count' ],
			                                                            'premium_count'   => $orb[ 'Orb' ][ 'premium_count' ],
			                                                            'sidesauce_count' => $orb[ 'Orb' ][ 'sidesauce_count' ]
			                                           ]
			);
			$pricing   = $this->pricing_array( $opt_price, $price_rank, $quantity, $orb );
			// walk current cart (if it exists); if item of identical configuration found, update it's quantity
			$candidate = compact( "orb", 'orbopts', 'pricing' );

			if ( $this->update_quantity( $candidate ) ) return true;

			return $this->Session->write( "Cart.Order.$uid", $candidate );
		}

		private function pricing_array( $opt_price, $price_rank, $quantity, $orb ) {
			$pricing                    = [ 'rank'                  => $price_rank,
			                                'label'                 => $orb[ 'Pricedict' ][ "l$price_rank" ],
			                                'quantity'              => $quantity,
			                                'unit_base_price'       => 1 * $orb[ 'Pricelist' ][ "p$price_rank" ],
			                                'opt_price'             => $opt_price,
			                                'configured_unit_price' =>
				                                1 * $orb[ 'Pricelist' ][ "p$price_rank" ] + $opt_price,
			                                'net'                   =>
				                                $quantity * ( $orb[ 'Pricelist' ][ "p$price_rank" ] + $opt_price ) ];
			$pricing[ 'net_formatted' ] = money_format( "%#3.2n", $pricing[ 'net' ] );

			return $pricing;
		}

		public function edit( $orb_uid, $opt_id ) {
			$orb = $this->find_by_uid( $orb_uid );
			if ( !$orb ) return false;
			if ( !$opt_id ) {
				return $this->remove( $orb_uid, 1 );
			} else {
				unset( $orb[ 'orbopts' ][ $opt_id ] );
				$orb[ 'orbopts' ] = array_filter( $orb[ 'orbopts' ] );
				$included         = [ 'opt_count'       => $orb[ 'orb' ][ 'Orb' ][ 'opt_count' ],
				                      'cheese_count'    => $orb[ 'orb' ][ 'Orb' ][ 'cheese_count' ],
				                      'premium_count'   => $orb[ 'orb' ][ 'Orb' ][ 'premium_count' ],
				                      'sidesauce_count' => $orb[ 'orb' ][ 'Orb' ][ 'sidesauce_count' ] ];
				$opt_price        = $this->price_orbopts( $orb[ 'orbopts' ], $orb[ 'pricing' ][ 'rank' ], $included );
				$orb[ 'pricing' ] = $this->pricing_array( $opt_price, $orb[ 'pricing' ][ 'rank' ], $orb[ 'pricing' ][ 'quantity' ], $orb[ 'orb' ] );
				$this->Session->write( "Cart.Order.$orb_uid", $orb );
				$this->update_invoice();
			}

			return true;
		}

		/**
		 * @param $uid
		 *
		 * @return bool
		 */
		public function find_by_uid( $uid ) {
			$order = $this->Session->read( 'Cart.Order' );
			foreach ( $order as $orb_uid => $orb ) if ( $orb_uid == $uid ) return $orb;

			return false;
		}

		/**
		 * @param        $uid
		 * @param string $quantity
		 *
		 * @return int
		 */
		public function remove( $uid, $quantity ) {
			if ( $this->Session->check( "Cart.Order.$uid" ) ) {
				$quantity        = $this->Session->read( "Cart.Order.$uid.pricing.quantity" ) - $quantity;
				$conf_unit_price = $this->Session->read( "Cart.Order.$uid.pricing.configured_unit_price" );

				// if deleting some but not all of them item, reduce quantity
				if ( $quantity > 0 ) {
					$this->Session->write( "Cart.Order.$uid.pricing.quantity", $quantity );
					$this->Session->write( "Cart.Order.$uid.pricing.configured_unit_price",
					                       $quantity * $conf_unit_price );
				} else {
					$this->Session->delete( "Cart.Order.$uid" );
				}
			}

			$this->update_invoice();

			return true;
		}


		/**
		 * update_invoice()
		 *
		 * @return bool
		 */
		public function update_invoice() {
			if ( !empty( $cart[ 'Order' ] ) ) return;

			$invoice = $this->invoice_template;
			foreach ( $this->Session->read( "Cart.Order" ) as $uid => $oi ) {
				$invoice[ 'subtotal' ] += $oi[ 'pricing' ][ 'net' ];
				$invoice[ 'item_count' ] += $oi[ 'pricing' ][ 'quantity' ];
				$invoice[ 'invoice_rows' ]++;
			}
			$invoice[ 'hst' ]   = $invoice[ 'subtotal' ] * $this->hst_rate;
			$invoice[ 'total' ] = $invoice[ 'subtotal' ] + $invoice[ 'hst' ];
			$this->Session->write( 'Cart.Invoice', $invoice );
			$this->Session->write( 'Cart.Service.deliverable',
			                       $this->Session->read( 'Cart.Invoice.total' ) >= $this->delivery_min );
		}


		public function clear() {
			$this->Session->delete( 'Cart' );
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow( 'add', 'clear', 'remove', 'startup' );
		}
	}
