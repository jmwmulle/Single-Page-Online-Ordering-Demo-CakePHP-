<?php
	App::uses( 'AppController', 'Controller' );

	/**
	 * Orderss Controller
	 *
	 * @property Order                $Order
	 * @property CartComponent        $Cart
	 */
	class OrdersController extends AppController {
		private $orb_template = [  "id"         => -1,
	                               "uid"        => -1,
	                               "quantity"   => -1,
	                               "price_rank" => 0,
	                               "orbopts"    => [],
	                               "orb_note"   => "" ];

		private $cart_template = [ 'Order'   => [ ],
		                                'User'    => [
			                                'Address' => [ 'address'               => null,
			                                               'address_2'             => null,
			                                               'postal_code'           => null,
			                                               'building_type'         => null,
			                                               'phone'                 => null,
			                                               'delivery_instructions' => null,
			                                               'city'                  => 'Halifax',
			                                               'province'              => 'NS',
			                                               'delivery_time'         => null,
			                                               'firstname'             => null,
			                                               'lastname'              => null ]
		                                ],
		                                'Invoice' => [ 'subtotal'     => 0,
		                                               'total'        => 0,
		                                               'item_count'   => 0,
		                                               'receipt_rows' => 0,
		                                               'hst'          => 0 ],
		                                'Service' => [ 'deliverable'  => false,
		                                               'order_method' => JUST_BROWSING,
		                                               'flags'        => [
			                                               "address_valid" => null,
		                                                   "request_address_save" => null
		                                               ] ]
		];

		public $components = array(
			'Cart',
			//		'Security',
			'Paginator',
			#'Paypal',
			#'AuthorizeNet'
		);

		public $uses = array( 'User', 'Address', 'Order', 'Orb', 'Orbopt', 'Optflag' );

		/**
		 * index method
		 *
		 * @return void
		 */
		public function index() {
			//$this->Order->recursive = 0;
			$this->set( 'orders', $this->Paginator->paginate() );
		}

		/**
		 * add method
		 *
		 * @return void
		 */
		public function add() {

			if ( $this->request->is( 'post' ) ) {
				$this->Order->create();
				if ( $this->Order->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The order has been saved.' ) );

					return $this->redirect( ['action' => 'index' ] );
				} else {
					$this->Session->setFlash( __( 'The order could not be saved. Please, try again.' ) );
				}
			}
			$users = $this->Order->User->find( 'list' );
			$this->set( compact( 'users', 'orbs' ) );
		}

		/**
		 * view method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function view( $id = null ) {
			if ( !$this->Order->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid order' ) );
			}
			$options = array( 'conditions' => array( 'Order.' . $this->Order->primaryKey => $id ) );
			$this->set( 'order', $this->Order->find( 'first', $options ) );
		}

		/**
		 * edit method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function edit( $id = null ) {
			$this->init_cart();
			if ( !$this->Order->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid order' ) );
			}
			if ( $this->request->is( array( 'post', 'put' ) ) ) {
				if ( $this->Order->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The order has been saved.' ) );

					return $this->redirect( array( 'action' => 'index' ) );
				} else {
					$this->Session->setFlash( __( 'The order could not be saved. Please, try again.' ) );
				}
			} else {
				$options             = array( 'conditions' => array( 'Order.' . $this->Order->primaryKey => $id ) );
				$this->request->data = $this->Order->find( 'first', $options );
			}
			$users = $this->Order->User->find( 'list' );
			$orbs  = $this->Order->Orb->find( 'list' );
			$this->set( compact( 'users', 'orbs' ) );
		}

		/**
		 * delete method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function delete( $id = null ) {
			$this->Order->id = $id;
			if ( !$this->Order->exists() ) {
				throw new NotFoundException( __( 'Invalid order' ) );
			}
			$this->request->allowMethod( 'post', 'delete' );
			if ( $this->Order->delete() ) {
				$this->Session->setFlash( __( 'The order has been deleted.' ) );
			} else {
				$this->Session->setFlash( __( 'The order could not be deleted. Please, try again.' ) );
			}

			return $this->redirect( array( 'action' => 'index' ) );
		}

		/**
		 * init_cart() called before all cart-related functions; ensures any missing required keys exist
		 * @return bool
		 */
		private function init_cart() {
//			$this->Session->destroy();
			if ( !$this->Session->check( 'Cart' ) ) $this->Session->write( 'Cart', $this->cart_template );
			foreach ( $this->cart_template as $key => $value ) {
				if ( !$this->Session->check( "Cart.$key" ) ) {
					$this->Session->write( "Cart.$key", $value );
				}
				foreach ( $value as $subkey => $subvalue ) {
					if ( !$this->Session->check( "Cart.$key.$subkey" ) ) {
						$this->Session->write( "Cart.$key.$subkey", $subvalue );
					}
				}
			}

			return true;
		}

		/**
		 * add_to_cart method
		 * Adds a new item to the cart session key
		 *
		 * @return the item information in AJAX
		 */
		public function add_to_cart() {
			$this->init_cart();
			if ( $this->is_ajax_post() ) {
				$cart_restore = $this->Session->read( 'Cart' );
				$response     = array( "success"        => true,
				                       "error"          => false,
				                       "cart"           => [ ],
				                       "submitted_data" => $this->request->data
				);

				// process the item, price it and write it to the cart
				foreach ( $this->request->data[ 'Order' ] as $orb ) {
					try {
						$this->Cart->add( array_merge( $this->orb_template, $orb ) );
					} catch ( Exception $e ) {
						$response[ 'success' ] = false;
						$response[ 'error' ]   = $e->getMessage();
						$this->Session->write( 'Cart', $cart_restore );
						break;
					}
				}

				// update the user's invoice in the session, or, if add failed, restore the cart and report the error
				if ( !$response[ 'error' ] ) {
					try {
						$this->Cart->update_invoice();
					} catch ( Exception $e ) {
						$response[ 'success' ] = false;
						$response[ 'error' ]   = $e->getMessage();
						$this->Session->write( 'Cart', $cart_restore );
						$this->render_ajax_response( $response );
					}
				}

				$response[ 'cart' ] = $this->Session->read( 'Cart' );
				$this->render_ajax_response( $response );
			} else {
				return $this->redirect( cakeUrl( array( "controller" => 'menu', "action" => null ) ) );
			}
		}


		/**
		 * Retain user data & service info but remove all food items
		 *
		 * @return mixed
		 */
		public function clear_cart() {
			if ( $this->is_ajax_get() ) {
				$this->Cart->clear();
				$this->init_cart();
				$this->render_ajax_response(['success' => true, 'error' => false, 'data' => false]);
			} else {
				return $this->redirect( cakeUrl( array( "controller" => 'menu', "action" => null ) ) );
			}
		}

//      THIS IS MARKED FOR DELETION; CAN'T FIND ANY REFERENCE TO IT AT THE MOMENT (July 16)
//
//		public function item_update() {
//			if ( $this->is_ajax_post() ) {
//				$this->init_cart();
//				foreach ( $this->request->data[ 'Order' ][ 'Orbs' ] as $orb ) {
//					$id                = isset( $orb[ 'id' ] ) ? $orb[ 'id' ] : null;
//					$quantity          = isset( $orb[ 'quantity' ] ) ? $orb[ 'quantity' ] : null;
//					$price_rank        = isset( $orb[ 'price_rank' ] ) ? $orb[ 'price_rank' ] : null;
//					$orbopts           = isset( $orb[ 'Orbopts' ] ) ? $orb[ 'Orbopts' ] : null;
//					$prep_instructions = isset( $orb[ 'prep_instructions' ] ) ? $orb[ 'prep_instructions' ] : null;
//					array_push( $products, $this->Cart->add( $id, $quantity, $price_rank, $orbopts, $prep_instructions ) );
//				}
//				$cart = $this->Session->read( 'Cart' );
//				echo json_encode( $cart );
//				$this->autoRender = false;
//			} else {
//				return $this->redirect( cakeUrl( array( "controller" => 'menu', "action" => null ) ) );
//			}
//		}


		public function update() {
			$this->Cart->update( $this->request->data[ 'Orb' ][ 'id' ], 1 );
		}


		public function remove_from_cart( $uid, $quantity = 0 ) {
			$this->Cart->remove( $uid, $quantity );

			if ( !empty( $product ) ) {
				$this->Session->setFlash( $product[ 'Orb' ][ 'title' ] .
				                          ' was removed from your shopping cart', 'flash_error'
				);
			}

			return $this->redirect( array( 'action' => 'cart' ) );
		}


		public function update_cart() {
			if ( $this->is_ajax_post() ) {
				foreach ( $this->request->data[ 'Orb' ] as $args ) {
					extract( array_merge( array(
						                      "id"                       => -1,
						                      "quantity"                 => -1,
						                      "price_rank"               => 0,
						                      "orbopts"                  => array(),
						                      "orb_note" => "" ),
					                      $args
					         )
					);
//					$this->Cart->add( $id, $quantity, $price_rank, $orbopts, $preparation_instructions );
				}
			}

			return $this->redirect( array( 'action' => 'cart' ) );
		}

		/**
		 * Review cart only (ie. no order details like payment or service)
		 * @return mixed
		 */
		public function review_cart() {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = 'ajax';
				$this->init_cart();
				$this->set( 'cart', $this->Session->read( 'Cart' ) );
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}


		public function address() {
			$cart = $this->Session->read( 'Cart' );
			if ( !$cart[ 'Order' ][ 'total' ] ) {
				return $this->redirect( '/' );
			}

			if ( $this->Auth->loggedIn() ) {
				$User = $this->User->find( 'first', array( 'conditions' => array(
					                                  'User.id' => $this->Session->read( 'Auth.User.User.id' ) ) )
				); #TODO Investigate why this is two Users deep
				$this->set( 'User', $User[ 'User' ] );
			}

			if ( $this->request->is( 'post' ) ) {

				$this->Order->set( $this->request->data );
				if ( $this->Order->validates() ) {
					$order                     = $this->request->data[ 'Order' ];
					$order[ 'payment_method' ] = 'creditcard';
					$this->Session->write( 'Cart.Order', $order + $cart[ 'Order' ] );

					return $this->redirect( array( 'action' => 'review' ) );
				} else {
					$this->Session->setFlash( 'The form could not be saved. Please, try again.', 'flash_error' );
				}
			}
			if ( !empty( $cart[ 'Order' ] ) ) {
				$this->request->data[ 'Order' ] = $cart[ 'Order' ];
			}
		}

		public function step1() {
			$paymentAmount = $this->Session->read( 'Cart.Order.total' );
			if ( !$paymentAmount ) {
				return $this->redirect( '/' );
			}
			$this->Session->write( 'Cart.Order.payment_method', 'creditcard' );
			$this->Paypal->step1( $paymentAmount );
		}

		public function step2() {

			$token  = $this->request->query[ 'token' ];
			$paypal = $this->Paypal->GetShippingDetails( $token );

			$ack = strtoupper( $paypal[ 'ACK' ] );
			if ( $ack == 'SUCCESS' || $ack == 'SUCESSWITHWARNING' ) {
				$this->Session->write( 'Order.Paypal.Details', $paypal );

				return $this->redirect( array( 'action' => 'review' ) );
			} else {
				$ErrorCode         = urldecode( $paypal[ 'L_ERRORCODE0' ] );
				$ErrorShortMsg     = urldecode( $paypal[ 'L_SHORTMESSAGE0' ] );
				$ErrorLongMsg      = urldecode( $paypal[ 'L_LONGMESSAGE0' ] );
				$ErrorSeverityCode = urldecode( $paypal[ 'L_SEVERITYCODE0' ] );
				echo 'GetExpressCheckoutDetails API call failed. ';
				echo 'Detailed Error Message: ' . $ErrorLongMsg;
				echo 'Short Error Message: ' . $ErrorShortMsg;
				echo 'Error Code: ' . $ErrorCode;
				echo 'Error Severity Code: ' . $ErrorSeverityCode;
				die();
			}
		}

		/**
		 * Review order, ie. cart as well as payment & service info
		 * @return mixed
		 */
		public function review_order() {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = 'ajax';
				$cart         = $this->Session->read( 'Cart' );
				if ( empty( $cart ) ) {
					return $this->redirect( '/' );
				}
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function finalize() {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = 'ajax';
				$response     = array( 'success', 'error', 'order_id' );
				if ( $this->request->is( 'post' ) ) {
					$payment_method = $this->request->data[ 'Order' ][ 'payment_method' ];
					if ( in_array( $payment_method, array( CREDIT_CARD, CASH, DEBIT ) ) ) {
						$this->Session->write( 'Cart.Order.payment_method', $payment_method );
					} else {
						// todo: handle case of invalid payment-method type; probably just bounce
					}
					$cart = $this->Session->read( 'Cart' );
					$this->Order->set( $this->Session->read( 'Cart.Order' ) );
					if ( $this->Order->validates() ) {
						$order = $cart;
						$this->Order->set( 'detail', json_encode( $cart ) );
						$this->Order->set( 'invoice', "Not Yet Implemented" );
						$order[ 'Order' ][ 'status' ] = ORDER_PENDING;

						switch ( $cart[ 'Order' ][ 'payment_method' ] ) {
							case PAYPAL:
								if ( $this->Session->read( 'Cart.Paypal.Details' ) ) {
									$cart[ 'Order' ][ 'first_name' ]       = $cart[ 'Paypal' ][ 'Details' ][ 'FIRSTNAME' ];
									$cart[ 'Order' ][ 'last_name' ]        = $cart[ 'Paypal' ][ 'Details' ][ 'LASTNAME' ];
									$cart[ 'Order' ][ 'email' ]            = $cart[ 'Paypal' ][ 'Details' ][ 'EMAIL' ];
									$cart[ 'Order' ][ 'phone' ]            = '888-888-8888';
									$cart[ 'Order' ][ 'billing_address' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTREET' ];
									$cart[ 'Order' ][ 'billing_address2' ] = '';
									$cart[ 'Order' ][ 'billing_city' ]     = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCITY' ];
									$cart[ 'Order' ][ 'billing_zip' ]      = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOZIP' ];
									$cart[ 'Order' ][ 'billing_state' ]    = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTATE' ];
									$cart[ 'Order' ][ 'billing_country' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCOUNTRYNAME' ];

									$cart[ 'Order' ][ 'shipping_address' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTREET' ];
									$cart[ 'Order' ][ 'shipping_address2' ] = '';
									$cart[ 'Order' ][ 'shipping_city' ]     = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCITY' ];
									$cart[ 'Order' ][ 'shipping_zip' ]      = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOZIP' ];
									$cart[ 'Order' ][ 'shipping_state' ]    = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTATE' ];
									$cart[ 'Order' ][ 'shipping_country' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCOUNTRYNAME' ];

									$cart[ 'Order' ][ 'payment_method' ] = 'paypal';

									$this->Session->write( 'Shop.Order', $cart[ 'Order' ] );
									$paypal = $this->Paypal->ConfirmPayment( $order[ 'Order' ][ 'total' ] );
									$ack    = strtoupper( $paypal[ 'ACK' ] );
									if ( $ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING' ) {
										$order[ 'Order' ][ 'status' ] = ORDER_PAID;
									}
								}
								break;
							case CREDIT_CARD:
								if ( ( Configure::read( 'Settings.AUTHORIZENET_ENABLED' ) == 1 ) ) {
									$payment = array(
										'creditcard_number' => $this->request->data[ 'Order' ][ 'creditcard_number' ],
										'creditcard_month'  => $this->request->data[ 'Order' ][ 'creditcard_month' ],
										'creditcard_year'   => $this->request->data[ 'Order' ][ 'creditcard_year' ],
										'creditcard_code'   => $this->request->data[ 'Order' ][ 'creditcard_code' ],
									);
									try {
										$authorizeNet = $this->AuthorizeNet->charge( $cart[ 'Order' ], $payment );
									} catch ( Exception $e ) {
										$this->Session->setFlash( $e->getMessage() );

										return $this->redirect( array( 'action' => 'review' ) );
									}
									$order[ 'Order' ][ 'authorization' ] = $authorizeNet[ 4 ];
									$order[ 'Order' ][ 'transaction' ]   = $authorizeNet[ 6 ];
								}
								break;
							case DEBIT:
								// todo: confirm debit is available?
								break;
							default:
								// todo: set some default keys if needed, maybe?
						}

						$save = false;

						if ( $this->Auth->loggedIn() ) {
							$this->User->set( 'id', $this->Auth->user[ 'id' ] );
							$save = $this->User->saveAssociated( array( "User"  => $this->User,
							                                            "Order" => $this->Order )
							);
						} else {
							$order = array( 'Order' => array( 'user_id' => -1,
							                                  'state'   => 0,
							                                  'detail'  => json_encode( $cart ),
							                                  'invoice' => $this->invoice( $cart ) ) );

							$save = $this->Order->save( $order );
						}

						if ( $save ) {
							//This sets a view variable for the cart, in case it needs to be displayed
							$this->set( compact( 'cart' ) ); // what's this do? still valid?

							/*App::uses('CakeEmail', 'Network/Email');
							$email = new CakeEmail();
							$email->from('xtremepizzahalifax@gmail.com')
									->cc('xtremepizzahalifax@gmail.com')
									->to($cart['Order']['email'])
									->subject('Xtreme Pizza Order Confirmation')
									->template('order')
									->emailFormat('text')
									->viewVars(array('cart' => $cart))
									->send();*/
							$response = array_combine( $response, array( true, false, $this->Order->id ) );
							//$this->Session->destroy('Cart');
						} else {
							$response = array_combine( $response, array( false, $this->Order->invalidFields(), false )
							);
						}
					} else {
						$response = array_combine( $response, array( false, "Cart didn't validate", false ) );
					}
				} else {
					$response = array_combine( $response, array( false, "Request was not POST", false ) );
				}
				$this->set( compact( 'response' ) );
				$this->render( 'finalize_order' );
			} else {
				return $this->redirect( array( 'controller' => 'menu', 'action' => '' ) );
			}
		}

		private function invoice( $cart ) {
			//todo: make this fucking ledgible haha.
			$address = implode( "\n", $cart[ 'Order' ][ 'address' ] );
			$order   = "";
			foreach ( $cart[ 'OrderItem' ] as $item ) {
				$order .= "\n" . $item[ 'title' ] . "\t (" . $item[ 'subtotal' ] . ")";
			}

			return $address . "\n" . $order;
		}

		public function success() {
			$cart = $this->Session->read( 'Cart' );
			$this->Cart->clear();
			if ( empty( $cart ) ) {
				return $this->redirect( '/' );
			}
			$this->set( compact( 'cart' ) );
		}


		/**
		 * Sets Cart.Service.order_method session key
		 * @param $method
		 */
		public function order_method( $method ) {
			if ( $this->is_ajax_post() ) {
				$this->Session->write( "Cart.Service.order_method", $method );

				// check if current User.address key is incomplete, invalid or valid; save it if valid & logged in
//				$this->Session->write("Cart.Service.flags.address_valid", $this->Order->User->validate_session());
				if ( $this->Auth->loggedIn() and $method == DELIVERY ) {
					$options         = array( 'conditions' => array( 'User.id' => $this->Auth->user( 'id' ) ) );
					$user            = $this->User->find( 'first', $options );
					if ( $this->Session->read("Cart.Service.flags.address_valid") ) {
						if ( !in_array( $this->Session->read( 'User.Address' ), $user[ 'Address' ] ) ) {
							$this->Session->write("Cart.Service.flags.request_save_address", true);
						}
					}
				}
				$response = [ "success" => true, "error" => false, "data" => $this->Session->read("Cart")];
				return $this->render_ajax_response($response);
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		/**
		 * @param null $command
		 *
		 * @return mixed
		 */
		public function confirm_address( $command = null ) {
			if ( $this->request->is( 'ajax' ) ) {
				if ( $this->request->is( 'get' ) ) {
					$this->set(["header" => "Delivery! Yay for sitting!",
								"subheader" => "But let's confirm your address, yeah?"]);
					return $this->render( 'confirm_address' );
				}
				if ( !$this->Session->check( "Cart.Order.address_checked" ) ) {
					$this->Session->write( 'Cart.Order.address_checked', false );
				}
				$data = $this->request->data;
				if ( $command == 'database' ) {
					if ( $this->Auth->loggedIn() ) {
						$conditions = array( 'conditions' => array( 'Address.user_id' => $this->Auth->user( 'id' ),
						                                            'Address.id'      => $data[ 'address_id' ] ) );
						$address    = $this->Address->find( 'first', $conditions );
						$this->Session->write( 'Cart.Order.address_checked', true );
						$this->Session->write( 'Cart.Order.address', $address );
					} else {
						$this->set( "response", array( "success" => false,
						                               "address" => false,
						                               "error"   => "User not logged in." )
						);
					}
				} elseif ( $command == 'update_database' ) {
					if ( $this->Auth->loggedIn() ) {
						$conditions = array( 'conditions' => array( 'User.id' => $this->Auth->user[ 'id' ] ) );
						$this->User->find( 'first', $conditions );
						if ( array_key_exists( 'address_id', $data ) ) {
							$conditions = array( 'conditions' => array( 'Address.user_id' => $this->Auth->user[ 'id' ],
							                                            'Address.id'      => $data[ 'address_id' ] ) );
							$address    = $this->Address->find( 'first', $conditions );
							$address    = array_merge( $address, $data[ 'orderAddress' ] );
							$to_save    = array( 'User' => $this->User, 'Address' => $address );
						} else {
							$to_save = array( 'User' => $this->User, 'Address' => $data[ 'orderAddress' ] );
						}
						if ( $this->User->saveAssociated( $to_save ) ) {
							// I always need all the keys, it's just a lot easier than manually checking
							$this->set( "response", array( "success" => true,
							                               "address" => $data[ 'orderAddress' ],
							                               "error"   => false )
							);
						} else {
							$this->set( "response", array( "success" => false,
							                               "address" => false,
							                               "error"   => "Data could not be saved." )
							);
						}
					} else {
						$this->set( "response", array( "success" => false,
						                               "address" => false,
						                               "error"   => "User not logged in." )
						);
					}
				} elseif ( $command == 'session' ) {
					if ( !empty( $data[ 'orderAddress' ] ) ) {
						$this->Session->write( 'Cart.Order.address', $data[ 'orderAddress' ] );
						$this->Session->write( 'Cart.Order.email', $data[ 'orderAddress' ][ 'email' ] );
						$this->Session->write( 'Cart.Order.delivery_instructions', $data[ 'orderAddress' ][ 'delivery_instructions' ] );
					} else {
						$this->Session->write( 'Cart.Order.triedToSetEmptyAddress', true );
					}
					$this->Session->write( 'Cart.Order.address_checked', true );
					$this->set( "response", array( "success" => true,
					                               "address" => $data [ 'orderAddress' ],
					                               "error"   => false
					                      )
					);
				}
				$this->render( 'confirm_address_reply' ); // this is the JSON reply; this is more reliable for some reason
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function get_status( $id, $first_call = false ) {
			if ( $this->request->is( 'ajax' ) || true ) {
				$this->layout = 'ajax';
				$order        = $this->Order->findById( $id );
				$response     = array( 'success', 'status', 'error' );
				$r            = !empty( $order ) ? array( true,
				                                          $order[ 'Order' ][ 'state' ],
				                                          false ) : array( false,
				                                                           null,
				                                                           "Order not found." );
				$response     = array_combine( $response, $r );
				$this->set( compact( 'response' ) );

				return $first_call ? $this->render( 'get_status' ) : $this->render( 'get_status_update' );
			}
			return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
		}

		public function set_status( $id = null, $status = null ) {
			if ( $this->request->is( 'ajax' ) && $id != null && $status != null ) {
				$order    = $this->Order->findById( $id );
				$response = array( 'success', 'error' );
				if ( $order ) {
					$order[ 'Order' ][ 'state' ] = $status;
					$resp                        = $this->Order->save( $order ) ? array( true,
					                                                                     null ) : array( false,
					                                                                                     'Failed to save updated order' );
					$response                    = array_combine( $response, $resp );
				} else {
					$response = array_combine( $response, array( false, 'Order not found.' ) );
				}
				$this->set( compact( 'response' ) );

				return $this->render();
			} else {
				$this->redirect( "/menu" );
			}
		}

		public function get_pending( $refreshed ) {
			if ( $this->request->is( 'ajax' ) || true ) {
				$this->layout = "ajax";
				$conditions   = array( 'conditions' => array( 'Order.state' => ORDER_PENDING ), 'recursive' => -1 );
				$orders       = $this->Order->find( 'all', $conditions );
				$response     = null;
				$f_orders     = array();
				try {
					foreach ( $orders as $order ) {
						$detail  = json_decode( $order[ 'Order' ][ 'detail' ], true );
						$address = $detail[ 'Order' ][ 'address' ];
						if ( !array_key_exists( 'firstname', $address ) ) {
							$address[ 'firstname' ] = 'Anonymous';
						}
						if ( !array_key_exists( 'lastname', $address ) ) {
							$address[ 'lastname' ] = 'Anonymous';
						}

						$delivery_instructions = false;
						if ( array_key_exists( "delivery_instructions", $detail[ 'Order' ] ) ) {
							$delivery_instructions = $detail[ 'Order' ][ 'delivery_instructions' ];
						}
						$f_order    = array(
							'id'                    => $order[ 'Order' ][ 'id' ],
							'address'               => $address[ 'address' ],
							'customer'              => sprintf( "%s %s", $address[ 'firstname' ], $address[ 'lastname' ] ),
							'order_method'          => $detail[ 'Order' ][ 'order_method' ],
							'payment_method'        => $detail[ 'Order' ][ 'payment_method' ],
							'paid'                  => false,
							'delivery_instructions' => $delivery_instructions,
							'time'                  => $order[ 'Order' ][ 'created' ],
							'price'                 => $detail[ 'Order' ][ 'total' ],
							'food'                  => array()
						);
						$food_array = array();
						foreach ( $detail[ 'OrderItem' ] as $orb ) {
							$opts = array();
							if ( !empty( $orb[ 'orbopts' ] ) ) {
								foreach ( $orb[ 'orbopts' ] as $opt ) {
									$id      = $opt[ 'Orbopt' ][ 'id' ];
									$opts[ ] = array( 'title'  => $opt[ 'Orbopt' ][ 'title' ],
									                  'weight' => $orb[ 'orbopts_arrangement' ][ $id ] );
								}
							}
							$food_array[ $orb[ 'title' ] ] = array( 'size'         => $orb[ 'size_name' ],
							                                        'price'        => $orb[ 'subtotal' ],
							                                        'quantity'     => $orb[ 'quantity' ],
							                                        'opts'         => $opts,
							                                        'instructions' => $orb[ 'preparation_instructions' ] );
						}
						$f_order[ 'food' ] = $food_array;
						$f_orders[ ]       = $f_order;
					}
					$response = array( 'success'   => true,
					                   'error'     => false,
					                   'orders'    => $f_orders,
					                   'refresh'   => true,
					                   'refreshed' => $refreshed );
				} catch ( Exception $e ) {
					$response = array( 'success'   => false,
					                   'error'     => $e,
					                   'orders'    => false,
					                   'refresh'   => true,
					                   'refreshed' => $refreshed );
				}

				$this->set( compact( 'response' ) );
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->disableCache();
			$this->Auth->allow( 'success', 'order_method', 'confirm_address', 'delivery', 'add_to_cart', 'update', 'clear', 'itemupdate', 'remove', 'cartupdate', 'cart', 'address', 'review', 'get_pending', 'set_status', 'get_status', 'finalize' );
		}

		public function vendor() {
			$this->set( 'page_name', 'vendor' );
			if ( $this->request->header( 'User-Agent' ) == "xtreme-pos-tablet" || true ) {
				$this->render( "vendor" );
			} else {
				$this->redirect( "/menu" );
			}
		}
	}
