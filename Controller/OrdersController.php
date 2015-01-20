<?php
	App::uses( 'AppController', 'Controller' );

	class OrdersController extends AppController {


		public $components = array(
			'Cart',
			//		'Security',
			'Paginator',
			#'Paypal',
			#'AuthorizeNet'
		);

		public $uses = array( 'User', 'Address', 'Order', 'Orb', 'Orbopt' );

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
		 * view method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function view($id = null) {
			if ( !$this->Order->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid order' ) );
			}
			$options = array( 'conditions' => array( 'Order.' . $this->Order->primaryKey => $id ) );
			$this->set( 'order', $this->Order->find( 'first', $options ) );
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

					return $this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( __( 'The order could not be saved. Please, try again.' ) );
				}
			}
			$users = $this->Order->User->find( 'list' );
			$this->set( compact( 'users', 'orbs' ) );
		}

		/**
		 * add_to_cart method
		 * Adds a new item to the cart session key
		 *
		 * @return the item information in AJAX
		 */
		public function add_to_cart() {
//			if ( $this->request->is( 'ajax' ) ) $this->layout = "ajax";
			//if ( $this->request->is( 'get' ) ) {
			//	$this->render();
			//	return;
			//}
			$products = array();
			foreach ( $this->request->data[ 'Order' ] as $orb ) {
				extract( array_merge( array(
							"id"                       => -1,
							"quantity"                 => -1,
							"price_rank"               => 0,
							"orbopts"                  => array(),
							"preparation_instructions" => "" ),
						$orb
					)
				);
				$item = $this->Cart->add( $id, $quantity, $price_rank, $orbopts, $preparation_instructions );
				array_push( $products, $item );
			}
			$this->Cart->update();
			if ( !empty( $products ) ) {
				if ( $this->Session->check( 'Cart.Order.total' ) ) {
					$total = $this->Session->read( 'Cart.Order.total' );
				}
				else {
					$total = null;
				}

				$this->set( "response", json_encode( array( "Order"   => array( "Orbs" => $products ),
				                                            "success" => true, "cart_total" => $total )
					)
				);
			}
			else {
				$this->set( "response", json_encode( array( "orb" => null, "success" => false, "cart_total" => null )
					)
				);
			}
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
		public function edit($id = null) {
			if ( !$this->Order->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid order' ) );
			}
			if ( $this->request->is( array( 'post', 'put' ) ) ) {
				if ( $this->Order->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The order has been saved.' ) );

					return $this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( __( 'The order could not be saved. Please, try again.' ) );
				}
			}
			else {
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
		public function delete($id = null) {
			$this->Order->id = $id;
			if ( !$this->Order->exists() ) {
				throw new NotFoundException( __( 'Invalid order' ) );
			}
			$this->request->allowMethod( 'post', 'delete' );
			if ( $this->Order->delete() ) {
				$this->Session->setFlash( __( 'The order has been deleted.' ) );
			}
			else {
				$this->Session->setFlash( __( 'The order could not be deleted. Please, try again.' ) );
			}

			return $this->redirect( array( 'action' => 'index' ) );
		}


		public function clear() {
			$this->layout = "ajax";
			$this->Cart->clear();
			$this->set( 'response', json_encode( array( 'success' => true ) ) );
			$this->autoRender = false;
		}


		public function itemupdate() {
			if ( $this->request->is( 'ajax' ) ) {

				foreach ( $this->request->data[ 'Order' ][ 'Orbs' ] as $orb ) {
					$id                = isset( $orb[ 'id' ] ) ? $orb[ 'id' ] : null;
					$quantity          = isset( $orb[ 'quantity' ] ) ? $orb[ 'quantity' ] : null;
					$price_rank        = isset( $orb[ 'price_rank' ] ) ? $orb[ 'price_rank' ] : null;
					$orbopts           = isset( $orb[ 'Orbopts' ] ) ? $orb[ 'Orbopts' ] : null;
					$prep_instructions = isset( $orb[ 'prep_instructions' ] ) ? $orb[ 'prep_instructions' ] : null;
					array_push( $products, $this->Cart->add( $id, $quantity, $price_rank, $orbopts, $prep_instructions ) );
				}
				$cart = $this->Session->read( 'Cart' );
				echo json_encode( $cart );
				$this->autoRender = false;
			}
			else {
				return $this->redirect( cakeUrl( array( "controller" => 'menu', "action" => null ) ) );
			}
		}


		public function update() {
			$this->Cart->update( $this->request->data[ 'Orb' ][ 'id' ], 1 );
		}


		public function remove($id = null) {
			$product = $this->Cart->remove( $id );
			if ( !empty( $product ) ) {
				$this->Session->setFlash( $product[ 'Orb' ][ 'title' ] .
				                          ' was removed from your shopping cart', 'flash_error'
				);
			}

			return $this->redirect( array( 'action' => 'cart' ) );
		}


		public function cartupdate() {
			if ( $this->request->is( 'post' ) ) {
				foreach ( $this->request->data[ 'Orb' ] as $args ) {
					extract( array_merge( array(
								"id"                       => -1,
								"quantity"                 => -1,
								"price_rank"               => 0,
								"orbopts"                  => array(),
								"preparation_instructions" => "" ),
							$args
						)
					);
					$this->Cart->add( $id, $quantity, $price_rank, $orbopts, $preparation_instructions );
				}
			}

			return $this->redirect( array( 'action' => 'cart' ) );
		}


		public function cart() {
			$cart = $this->Session->read( 'Cart' );
			$this->Cart->update();
			if ( $this->request->is( 'ajax' ) ) {
				$to_return = array();
				if ( !array_key_exists( 'OrderItem', $cart ) ) {
					$cart[ 'OrderItem' ] = array();
					$this->Session->write( 'Cart.OrderItem', array() );
				}
				foreach ( $cart[ 'OrderItem' ] as $item ) {
					if ( !isset( $to_return[ intval( $item[ 'orb_id' ] ) ] ) ) {
						$to_return[ intval( $item[ 'orb_id' ] ) ] = array();
					}
					$to_return[ intval( $item[ 'orb_id' ] ) ][ ] = $item;
				}
				$cart[ 'OrderItem' ] = $to_return;
			}
			$this->set( compact( 'cart' ) );
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
				}
				else {
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
			}
			else {
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


		public function review() {

			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = 'ajax';
				$cart         = $this->Session->read( 'Cart' );

				if ( empty( $cart ) ) {
					return $this->redirect( '/' );
				}
			}
			else {
				$this->redirect( "/menu" );
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
					}
					else {
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
						}
						else {
							$order = array( 'Order' => array( 'user_id' => -1,
							                                  'state'   => 0,
							                                  'detail'  => json_encode( $cart ),
							                                  'invoice' => $this->invoice( $cart ) ) );

							$save = $this->Order->save( $order );
						}

						if ( $save ) {
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
//							$this->Session->destroy('Cart');
						}
						else {
							$response = array_combine( $response, array( false, $this->Order->invalidFields(), false )
							);
						}
					}
					else {
						$response = array_combine( $response, array( false, "Cart didn't validate", false ) );
					}
				}
				else {
					$response = array_combine( $response, array( false, "Request was not POST", false ) );
				}
				$this->set( compact( 'response' ) );
				$this->render( 'finalize_order' );
			}
			else {
				return $this->redirect( array( 'controller' => 'menu', 'action' => '' ) );
			}
		}

		private function invoice($cart) {
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


		/* order_method */
		public function order_method($method) {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = 'ajax';
				if ( $this->request->is( 'post' ) ) {
					if ( !$this->Session->read( "Cart.Order.address_checked" ) ) {
						$this->Session->write( 'Cart.Order.address_checked', false );
					}
					if ( in_array( $method, array( DELIVERY, PICKUP, JUST_BROWSING ) ) ) {
						$this->Session->write( "Cart.Order.order_method", $method );
						if ( $this->Auth->loggedIn() and $method == DELIVERY ) {
							$options         = array( 'conditions' => array( 'User.id' => $this->Auth->user( 'id' ) ) );
							$user            = $this->User->find( 'first', $options );
							$address_matches = in_array( $this->Session->read( 'User.Address' ), $user[ 'Address' ] );
						}
						else {
							$address_matches = null;
						}
						$this->Session->write( "User.address_matches", $address_matches );
						$this->set( "response", array( "success" => true,
						                               'matches' => $address_matches,
						                               'error'   => false )
						);
					}
					else {
						$this->set( "response", array( "success" => false,
						                               'matches' => false,
						                               'error'   => "Invalid order method" )
						);
					}
				}
				$this->render( 'order_method' );
			}
			else {
				return $this->redirect( '/menu' );
			}
		}

		/*confirm_address*/
		public function confirm_address($command = null) {
			if ( $this->request->is( 'ajax' ) ) {
				if ( $this->request->is( 'post' ) ) {
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
						}
						else {
							$this->set( "response", array( "success" => false,
							                               "address" => false,
							                               "error"   => "User not logged in." )
							);
						}
					}
					elseif ( $command == 'update_database' ) {
						if ( $this->Auth->loggedIn() ) {
							$conditions = array( 'conditions' => array( 'User.id' => $this->Auth->user[ 'id' ] ) );
							$this->User->find( 'first', $conditions );
							if ( array_key_exists( 'address_id', $data ) ) {
								$conditions = array( 'conditions' => array( 'Address.user_id' => $this->Auth->user[ 'id' ],
								                                            'Address.id'      => $data[ 'address_id' ] ) );
								$address    = $this->Address->find( 'first', $conditions );
								$address    = array_merge( $address, $data[ 'orderAddress' ] );
								$to_save    = array( 'User' => $this->User, 'Address' => $address );
							}
							else {
								$to_save = array( 'User' => $this->User, 'Address' => $data[ 'orderAddress' ] );
							}
							if ( $this->User->saveAssociated( $to_save ) ) {
								// I always need all the keys, it's just a lot easier than manually checking
								$this->set( "response", array( "success" => true,
								                               "address" => $data[ 'orderAddress' ],
								                               "error"   => false )
								);
							}
							else {
								$this->set( "response", array( "success" => false,
								                               "address" => false,
								                               "error"   => "Data could not be saved." )
								);
							}
						}
						else {
							$this->set( "response", array( "success" => false,
							                               "address" => false,
							                               "error"   => "User not logged in." )
							);
						}
					}
					elseif ( $command == 'session' ) {
						if ( !empty( $data[ 'orderAddress' ] ) ) {
							$this->Session->write( 'Cart.Order.address', $data[ 'orderAddress' ] );
							$this->Session->write( 'Cart.Order.email', $data[ 'orderAddress' ][ 'email' ] );
							$this->Session->write( 'Cart.Order.delivery_instructions', $data[ 'orderAddress' ][ 'delivery_instructions' ] );
						}
						else {
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
				}
				else {
					$this->render( 'confirm_address' );
				} // this is the confirm address form; no longer in order_method
			}
			else {
				return $this->redirect( array( 'controller' => 'menu', 'action' => 'index' ) );
			}
		}

		public function get_status($id) {
			if ($this->request->is('ajax')) {
				$this->layout = 'ajax';
				$conditions = array('conditions' => array('Order.id' => $id));
				if ($this->Order->find('first', $conditions)) {
					$this->set('response', array('success'=>true, 'status'=>$this->Order->state, 'error'=>Null));
				} else {
					$this->set('response', array('success'=>false, 'status'=>Null, 'error'=>'Order not found.'));
				}
				return $this->render();
			}
			$this->redirect( "/menu" );
		}

		public function set_status($id, $status) {
			if ( $this->request->is( 'ajax' ) ) {
				$conditions = array( 'conditions' => array( 'Order.id' => $id ) );
				if ( $this->Order->find( 'first', $conditions ) ) {
					$this->Order->set( 'state', $status );
					if ( $this->Order->save() ) {
						$this->set( 'response', array( 'success' => true, 'error' => null ) );
					}
					else {
						$this->set( 'response', array( 'success' => false,
						                               'error'   => 'Failed to save updated order.' )
						);
					}
				}
				else {
					$this->set( 'response', array( 'success' => false, 'error' => 'Order not found.' ) );
				}
				return $this->render();
			}
			$this->redirect("/menu");
		}

		public function get_pending() {
			if ( $this->request->is( 'ajax' ) ) {
				$conditions = array( 'conditions' => array( 'Order.state' => ORDER_PENDING ) );
				$this->set( 'Orders', $this->Order->find( 'all', $conditions ) );
			}
			else {
				return $this->redirect("/menu");
			}
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->disableCache();
			$this->Auth->allow( 'success', 'order_method', 'confirm_address', 'delivery', 'add_to_cart', 'update', 'clear', 'itemupdate', 'remove', 'cartupdate', 'cart', 'address', 'review', 'index', 'view', 'get_pending', 'set_status', 'get_status', 'finalize' );
		}
	}
