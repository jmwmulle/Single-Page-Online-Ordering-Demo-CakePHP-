<?php
App::uses('AppController', 'Controller');
class OrdersController extends AppController {


	public $components = array(
		'Cart',
//		'Security',
		'Paginator',
		#'Paypal',
		#'AuthorizeNet'
	);
	
	public $uses = array('Orb', 'Orbopt');

	/**
	 * index method
	 *
	 * @return void
	 */
		public function index() {
			//$this->Order->recursive = 0;
			$this->set('orders', $this->Paginator->paginate());
		}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
		public function view($id = null) {
			if (!$this->Order->exists($id)) {
				throw new NotFoundException(__('Invalid order'));
			}
			$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
			$this->set('order', $this->Order->find('first', $options));
		}

	/**
	 * add method
	 *
	 * @return void
	 */
		public function add() {
			if ($this->request->is('post')) {
				$this->Order->create();
				if ($this->Order->save($this->request->data)) {
					$this->Session->setFlash(__('The order has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
				}
			}
			$users = $this->Order->User->find('list');
			$orbs = $this->Order->Orb->find('list');
			$this->set(compact('users', 'orbs'));
		}
	/**
	 * add_to_cart method
	 *
	 * Adds a new item to the cart session key
	 *
	 * @return the item information in AJAX
	 */ 
		public function add_to_cart() {
			if ($this->request->is('ajax') || true) {
				$this->layout = "ajax";

			} elseif ($this->request->is('get')) {
				$this->render();
				return;
			}
			$products = array();

			foreach ($this->request->data['Order'] as $orb) {
				extract(array_merge(array(
							"id" => -1,
							"quantity" => -1,
							"price_rank" => 0,
							"orbopts" => array(),
							"preparation_instructions" => ""),
							$orb));
				$cart = $this->Cart->add($id, $quantity, $price_rank, $orbopts, $preparation_instructions);

				array_push($products, $cart);
			}
			if (!empty($products)) {
				if ($this->Session->check('Cart.Order.total') ) {
					$total = $this->Session->read('Cart.Order.total');
				} else {
					$total = null;
				}
				$this->set("response", json_encode(array("Order" => array("Orbs" => $products), "success" => true, "cart_total" => $total)));
			} else {
				$this->set("response", json_encode(array("orb" => null, "success" => false, "cart_total" => null)));
			}
		}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
		public function edit($id = null) {
			if (!$this->Order->exists($id)) {
				throw new NotFoundException(__('Invalid order'));
			}
			if ($this->request->is(array('post', 'put'))) {
				if ($this->Order->save($this->request->data)) {
					$this->Session->setFlash(__('The order has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The order could not be saved. Please, try again.'));
				}
			} else {
				$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
				$this->request->data = $this->Order->find('first', $options);
			}
			$users = $this->Order->User->find('list');
			$orbs = $this->Order->Orb->find('list');
			$this->set(compact('users', 'orbs'));
		}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
		public function delete($id = null) {
			$this->Order->id = $id;
			if (!$this->Order->exists()) {
				throw new NotFoundException(__('Invalid order'));
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->Order->delete()) {
				$this->Session->setFlash(__('The order has been deleted.'));
			} else {
				$this->Session->setFlash(__('The order could not be deleted. Please, try again.'));
			}
			return $this->redirect(array('action' => 'index'));
		}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->disableCache();
		$this->set('loggedIn', $this->Auth->loggedIn());
		$this->set('user', $this->Auth->user());
	}


	public function clear() {
		$this->Cart->clear();
		$this->Session->setFlash('All item(s) removed from your shopping cart', 'flash_error');
		return $this->redirect('/');
	}


	public function itemupdate() {
		if ($this->request->is('ajax')) {

			foreach ($this->request->data['Order']['Orbs'] as $orb) {
				$id = isset($orb['id']) ? $orb['id'] : null;
				$quantity = isset($orb['quantity']) ? $orb['quantity'] : null;
				$price_rank = isset($orb['price_rank']) ? $orb['price_rank'] : null;
				$orbopts = isset($orb['Orbopts']) ? $orb['Orbopts'] : null;
				$prep_instructions = isset($orb['prep_instructions']) ? $orb['prep_instructions'] : null;
				array_push($products,$this->Cart->add($id, $quantity, $price_rank, $orbopts, $prep_instructions));
			}
		$cart = $this->Session->read('Cart');
		echo json_encode($cart);
		$this->autoRender = false;
		} else {
			return $this->redirect(cakeUrl(array("controller" =>'menu', "action" => null)));
		}
	}


	public function update() {
		$this->Cart->update($this->request->data['Orb']['id'], 1);
	}


	public function remove($id = null) {
		$product = $this->Cart->remove($id);
		if(!empty($product)) {
			$this->Session->setFlash($product['Orb']['title'] . ' was removed from your shopping cart', 'flash_error');
		}
		return $this->redirect(array('action' => 'cart'));
	}


	public function cartupdate() {
		if ($this->request->is('post')) {
			foreach($this->request->data['Orb'] as $key => $value) {
				$p = explode('-', $key);
				$p = explode('_', $p[1]);
				db($p);
				$this->Cart->add($p[0], $value, $p[1]);
			}
			$this->Session->setFlash('Shopping Cart is updated.', 'flash_success');
		}
		return $this->redirect(array('action' => 'cart'));
	}


	public function cart() {
		$cart = $this->Session->read('Cart');
		$this->set(compact('cart'));
	}


	public function address() {
		$cart = $this->Session->read('Cart');
		if(!$cart['Order']['total']) {
			return $this->redirect('/');
		}

		if ($this->request->is('post')) {
			
			$this->Order->set($this->request->data);
			if($this->Order->validates()) {
				$order = $this->request->data['Order'];
				$order['order_type'] = 'creditcard';
				$this->Session->write('Shop.Order', $order + $cart['Order']);
				return $this->redirect(array('action' => 'review'));
			} else {
				$this->Session->setFlash('The form could not be saved. Please, try again.', 'flash_error');
			}
		}
		if(!empty($cart['Order'])) {
			$this->request->data['Order'] = $cart['Order'];
		}

	}


	public function step1() {
		$paymentAmount = $this->Session->read('Shop.Order.total');
		if(!$paymentAmount) {
			return $this->redirect('/');
		}
		$this->Session->write('Shop.Order.order_type', 'paypal');
		$this->Paypal->step1($paymentAmount);
	}


	public function step2() {

		$token = $this->request->query['token'];
		$paypal = $this->Paypal->GetShippingDetails($token);

		$ack = strtoupper($paypal['ACK']);
		if($ack == 'SUCCESS' || $ack == 'SUCESSWITHWARNING') {
			$this->Session->write('Shop.Paypal.Details', $paypal);
			return $this->redirect(array('action' => 'review'));
		} else {
			$ErrorCode = urldecode($paypal['L_ERRORCODE0']);
			$ErrorShortMsg = urldecode($paypal['L_SHORTMESSAGE0']);
			$ErrorLongMsg = urldecode($paypal['L_LONGMESSAGE0']);
			$ErrorSeverityCode = urldecode($paypal['L_SEVERITYCODE0']);
			echo 'GetExpressCheckoutDetails API call failed. ';
			echo 'Detailed Error Message: ' . $ErrorLongMsg;
			echo 'Short Error Message: ' . $ErrorShortMsg;
			echo 'Error Code: ' . $ErrorCode;
			echo 'Error Severity Code: ' . $ErrorSeverityCode;
			die();
		}

	}


	public function review() {

		$cart = $this->Session->read('Cart');

		if(empty($cart)) {
			return $this->redirect('/');
		}

		if ($this->request->is('post')) {
			$this->Order->set($this->request->data);
			if($this->Order->validates()) {
				$order = $cart;
				$order['Order']['status'] = 1;

				if($cart['Order']['order_type'] == 'paypal') {
					$paypal = $this->Paypal->ConfirmPayment($order['Order']['total']);
					//debug($resArray);
					$ack = strtoupper($paypal['ACK']);
					if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') {
						$order['Order']['status'] = 2;
					}
					$order['Order']['authorization'] = $paypal['ACK'];
					//$order['Order']['transaction'] = $paypal['PAYMENTINFO_0_TRANSACTIONID'];
				}

				if((Configure::read('Settings.AUTHORIZENET_ENABLED') == 1) && $cart['Order']['order_type'] == 'creditcard') {
					$payment = array(
						'creditcard_number' => $this->request->data['Order']['creditcard_number'],
						'creditcard_month' => $this->request->data['Order']['creditcard_month'],
						'creditcard_year' => $this->request->data['Order']['creditcard_year'],
						'creditcard_code' => $this->request->data['Order']['creditcard_code'],
					);
					try {
						$authorizeNet = $this->AuthorizeNet->charge($cart['Order'], $payment);
					} catch(Exception $e) {
						$this->Session->setFlash($e->getMessage());
						return $this->redirect(array('action' => 'review'));
					}
					$order['Order']['authorization'] = $authorizeNet[4];
					$order['Order']['transaction'] = $authorizeNet[6];
				}

				$save = $this->Order->saveAll($order, array('validate' => 'first'));
				if($save) {

					$this->set(compact('cart'));

					App::uses('CakeEmail', 'Network/Email');
					$email = new CakeEmail();
					$email->from(Configure::read('Settings.ADMIN_EMAIL'))
							->cc(Configure::read('Settings.ADMIN_EMAIL'))
							->to($cart['Order']['email'])
							->subject('Shop Order')
							->template('order')
							->emailFormat('text')
							->viewVars(array('shop' => $cart))
							->send();
					return $this->redirect(array('action' => 'success'));
				} else {
					$errors = $this->Order->invalidFields();
					$this->set(compact('errors'));
				}
			}
		}

		if(($cart['Order']['order_type'] == 'paypal') && !empty($cart['Paypal']['Details'])) {
			$cart['Order']['first_name'] = $cart['Paypal']['Details']['FIRSTNAME'];
			$cart['Order']['last_name'] = $cart['Paypal']['Details']['LASTNAME'];
			$cart['Order']['email'] = $cart['Paypal']['Details']['EMAIL'];
			$cart['Order']['phone'] = '888-888-8888';
			$cart['Order']['billing_address'] = $cart['Paypal']['Details']['SHIPTOSTREET'];
			$cart['Order']['billing_address2'] = '';
			$cart['Order']['billing_city'] = $cart['Paypal']['Details']['SHIPTOCITY'];
			$cart['Order']['billing_zip'] = $cart['Paypal']['Details']['SHIPTOZIP'];
			$cart['Order']['billing_state'] = $cart['Paypal']['Details']['SHIPTOSTATE'];
			$cart['Order']['billing_country'] = $cart['Paypal']['Details']['SHIPTOCOUNTRYNAME'];

			$cart['Order']['shipping_address'] = $cart['Paypal']['Details']['SHIPTOSTREET'];
			$cart['Order']['shipping_address2'] = '';
			$cart['Order']['shipping_city'] = $cart['Paypal']['Details']['SHIPTOCITY'];
			$cart['Order']['shipping_zip'] = $cart['Paypal']['Details']['SHIPTOZIP'];
			$cart['Order']['shipping_state'] = $cart['Paypal']['Details']['SHIPTOSTATE'];
			$cart['Order']['shipping_country'] = $cart['Paypal']['Details']['SHIPTOCOUNTRYNAME'];

			$cart['Order']['order_type'] = 'paypal';

			$this->Session->write('Shop.Order', $cart['Order']);
		}

		$this->set(compact('cart'));

	}


	public function success() {
		$cart = $this->Session->read('Cart');
		$this->Cart->clear();
		if(empty($cart)) {
			return $this->redirect('/');
		}
		$this->set(compact('cart'));
	}


}
