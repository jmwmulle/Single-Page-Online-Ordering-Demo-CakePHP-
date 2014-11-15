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

	public function add($id, $quantity = 1, $productmodId = null) {
		/*if($productmodId) {
			$productmod = ClassRegistry::init('Orbextra')->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'Orbmod.id' => $productmodId,
					'Orbmod.product_id' => $id,
				)
			));
	        }*/

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

		if($this->Session->check('Shop.OrderItem.' . $id . '.Orb.productmod_name')) {
			$productmod['Orbextra']['id'] = $this->Session->read('Shop.OrderItem.' . $id . '.Orb.productmod_id');
			$productmod['Orbextra']['title'] = $this->Session->read('Shop.OrderItem.' . $id . '.Orb.productmod_name');
			$productmod['Orbextra']['price_matrix'] = $this->Session->read('Shop.OrderItem.' . $id . '.Orb.price');

		}

		if(isset($productmod)) {
			$product['Orb']['productmod_id'] = $productmod['Orbextra']['id'];
			$product['Orb']['productmod_name'] = $productmod['Orbextra']['title'];
			$product['Orb']['price_matrix'] = $productmod['Orbextra']['price_matrix'];
			$productmodId = $productmod['Orbextra']['id'];
			$data['productmod_id'] = $product['Orb']['productmod_id'];
			$data['productmod_name'] = $product['Orb']['productmod_name'];
		} else {
			$product['Orb']['productmod_id'] = '';
			$product['Orb']['productmod_name'] = '';
			$productmodId = 0;
			$data['productmod_id'] = '';
			$data['productmod_name'] = '';
		}

		$prices = json_decode($product['Orb']['price_matrix'],true);

		$data['product_id'] = $product['Orb']['id'];
		$data['title'] = $product['Orb']['title'];
		$data['price'] = $prices['9in'];
		$data['quantity'] = $quantity;
		$data['subtotal'] = sprintf('%01.2f', $prices['9in'] * $quantity);
		$data['Orb'] = $product['Orb'];
		$this->Session->write('Shop.OrderItem.' . $id . '_' . $productmodId, $data);
		$this->Session->write('Shop.Order.shop', 1);

		$this->Cart = ClassRegistry::init('Cart');

		$cartdata['Cart']['sessionid'] = $this->Session->id();
		$cartdata['Cart']['quantity'] = $quantity;
		$cartdata['Cart']['product_id'] = $product['Orb']['id'];
		$cartdata['Cart']['title'] = $product['Orb']['title'];
		$cartdata['Cart']['price'] = $prices['9in'];
		$cartdata['Cart']['subtotal'] = sprintf('%01.2f', $prices['9in'] * $quantity);

		$existing = $this->Cart->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'Cart.sessionid' => $this->Session->id(),
				'Cart.product_id' => $product['Orb']['id'],
			)
		));
		if($existing) {
			$cartdata['Cart']['id'] = $existing['Cart']['id'];
		} else {
			$this->Cart->create();
		}
		$this->Cart->save($cartdata, false);

		$this->cart();

		return $product;
	}

//////////////////////////////////////////////////

	public function remove($id) {
		if($this->Session->check('Shop.OrderItem.' . $id)) {
			$product = $this->Session->read('Shop.OrderItem.' . $id);
			$this->Session->delete('Shop.OrderItem.' . $id);

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
		$shop = $this->Session->read('Shop');
		$quantity = 0;
		$subtotal = 0;
		$total = 0;
		$order_item_count = 0;

		if (count($shop['OrderItem']) > 0) {
			foreach ($shop['OrderItem'] as $item) {
				$quantity += $item['quantity'];
				$subtotal += $item['subtotal'];
				$total += $item['subtotal'];
				$order_item_count++;
			}
			$d['order_item_count'] = $order_item_count;
			$d['quantity'] = $quantity;
			$d['subtotal'] = sprintf('%01.2f', $subtotal);
			$d['total'] = sprintf('%01.2f', $total);
			$this->Session->write('Shop.Order', $d + $shop['Order']);
			return true;
		}
		else {
			$d['quantity'] = 0;
			$d['subtotal'] = 0;
			$d['total'] = 0;
			$this->Session->write('Shop.Order', $d + $shop['Order']);
			return false;
		}
	}

//////////////////////////////////////////////////

	public function clear() {
		ClassRegistry::init('Cart')->deleteAll(array('Cart.sessionid' => $this->Session->id()), false);
		$this->Session->delete('Shop');
	}

//////////////////////////////////////////////////

}
