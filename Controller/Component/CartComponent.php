<?php
class CartComponent extends Component {

//////////////////////////////////////////////////

	public $components = array('Session');

//////////////////////////////////////////////////

	public $controller;

//////////////////////////////////////////////////

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->controller = $collection->getController();
		parent::__construct($collection, array_merge($this->settings, (array)$settings));
	}

//////////////////////////////////////////////////

	public function startup(Controller $controller) {
		#$this->controller = $controller;
	}

//////////////////////////////////////////////////

	public $maxQuantity = 99;

//////////////////////////////////////////////////

	public function add($id, $quantity = 1, $price_rank = 0, $orbopts_list = null, $prep_instructions = '') {
		if(!is_numeric($quantity)) {
			$quantity = 1;
		}

		$quantity = abs($quantity);

		if($quantity > $this->maxQuantity) {
			$quantity = $this->maxQuantity;
		}

		if($quantity == 0) {
			$this->remove($id);
			return;
		}
		$product = $this->controller->Orb->find('first', array(
			'conditions' => array(
				'Orb.id' => $id
			)
		));

		if(empty($product)) {
			return false;
		}

		if($orbopts_list) {
			$this->controller->Orbopt->Behaviors->load('Containable');
			foreach (array_keys($orbopts_list) as $orbopt_id) {
				if ($orbopts_list[$orbopts_id] != -1) {
					$orbopts[$orbopt_id] = $this->controller->Orbopt->find('first', array(
						'recursive' => 1,
						'conditions' => array(
							'Orbopt.id' => $orbopt_id,
						),
						'contain' => array(
							'Pricelist',
						),
					));
				}
			}
		}

		$opts_prices = array();
		if($orbopts) {
			foreach($orbopts as $orbopt) {
				$opts_by_val = array_values($orbopt['Pricelist']);
				array_push($opts_prices, $opts_by_val[$price_rank]);
			}
		}

		$prices = array_values($product['Pricelist']);

		$data['product_id'] = $product['Orb']['id'];
		$data['orbopts_ids'] = $orbopts_list;
		$data['title'] = $product['Orb']['title'];
		$data['price'] = $prices[$price_rank];
		$data['orbopts'] = $orbopts;
		$data['orbopts_prices'] = $opts_prices;
		$data['orbopts_arrangement'] = $orbopts_list;
		$data['prep_instructions'] = $prep_instructions;
		$data['quantity'] = $quantity;
		$data['subtotal'] = sprintf('%01.2f', ($data['price'] + array_sum($data['orbopts_prices'])) * $quantity);
		$data['price_rank'] = $price_rank;
		$data['Orb'] = $product['Orb'];
		$this->Session->write('Cart.OrderItem.' . $id, $data);
		$this->Session->write('Cart.Order.shop', 1);

		$this->Cart = ClassRegistry::init('Cart');
		$cartdata['Cart']['sessionid'] = $this->Session->id();
		$cartdata['Cart']['quantity'] = $quantity;
		$cartdata['Cart']['product_id'] = $product['Orb']['id'];
		$cartdata['Cart']['title'] = $product['Orb']['title'];
		$cartdata['Cart']['price'] = $prices[$price_rank];
		$cartdata['Cart']['price_rank'] = $price_rank;
		$cartdata['Cart']['subtotal'] = sprintf('%01.2f', ($data['subtotal']));
		$existing = $this->Cart->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'Cart.sessionid' => $this->Session->id(),
				'Cart.product_id' => $product['Orb']['id'],
				'Cart.price_rank' => $price_rank,
			)
		));
		if($existing) {
			$cartdata['Cart']['id'] = $existing['Cart']['id'];
		} else {
			$this->Cart->create();
		}
		if ($this->Cart->save($cartdata, false)) {
			$this->cart();
			return $product;
		} else {
			db('Failed to save');
		}
	}

//////////////////////////////////////////////////

	public function remove($id) {
		if($this->Session->check('Cart.OrderItem.' . $id)) {
			$product = $this->Session->read('Cart.OrderItem.' . $id);
			$this->Session->delete('Cart.OrderItem.' . $id);

			ClassRegistry::init('Cart')->deleteAll(
				array(
					'Cart.sessionid' => $this->Session->id(),
					'Cart.product_id' => $id,
				),
				false
			);

			$this->cart();
			return $product;
		}
		return false;
	}

//////////////////////////////////////////////////

	public function cart() {
		$cart = $this->Session->read('Cart');
		$quantity = 0;
		$subtotal = 0;
		$total = 0;
		$order_item_count = 0;
		$HST_MULT = 0.15;
		$HST = 0;
		$delivery = 3.00;

		if (count($cart['OrderItem']) > 0) {
			foreach ($cart['OrderItem'] as $item) {
				$quantity += $item['quantity'];
				$subtotal += $item['subtotal'];
				$HST += $item['subtotal']*$HST_MULT;
				$order_item_count++;
			}
			$total = $subtotal+$HST+$delivery;
			$d['order_item_count'] = $order_item_count;
			$d['quantity'] = $quantity;
			$d['subtotal'] = sprintf('%01.2f', $subtotal);
			$d['HST'] = sprintf('%01.2f', $HST);
			$d['delivery'] = sprintf('%01.2f', $delivery);
			$d['total'] = sprintf('%01.2f', $total);
			$this->Session->write('Cart.Order', $d + $cart['Order']);
			return true;
		}
		else {
			$d['quantity'] = 0;
			$d['subtotal'] = 0;
			$d['total'] = 0;
			$this->Session->write('Cart.Order', $d + $cart['Order']);
			return false;
		}
	}

//////////////////////////////////////////////////

	public function clear() {
		ClassRegistry::init('Cart')->deleteAll(array('Cart.sessionid' => $this->Session->id()), false);
		$this->Session->delete('Cart');
	}

//////////////////////////////////////////////////

}
