<?php

	class CartComponent extends Component {

		public $components = array( 'Session' );

		public $Controller;

		public function __construct(ComponentCollection $collection, $settings = array()) {
			$this->Controller = $collection->getController();
			parent::__construct( $collection, array_merge( $this->settings, (array)$settings ) );
		}

		public function startup(Controller $controller) {
		}

		public $maxQuantity = 99;

		/**
		 * returns array of orbopt model records from values of orbopt_ids array
		 * @param $orbopt_ids
		 *
		 * @return array
		 */
		private function fetch_orbopts_from_ids($orbopt_ids) {
			$this->Controller->Orbopt->Behaviors->load( 'Containable' );
			foreach ( $orbopt_ids as $opt_id => $coverage ) {
				if ($coverage != -1) {
					$conditions = array( 'recursive'  => 1,
										 'conditions' => array('Orbopt.id' => $opt_id),
										 'contain'    => array( 'Pricelist',
										                        'Optflag' => array(
											                        'conditions' => array('price_factor' => 1)
										                        )));
					$orbopt_ids[$opt_id] =  $this->Controller->Orbopt->find( 'first', $conditions);
					$orbopt_ids[$opt_id]['Orbopt']['coverage'] = $coverage;
				} else { unset($orbopt_ids[$opt_id]); }
			}
			return array_filter($orbopt_ids);
		}

		private function orbopt_pricing_map($orbopts) {
			$priceable_flags['regular'] = 0;
			foreach ( $orbopts as $id => $orbopt ) {
				$opts_by_val = array_values( $orbopt[ 'Pricelist' ] );
				foreach($orbopt['Optflag'] as $of_id => $flag) {
					try {
						$priceable_flags[$flag['title']]++;
					} catch (Exception $e) {
						$priceable_flags['regular']++;
					}
				}
			}
			return $priceable_flags;
		}

		public function add($orb_cfg) {
			 // $orb_cfg = [orb_id, uid, quantity, price_rank, orbopts, preparation_instructions]
			$position_to_price = array( 'F' => 1, 'L' => 0.5, 'R' => 0.5, 'D' => 2 );
			$quantity = !is_numeric( $orb_cfg['quantity'] ) ? 1 : abs( $orb_cfg['quantity'] );
			if ( $quantity > $this->maxQuantity ) $quantity = $this->maxQuantity;

			// get orb details from id
			$orb = $this->Controller->Orb->find( 'first', array( 'conditions' => array('Orb.id' => $orb_cfg['id'])));

			// if bad orb_id passed, exit quietly
			if ( empty( $orb ) ) { return null; }

			// get orbopt data
			$orbopts = $this->fetch_orbopts_from_ids($orb_cfg['orbopts']);
			return $orbopts;
			// get the pricing info for each opt

			$opt_price_cfg = $this->orbopt_pricing_map($orbopts);
			return $opt_price_cfg;
			$prices       = array_values( $orb[ 'Pricelist' ] );
			$size_names   = array_values( $orb[ 'Pricedict' ] );
			$matched      = false;
			$current_data = array();
			if ( $this->Session->check( 'Cart.OrderItem' ) ) {
				$current_data = $this->Session->read( 'Cart.OrderItem' );
				foreach ( $current_data as $id => $item ) {
					$cur_item                          = compact( "price_rank", "orb_id", "preparation_instructions" );
					$cur_item[ 'orbopts_arrangement' ] = $orbopts_list;
					if ( array_intersect_key( $item, $cur_item ) == $cur_item
					) { #Consider making this a hash table for speed
						if ( $item[ 'quantity' ] + $quantity <= 0 ) {
							$this->Session->delete( 'Cart.OrderItem.' . $id );
						}
						else {
							$item[ 'quantity' ] += $quantity;
							if ( $item[ 'quantity' ] > $this->maxQuantity ) {
								$this[ 'quantity' ] = $this->maxQuantity;
							}
							$this->Session->write( 'Cart.OrderItem.' . $id . '.quantity', $item[ 'quantity' ] );
							$this->Session->write( 'Cart.OrderItem.' . $id . '.subtotal',
								sprintf( '%01.2f', ( $item[ 'base_price' ] + array_sum( $item[ 'orbopts_prices' ] ) ) *
								                   $item[ 'quantity' ]
								)
							);
							$matched = true;
						}
					}
				}
			}
			rsort( $regular_opts_prices );
			rsort( $premium_opts_prices );
			$regular_opts_prices = array_slice( $regular_opts_prices, $orb[ 'Orb' ][ 'opt_count' ] );
			$premium_opts_prices = array_slice( $premium_opts_prices, $orb[ 'Orb' ][ 'premium_count' ] );
			$orbopts_prices      = array_merge( $regular_opts_prices, $premium_opts_prices );
			if ( !$matched && $quantity > 0 ) {
				$item[ 'orb_id' ]                   = $orb[ 'Orb' ][ 'id' ];
				$item[ 'uid' ]                      = $uid;
				$item[ 'title' ]                    = $orb[ 'Orb' ][ 'title' ];
				$item[ 'description' ]              = $orb[ 'Orb' ][ 'description' ];
				$item[ 'price_rank' ]               = $price_rank;
				$item[ 'base_price' ]               = $prices[ $price_rank + 1 ]; #id is index 0, 1-6 are prices/sizes
				$item[ 'size_name' ]                = $size_names[ $price_rank + 1 ];
				$item[ 'orbopts' ]                  = $orbopts;
				$item[ 'orbopts_prices' ]           = empty( $opts_prices ) ? array() : $opts_prices;
				$item[ 'orbopts_arrangement' ]      = $orbopts_list;
				$item[ 'preparation_instructions' ] = $preparation_instructions;
				$item[ 'quantity' ]                 = $quantity;
				$item[ 'subtotal' ]                 = sprintf( '%01.2f',
					( $item[ 'base_price' ] + array_sum( $item[ 'orbopts_prices' ] ) ) * $quantity
				);

				$current_data[ ] = $item;

				$this->Session->write( 'Cart.OrderItem', $current_data );

				return $item;
			}
			else {
				return null;
			}
		}

		public function remove($id) {
			if ( $this->Session->check( 'Cart.OrderItem.' . $id ) ) {
				$order_item = $this->Session->read( 'Cart.OrderItem.' . $id );
				$this->Session->delete( 'Cart.OrderItem.' . $id );

				return $order_item;
			}

			return false;
		}

		public function update() {
			$cart             = $this->Session->read( 'Cart' );
			$quantity         = 0;
			$subtotal         = 0;
			$total            = 0;
			$order_item_count = 0;
			$HST_MULT         = 0.15;
			$HST              = 0;
			$delivery         = 3.00;
			$deliverable      = false;
			if ( !array_key_exists( 'OrderItem', $cart ) ) {
				$cart[ 'OrderItem' ] = array();
				$this->Session->write( 'Cart.OrderItem', array() );
			}
			if ( count( $cart[ 'OrderItem' ] ) > 0 ) {
				foreach ( $cart[ 'OrderItem' ] as $item ) {
					$quantity += $item[ 'quantity' ];
					$subtotal += $item[ 'subtotal' ];
					$HST += $item[ 'subtotal' ] * $HST_MULT;
					$order_item_count++;
				}
				$delivery = $subtotal >= 10.0 ? 0.00 : 3.00;
				$total    = $subtotal + $HST + $delivery;
				$this->Session->write( 'Cart.Order.order_item_count', $order_item_count );
				$this->Session->write( 'Cart.Order.quantity', $quantity );
				$this->Session->write( 'Cart.Order.subtotal', sprintf( '%01.2f', $subtotal ) );
				$this->Session->write( 'Cart.Order.HST', sprintf( '%01.2f', $HST ) );
				$this->Session->write( 'Cart.Order.delivery', sprintf( '%01.2f', $delivery ) );
				$this->Session->write( 'Cart.Order.total', sprintf( '%01.2f', $total ) );
				$this->Session->write( 'Cart.Order.deliverable', $total >= 10.0 );
				if ( !$this->Session->check( 'Cart.Order.order_method' ) ) {
					$this->Session->write( 'Cart.Order.order_method', 'just_browsing' );
				}
				if ( !$this->Session->check( 'Cart.Order.email' ) ) {
					$this->Session->write( 'Cart.Order.email', '' );
				}
				if ( !$this->Session->check( 'Cart.Order.address' ) ) {
					$this->Session->write( 'Cart.Order.address', array( 'address'               => '',
					                                                    'address_2'             => '',
					                                                    'postal_code'           => '',
					                                                    'building_type'         => '', 'phone' => '',
					                                                    'delivery_instructions' => ''
					                                                    , 'city'                => 'Halifax',
					                                                    'province'              => 'NS',
					                                                    'delivery_time'         => '',
					                                                    'firstname'             => '',
					                                                    'lastname'              => '' )
					);
				}

				return true;
			}
			else {
				$this->Session->write( 'Cart.Order.quantity', 0 );
				$this->Session->write( 'Cart.Order.subtotal', 0 );
				$this->Session->write( 'Cart.Order.total', 0 );
				$this->Session->write( 'Cart.Order.deliverable', false );

				return false;
			}
		}

		public function clear() {
			$this->Session->delete( 'Cart' );
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow( 'add', 'clear', 'remove', 'startup' );
		}
	}
