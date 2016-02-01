<?php

	class CartComponent extends Component {
		public $portion_pricing = array( 'F' => 1, 'L' => 0.5, 'R' => 0.5, 'D' => 2 );
		public $components = array( 'Session' );
		public $Controller;
		private $maxQuantity = 99;
		private $hst_rate = 0.15;
		private $bottle_deposit = 0.1;
		private $delivery_min = 10;
		private $delivery_leeway = 0.05;


		public function __construct( ComponentCollection $collection, $settings = array() ) {
			$this->Controller = $collection->getController();
			parent::__construct( $collection, array_merge( $this->settings, (array)$settings ) );
		}

		public function startup( Controller $controller ) { }

		private function invoice_template() {
			return [ 'subtotal'     => 0,
				                              'total'        => 0,
				                              'item_count'   => 0,
				                              'invoice_rows' => 0,
				                              'hst'          => 0 ];
		}

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
			$price_map = [ 'sauces'     => [ 'count'    => 0,
			                                'included' => 1,
			                                'price'    => 0 ],
			               'sidesauces' => [ 'count'    => 0,
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
				if ( !$orbopt['Orbopt']['included'] ) continue;
				$ot = $orbopt['Orbopt']['title'];
				$price = $orbopt[ 'Pricelist' ][ "p$price_rank" ];

				//ie. if opt !has flags but exists here (opts filtered by price_factor = true), it's a regular opt
				if ( empty($orbopt['Optflag']) ) array_push( $orbopt[ 'Optflag' ], [ 'title' => 'opt' ] );

				foreach ( $orbopt[ 'Optflag' ] as $fl ) {
					$index = array_key_exists( $fl[ 'title' ], $price_map ) ? $fl[ 'title' ] : "opt";
					$counting[$ot] = compact('fl', 'index');
					$score = 1;
					if ($orbopt['Orbopt']['coverage'] == "D") $score = 2;
					if (in_array($orbopt['Orbopt']['coverage'], ["R","L"]) ) $score = 0.5;
					$price_map [ $index ][ 'count' ] += $score;
					if ($index == 'premium') $price_map['opt']['count'] += $score ;
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

		private function online_launch_special() {
			$debug = [];
			$order = $this->Session->read('Cart');
			$free_eggroll_id = 254;
			$garlic_finger_id = 250;
			$qualifies = false;
			$garlic_finger_uid = null;
			$free_eggroll_uid = null;
			$debug['data'] = $order;
			$debug['items'] = [];

			foreach ($order['Order'] as $uid => $oi) {
				array_push($debug['items'], $oi);
				$this->Session->write('Cart.Debug.special', $debug);
				if ($oi['orb']['Orbcat']['title'] == "PIZZAS" && $oi['pricing']['rank'] == 3) {
					$qualifies = true;
				}
				if ($oi['orb']['Orb']['id'] == $free_eggroll_id) $free_eggroll_uid = $uid;
				if ($oi['orb']['Orb']['id'] == $garlic_finger_id) $garlic_finger_uid = $uid;
			}

			$this->Session->write('Cart.Debug.special', $debug);
			if ($qualifies && !$free_eggroll_uid) {
				$this->add(['id' => $free_eggroll_id,
                          'uid' => $free_eggroll_id."_".time(),
                          'quantity' => 2,
                          'price_rank' => 1,
				          'orb_note' => null
				           ], $check_special=false);
				$this->add(['id' => $garlic_finger_id,
                          'uid' => $garlic_finger_id."_".time(),
                          'quantity' => 1,
                          'price_rank' => 1,
				          'orb_note' => null
				           ], $check_special=false);
			}
			if (!$qualifies && $free_eggroll_uid && $garlic_finger_uid) {
				$this->remove($free_eggroll_uid, 2, true);
				$this->remove($garlic_finger_uid, 2, true);
			}
			return;
		}


		/**
		 * @param $orb_cfg
		 * @param $check_special
		 *
		 * @return mixed|null
		 */
		public function add( $orb_cfg, $check_special=true ) {
			// $orb_cfg = [orb_id, uid, quantity, price_rank, orbopts]
			$orb_id     = $orb_cfg[ 'id' ];
			$uid        = $orb_cfg[ 'uid' ];
			$quantity   = $orb_cfg[ 'quantity' ];
			$price_rank = $orb_cfg[ 'price_rank' ] + 1; // table columns are p1...p5, indices are 0...4
			$orbopts    = array_key_exists( 'Orbopt', $orb_cfg ) ? $orb_cfg[ 'Orbopt' ] : [ ];

			$quantity = !is_numeric( $quantity ) ? 1 : abs( $quantity );
			if ( $quantity == 0 ) return null;

			if ( $quantity > $this->maxQuantity ) $quantity = $this->maxQuantity;


			// get orb details from id
			$orb                    = $this->Controller->Orb->find( 'first', [ 'recursive'  => 0,
			                                                                   'conditions' => [ 'Orb.id' => $orb_id ] ]
			);

			$orb[ 'Orb' ][ 'note' ] = $orb_cfg[ 'orb_note' ];

			if ( empty( $orb ) ) return null; // if bad orb_id passed, exit quietly

			// get orbopt data
			$orbopts = $this->fetch_orbopts_from_ids( $orbopts );
			$default_orbopts = $this->Controller->OrbsOrbopt->find('all', [ 'conditions' => [
																						'`OrbsOrbopt`.`orb_id`' => $orb_id,
																						'`OrbsOrbopt`.`default`' => true]]);
			$default_opt_ids = Hash::extract($default_orbopts, "{n}.Orbopt.id");
			$coverage_mask = [];
			if (count($default_opt_ids) > 0) {
				$coverage_mask = array_fill(0, count($default_opt_ids), "F");
			}
			$default_orbopts = $this->fetch_orbopts_from_ids(array_combine($default_opt_ids, $coverage_mask));

			foreach ($orbopts as $id => $opt) {
				$orbopts[$id]['Orbopt']['default'] = array_key_exists($opt['Orbopt']['id'], $default_orbopts);
				$orbopts[$id]['Orbopt']['included'] = true;
			}
//			foreach ($default_orbopts as $id => $opt) {
//				if ( !array_key_exists($id, $orbopts) )  {
//					$opt['Orbopt']['default'] = true;
//					$opt['Orbopt']['included'] = false;
//					$orbopts[$id] = $opt;
//				}
//			}

//			db($orbopts);


//			foreach ($orbopts as $id => $opt) {
//				$default = $this->Controller->OrbsOrbopt->find('first', [
//				                                                         'conditions' => [
//																			  '`OrbsOrbopt`.`orb_id`' => $orb_id,
//				                                                              '`OrbsOrbopt`.`orbopt_id`' => $id,
//				                                                              '`OrbsOrbopt`.`default`' => true]]);
//				$orbopts[$id]['Orbopt']['default'] = !empty($default);
//			}
//			$this->Session->write("Cart.Debug.default_opts", $debug);
			// get the pricing info for each opt
			$opt_price = $this->price_orbopts( $orbopts, $price_rank, [ 'opt_count'       => $orb[ 'Orb' ][ 'opt_count' ],
			                                                            'cheese_count'    => $orb[ 'Orb' ][ 'cheese_count' ],
			                                                            'premium_count'   => $orb[ 'Orb' ][ 'premium_count' ],
			                                                            'sidesauce_count' => $orb[ 'Orb' ][ 'sidesauce_count' ]
			                                           ]
			);
			$pricing   = $this->pricing_array( $opt_price, $price_rank, $quantity, $orb );
			// walk current cart (if it exists); if item of identical configuration found, update it's quantity
			$candidate = compact( "orb", 'orbopts', 'default_orbopts', 'pricing' );

			if ( !$this->update_quantity( $candidate ) ) $this->Session->write( "Cart.Order.$uid", $candidate );
			if ($check_special) $this->online_launch_special();
			return true;
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
				$this->online_launch_special();
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
		public function remove( $uid, $quantity, $called_from_launch_special=False ) {
			if ( $this->Session->check( "Cart.Order.$uid" ) ) {
				$quantity        = $this->Session->read( "Cart.Order.$uid.pricing.quantity" ) - $quantity;
				$conf_unit_price = $this->Session->read( "Cart.Order.$uid.pricing.configured_unit_price" );

				// if deleting some but not all of them item, reduce quantity
				if ( $quantity > 0 ) {
					$this->Session->write( "Cart.Order.$uid.pricing.quantity", $quantity );
					$this->Session->write( "Cart.Order.$uid.pricing.net", $quantity * $conf_unit_price );
				} else {
					$this->Session->delete( "Cart.Order.$uid" );
				}
			}
			if (!$called_from_launch_special) $this->online_launch_special();
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

			$invoice = $this->invoice_template();
			foreach ( $this->Session->read( "Cart.Order" ) as $uid => $oi ) {
				$invoice[ 'subtotal' ] += $oi[ 'pricing' ][ 'net' ];
				$invoice[ 'item_count' ] += $oi[ 'pricing' ][ 'quantity' ];
				$invoice[ 'invoice_rows' ]++;
			}
			$invoice[ 'hst' ]   = round($invoice[ 'subtotal' ] * $this->hst_rate, 2);
			$invoice[ 'total' ] = $invoice[ 'subtotal' ] + $invoice[ 'hst' ];
			$debug = [];
			$debug['total'] = $invoice['total'];
			$debug['substr'] = (int) substr($invoice['total'], -1);
			$invoice_int = $invoice['total'] * 100;
			$mod = $invoice_int % 5;
			$debug['invoice_int'] = $invoice_int;
			$debug['modulo_5'] = $mod;
			if ($mod) {
				$debug['case'] = false;
				$debug['modulo_5'] = false;
				switch ( $mod ) {
					case 1:
						$invoice['total'] -= 0.01;
						$debug['case'] = 1;
						break;
					case 2:
						$invoice['total'] -= 0.02;
						$debug['case'] = 2;
						break;
					case 3:
						$invoice['total'] += 0.02;
						$debug['case'] = 3;
						break;
					case 4:
						$invoice['total'] += 0.01;
						$debug['case'] = 4;
						break;
					case 6:
						$invoice['total'] -= 0.01;
						$debug['case'] = 6;
						break;
					case 7:
						$invoice['total'] -= 0.02;
						$debug['case'] = 7;
						break;
					case 8:
						$invoice['total'] += 0.02;
						$debug['case'] = 8;
						break;
					case 9:
						$invoice['total'] += 0.01;
						$debug['case'] = 9;
						break;
				}
			$debug['new_total'] = $invoice['total'];
			}

			$this->Session->write( 'Cart.updateInvoice', $debug);
			$this->Session->write( 'Cart.Invoice', $invoice );
			$deliverable = $invoice['total'] >= $this->delivery_min - ($this->delivery_min * $this->delivery_leeway);
			$this->Session->write( 'Cart.Service.deliverable', $deliverable);
		}


		public function clear() {
			$this->Session->delete( 'Cart' );
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow( 'add', 'clear', 'remove', 'startup' );
		}
	}
