<?php
	App::uses( 'AppController', 'Controller' );

	/**
	 * Orderss Controller
	 *
	 * @property Order                $Order
	 * @property CartComponent        $Cart
	 */
	class OrdersController extends AppController {
		public $components = array(
			'Cart',
			//		'Security',
			'Paginator',
			#'Paypal',
			#'AuthorizeNet'
		);
		public $uses = array( 'User', 'Address', 'Order', 'Orb', 'Orbopt', 'Optflag' );
		private $orb_template = [ "id"         => -1,
		                          "uid"        => -1,
		                          "quantity"   => -1,
		                          "price_rank" => 0,
		                          "orbopts"    => [ ],
		                          "orb_note"   => "" ];
		private $test_user = [
			'firstname'      => "Jimmy",
			'lastname'       => "TheKid",
			'email'          => "jimmy-the-kid@jimmyserv.com",
			"email_verified" => true,
			"Address"        => [ [ 'id'            => 0,
			                        'firstname'     => "Jimmy",
			                        'lastname'      => "TheKid",
			                        'address'       => "1234 Somewhere",
			                        'address_2'     => "Apt. 7",
			                        'phone'         => 9027187019,
			                        'email'         => "heyo_i_have_a_damn_long_email_of_ffs_i_hate_these_peopel@gmail.com",
			                        'building_type' => "Apartment",
			                        'note'          => "You'll have to answer all 3 of the troll's questions to enter...",
			                        'postal_code'   => "B4r6j8",
			                        'delivery_time' => null,
			                        'city'          => "Halifax",
			                        'province'      => "Nova Scotia" ],
			                      [ 'id'            => 1,
			                        'firstname'     => "Jimmy",
			                        'lastname'      => "TheKid",
			                        'address'       => "123-6B MyHouse St.",
			                        'address_2'     => null,
			                        'phone'         => 9027187019,
			                        'email'         => "better_address@hotmail.com",
			                        'building_type' => "House",
			                        'note'          => null,
			                        'postal_code'   => "B1J2F3",
			                        'delivery_time' => null,
			                        'city'          => "Halifax",
			                        'province'      => "Nova Scotia" ],
			                      [ 'id'            => 2,
			                        'firstname'     => "Jimmy",
			                        'lastname'      => "TheKid",
			                        'address'       => "1 Topstreet Row",
			                        'address_2'     => "Department of Long Addresses, Excessive Address Building, 3rd Floor",
			                        'phone'         => 9027187019,
			                        'email'         => "best_email@gmail.com",
			                        'building_type' => "Office/Other",
			                        'note'          => "Take the elevator to the third floor then scream for assistance",
			                        'postal_code'   => "B2G5T9",
			                        'delivery_time' => null,
			                        'city'          => "Halifax",
			                        'province'      => "Nova Scotia" ] ] ];
		private $cart_template = [ 'id'       => null,
		                           'uid'      => null,
		                           'accepted' => false,
		                           'accepted_enquiries' => 0,
		                           'Order'    => [ ],
		                           'User'     => [
			                           'firstname'      => null,
			                           'lastname'       => null,
			                           'email'          => null,
			                           "email_verified" => false,
			                           "Address"        => [ ]
		                           ],
		                           'Invoice'  => [ 'subtotal'     => 0,
		                                           'total'        => 0,
		                                           'item_count'   => 0,
		                                           'receipt_rows' => 0,
		                                           'hst'          => 0 ],
		                           'Service'  => [ 'deliverable'         => true,
		                                           'order_method'        => JUST_BROWSING,
		                                           'pay_method'          => CASH,
		                                           'address'             => [ 'id'            => null,
		                                                                      'firstname'     => null,
		                                                                      'lastname'      => null,
		                                                                      'address'       => null,
		                                                                      'address_2'     => null,
		                                                                      'phone'         => null,
		                                                                      'email'         => null,
		                                                                      'building_type' => null,
		                                                                      'note'          => null,
		                                                                      'postal_code'   => null,
		                                                                      'delivery_time' => null,
		                                                                      'city'          => 'Halifax',
		                                                                      'province'      => 'NS' ],
		                                           "address_valid"       => null,
		                                           "address_set"         => false,
		                                           "unsaved"             => null,
		                                           "paid"                => false,
		                                           "payment_information" => [ ]
		                           ],

		];

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

					return $this->redirect( [ 'action' => 'index' ] );
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
		 * init_cart() called before all cart-related functions; ensures any missing required keys exist
		 *
		 * @param boolean $destroy
		 */
		public function init_cart( $destroy = false ) {
			if ( $destroy ) $this->Session->destroy();
			if ( !$this->Session->check( 'Cart' ) ) $this->Session->write( 'Cart', $this->cart_template );
			foreach ( $this->cart_template as $key => $value ) {
				if ( !$this->Session->check( "Cart.$key" ) ) {
					$this->Session->write( "Cart.$key", $value );
				}
				if ( is_array( $value ) ) {
					foreach ( $value as $subkey => $subvalue ) {
						if ( !$this->Session->check( "Cart.$key.$subkey" ) ) {
							$this->Session->write( "Cart.$key.$subkey", $subvalue );
						}
					}
				}
			}
			if ( $this->Session->read( 'Cart.uid' ) === null ) $this->Session->write( 'Cart.uid', String::uuid() );

			if ( $this->request->url === "!" ) $this->redirect( ___cakeUrl( "orbcats", "menu" ) );
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
				$response     = [ "success" => true,
				                  "error"   => false,
				                  "data"    => [
					                  "submitted_data" => $this->request->data,
					                  "Cart"           => null ]
				];

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

				$response[ 'data' ][ 'Cart' ] = $this->Session->read( 'Cart' );
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
			if ( $this->request->is( 'ajax' ) ) {
				$this->Cart->clear();
				$this->init_cart();
				$this->render_ajax_response( [ 'success'        => true,
				                               'error'          => false,
				                               'delegate_route' => "menu/unstash",
				                               'data'           => false ] );
			} else {
				return $this->redirect( cakeUrl( array( "controller" => 'menu', "action" => null ) ) );
			}
		}

		public function edit_cart( $orb_uid, $opt_id = null ) {
			if ( $this->is_ajax_post() ) {
				$response = [ 'success' => true,
				              'error'   => false,
				              'data'    => [
					              'submitted_data' => compact( $orb_uid, $opt_id ),
					              'cart'           => null
				              ] ];
				if ( !$this->Cart->edit( $orb_uid, $opt_id ) ) {
					$response[ 'success' ] = false;
					$response[ 'error' ]   = "UID not found in order.";
				}
				$response[ 'data' ][ 'cart' ] = $this->Session->read( 'Cart' );

				return $this->render_ajax_response( $response );
			} else {
				$this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}


		public function finalize() {
			if ( $this->is_ajax_post() ) {
				$response       = [ 'success' => true,
				                    'error'   => false,
				                    'data'    => [
					                    'submitted_data' => $this->request->data,
					                    'cart'           => $this->Session->read( 'Cart' ) ]
				];
				$payment_method = $this->request->data[ 'Order' ][ 'payment_method' ];
				$cart           = $this->Session->read( 'Cart' );
				$this->Order->set( 'uid', $cart[ 'uid' ] );
				$this->Order->set( 'state', ORDER_PENDING );
				$this->Order->set( 'detail', json_encode( $this->Session->read( 'Cart' ) ) );
				$this->Order->set( 'invoice', json_encode( $this->Session->read( 'Cart.Invoice' ) ) );
				switch ( $payment_method ) {
					case PAYPAL:
						$paid = $this->Order->process_paypal_payment();
						break;
					case CREDIT_CARD:
						$paid = $this->Order->process_credit_payment();
						break;
					case DEBIT:
						$paid = false;
						if ( !AppController::debit_available() ) {
							$cart[ 'Service' ][ 'payment_method' ] = null;
							$this->Session->write( 'Cart', $cart );
							$response[ 'sucess' ]         = false;
							$response[ 'error' ]          = "Debit unavailable";
							$response[ 'data' ][ 'cart' ] = $cart;
							$response[ 'delegate_route' ] = "review_order";
							$this->render_ajax_response( $response );
						}
						break;
					default:
						// todo: set some default keys if needed, maybe?
				}
				$cart[ 'Service' ][ 'payment_method' ] = $payment_method;
				$save                                  = true;
				if ( $this->Auth->loggedIn() ) {
					$this->Order->set( 'id', $this->Auth->user[ 'id' ] );
					$save = $this->User->saveAssociated( [ "User" => $this->User, "Order" => $this->Order ] );
				} else {
					$this->Order->set( 'user_id', -1 );
					$save = $this->Order->save();
				}
				if ( !$save ) {
					$response[ 'success' ]        = false;
					$response[ 'error' ]          = $this->Order->getValidationErrors();
					$response[ 'delegate_route' ] = 'fatal_error';

					return $this->render_ajax_response( $response );
				}
				$cart[ 'id' ] = $this->Order->id;
				$this->Session->write( 'Cart', $cart );
				$response[ 'data' ][ 'cart' ] = $cart;
				$response[ 'delegate_route' ] = 'order_confirmation' . DS . $this->Order->id . DS . 'launching';

				return $this->render_ajax_response( $response );

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
				//$this->Session->destroy('Cart');
			} else {
				return $this->redirect( ___cakeUrl( "orbcats", "menu" ) );
			}
		}


		/**
		 * Review cart only (ie. no order details like payment or service)
		 *
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

//		public function address() {
//			$cart = $this->Session->read( 'Cart' );
//			if ( !$cart[ 'Order' ][ 'total' ] ) {
//				return $this->redirect( '/' );
//			}
//
//			if ( $this->Auth->loggedIn() ) {
//				$User = $this->User->find( 'first', array( 'conditions' => array(
//					                                  'User.id' => $this->Session->read( 'Auth.User.User.id' ) ) )
//				); #TODO Investigate why this is two Users deep
//				$this->set( 'User', $User[ 'User' ] );
//			}
//
//			if ( $this->request->is( 'post' ) ) {
//
//				$this->Order->set( $this->request->data );
//				if ( $this->Order->validates() ) {
//					$order                     = $this->request->data[ 'Order' ];
//					$order[ 'payment_method' ] = 'creditcard';
//					$this->Session->write( 'Cart.Order', $order + $cart[ 'Order' ] );
//
//					return $this->redirect( array( 'action' => 'review' ) );
//				} else {
//					$this->Session->setFlash( 'The form could not be saved. Please, try again.', 'flash_error' );
//				}
//			}
//			if ( !empty( $cart[ 'Order' ] ) ) {
//				$this->request->data[ 'Order' ] = $cart[ 'Order' ];
//			}
//		}

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
		 *
		 * @return mixed
		 */
		public function review_order() {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = 'ajax';
				$this->set( "masthead", [ 'header'    => "Review Your Order",
				                          'subheader' => "So close to food you can almost taste it..." ] );
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function read_cart( $uid = null ) {
			$this->init_cart( ( $uid && $this->Session->read( 'Cart.uid' ) != $uid ) );
			$response = [ 'success' => true, 'error' => false, 'data' => $this->Session->read( 'Cart' ) ];

			return $this->render_ajax_response( $response );
		}

		/**
		 * @param null $scope
		 *
		 * @return mixed
		 */
		public function confirm_address( $scope = null ) {
			if ( $this->request->is( 'ajax' ) ) {
				$data = $this->request->data[ 'orderAddress' ];

				// naughty attempts to hack must has fail
				if ( !$this->Auth->loggedIn() ) {
					$data[ 'id' ] = -1;
					$scope        = CONF_ADR_SESSION;
				}

				$response = [ 'success' => true,
				              'error'   => false,
				              'data'    => [ "submitted_data" => $data,
				                             "scope"          => $scope,
				              ] ];

				$adr_on_file = $this->address_on_file( $data[ 'id' ] );

				if ( $scope == CONF_ADR_DB and !$adr_on_file ) {
					$response[ 'success' ] = false;
					$response[ 'error' ]   = "Address with that id not on file or does not belong to User.";
				};
				if ( $scope == CONF_ADR_DB_UPD and $data != $adr_on_file ) {
					if ( !$this->Address->save( $data ) ) {
						$response[ 'success' ] = false;
						$response[ 'error' ]   = $this->User->validationErrors;
					}
					if ( $adr_on_file and $data[ 'id' ] != $adr_on_file[ 'id' ]
					) {
						$data[ 'id' ] = $this->Address->getInsertId();
					}
				}
				$this->Session->write( "Cart.Service.address", $data );
				$this->Session->write( "Cart.Service.address_set", true );
				$this->Session->write( "Cart.Service.address_valid", true );
				$response[ 'data' ][ 'Cart' ] = $this->Session->read( 'Cart' );
				$this->render_ajax_response( $response );
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		/**
		 * @param      $address_id
		 * @param bool $return_address
		 *
		 * @return bool
		 */
		private function address_on_file( $address_id, $return_address = true ) {
			$addresses_on_file = $this->Session->read( 'Cart.User.Address' );
			foreach ( $addresses_on_file as $address ) {
				if ( $address_id == $address[ 'id' ] ) return $return_address ? $address : true;
			}

			return false;
		}

		/**
		 * Sets Cart.Service.order_method session key
		 *
		 * @param $request_context
		 * @param $method
		 */
		public function order_method( $request_context, $method ) {
			if ( $this->is_ajax_post() || true ) {
				$this->init_cart();

				// depending on where this was called in the menu UI, fire different routes on reply
				$delegate_routes = [
					"review" => [ DELIVERY => "review_order", PICKUP => "review_order" ],
					"splash" => [ DELIVERY => "menu", PICKUP => "menu" ],
					"menu"   => [ DELIVERY      => "set_delivery_address" . DS . "menu" . DS . "false",
					              PICKUP        => null,
					              JUST_BROWSING => null ]
				];

				$response = [ "success"        => true,
				              "error"          => false,
				              "delegate_route" => $delegate_routes[ $request_context ][ $method ],
				              "data"           => [ "Cart"            => null,
				                                    "method"          => $method,
				                                    "request_context" => $request_context ]
				];

				$this->Session->write( "Cart.Service.order_method", $method );

				if ( $this->Auth->loggedIn() and $method == DELIVERY ) {
					$options = array( 'conditions' => array( 'User.id' => $this->Auth->user( 'id' ) ) );
					$user    = $this->User->find( 'first', $options );
					if ( $this->Session->read( "Cart.Service.flags.address_valid" ) ) {
						// if the address is already valid assume they wish to edit it, therefore making it invalid until
						// it's been edited and rechecked
						$this->Session->write( "Cart.Service.flags.address_valid", false );
						if ( !in_array( $this->Session->read( 'User.Address' ), $user[ 'Address' ] ) ) {
							$this->Session->write( "Cart.Service.saved", false );
						}
					}
				}
				$response[ 'data' ][ 'Cart' ] = $this->Session->read( "Cart" );

				return $this->render_ajax_response( $response );
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

//		public function success() {
//			$cart = $this->Session->read( 'Cart' );
//			$this->Cart->clear();
//			if ( empty( $cart ) ) {
//				return $this->redirect( '/' );
//			}
//			$this->set( compact( 'cart' ) );
//		}

		public function set_address_form( $restore = null ) {
			if ( $this->request->is( 'ajax' ) && $this->request->is( 'get' ) ) {
				$this->Session->write( "Cart.Service.address_valid", null );
				$this->set( [ "header"    => "Delivery! Yay for sitting!",
				              "subheader" => "But let's confirm your address, yeah?",
				              "restore"   => $restore ? $restore : "menu/unstash"
				            ] );

				return $this->render( 'confirm_address' );
			} else {
				return $this->redirect( ___cakeUrl( "orbcats", "menu" ) );
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
				$this->render_ajax_response( $response );
			} else {
				return $this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
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

		public function user_order_confirmation( $id, $first_call = false ) {
			if ( $this->request->is( 'ajax' ) ) {
				$this->set( 'header', 'Order Confirmation' );
				$this->set( 'subheader', 'Contacting a real person... might take a minute!' );
				$inquiries = $this->Session->read('Cart.Service.accepted_inquiries');
				$this->Session->write('Cart.accepted_inquiries', $inquiries++);
				$order    = $this->Order->findById( $id );
				$response = [ 'success' => true,
				              'error'   => false,
				              'data'    => [
					              'submitted_data' => compact( 'id', 'first_call' ),
					              'status'         => false ]
				];
				if ( empty( $order ) ) {
					$response[ 'success' ] = false;
					$response[ 'error' ]   = "Order not found.";
				} else {
					$response[ 'data' ][ 'status' ] = $order[ 'Order' ][ 'state' ];
				}
				if ($inquiries > 30) $response[ 'data' ][ 'status' ] = "timeout";

				return $first_call ? $this->render( 'get_status', 'ajax' ) : $this->render_ajax_response( $response );
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

		private function process_paypal_payment() {
			// NOT VALID CODE, NOT IMPLEMENTED
			if ( $this->Session->check( 'Cart.Paypal.Details' ) ) {
				$cart                                      = $this->Session->read( 'Cart' );
				$billing_information                       = [ ];
				$billing_information[ 'first_name' ]       = $cart[ 'Paypal' ][ 'Details' ][ 'FIRSTNAME' ];
				$billing_information[ 'last_name' ]        = $cart[ 'Paypal' ][ 'Details' ][ 'LASTNAME' ];
				$billing_information[ 'email' ]            = $cart[ 'Paypal' ][ 'Details' ][ 'EMAIL' ];
				$billing_information[ 'phone' ]            = '888-888-8888';
				$billing_information[ 'billing_address' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTREET' ];
				$billing_information[ 'billing_address2' ] = '';
				$billing_information[ 'billing_city' ]     = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCITY' ];
				$billing_information[ 'billing_zip' ]      = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOZIP' ];
				$billing_information[ 'billing_state' ]    = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTATE' ];
				$billing_information[ 'billing_country' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCOUNTRYNAME' ];

				$billing_information[ 'shipping_address' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTREET' ];
				$billing_information[ 'shipping_address2' ] = '';
				$billing_information[ 'shipping_city' ]     = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCITY' ];
				$billing_information[ 'shipping_zip' ]      = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOZIP' ];
				$billing_information[ 'shipping_state' ]    = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOSTATE' ];
				$billing_information[ 'shipping_country' ]  = $cart[ 'Paypal' ][ 'Details' ][ 'SHIPTOCOUNTRYNAME' ];
				$cart[ 'Order' ][ 'payment_method' ]        = 'paypal';

				$this->Session->write( 'Shop.Order', $cart[ 'Order' ] );
				$paypal = $this->Paypal->ConfirmPayment( $order[ 'Order' ][ 'total' ] );
				$ack    = strtoupper( $paypal[ 'ACK' ] );
				if ( $ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING' ) {
					$order[ 'Order' ][ 'status' ] = ORDER_PAID;
				}
			}
		}

		private function process_credit_payment() {
			// NOT VALID CODE, NOT IMPLEMENTED
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
	}
