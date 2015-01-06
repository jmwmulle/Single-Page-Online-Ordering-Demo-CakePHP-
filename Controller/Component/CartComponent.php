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
	}

//////////////////////////////////////////////////

	public $maxQuantity = 99;

//////////////////////////////////////////////////

	public function add($orb_id, $quantity = 1, $price_rank = 0, $orbopts_list = null, $preparation_instructions = '') {

		$position_to_price = array('F'=>1, 'L'=>0.5, 'R'=>0.5, 'D'=>2);

		if(!is_numeric($quantity)) {
			$quantity = 1;
		}

		$quantity = abs($quantity);

		if($quantity > $this->maxQuantity) {
			$quantity = $this->maxQuantity;
		}

		$orb = $this->controller->Orb->find('first', array(
			'conditions' => array(
				'Orb.id' => $orb_id
			)
		));
		
		if(empty($orb)) {
			return Null;
		}

		if(($key = array_search(-1, $orbopts_list)) !== false) {
			unset($orbopts_list[$key]);
		}

		$orbopts = array();
		if($orbopts_list) {
			$this->controller->Orbopt->Behaviors->load('Containable');
			foreach (array_keys($orbopts_list) as $orbopt_id) {
				if ($orbopts_list[$orbopt_id] != -1) {
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
		if(!empty($orbopts)) {
			foreach($orbopts as $key=>$orbopt) {
				$opts_by_val = array_values($orbopt['Pricelist']);
				array_push($opts_prices, $opts_by_val[$price_rank]*$position_to_price[$orbopts_list[$key]]);
			}
		}
		for ($i=$orb['Orb']['orbopts_count']; $i>0; $i--) {
			$mins = array_keys($array, max($array));
			$opts_prices[$mins[0]] = 0;
		}

		$prices =  array_values($orb['Pricelist']);
		$size_names = array_values($orb['Pricedict']);

		$matched = False;
		$current_data = array();
		$this->Session->write('Test', 'Hello');
		if ($this->Session->check('Cart.OrderItem')) {
			$current_data = $this->Session->read('Cart.OrderItem');
			foreach ($current_data as $key => $item) {
				$cur_item = compact("price_rank","orb_id","preparation_instructions");
				$cur_item['orbopts_arrangement'] = $orbopts_list;
				if (array_intersect_key($item, $cur_item) == $cur_item) { #Consider making this a hash table for speed
					if ($item['quantity'] + $quantity <= 0) {
						$this->Session->delete('Cart.OrderItem.' . $key);
					} else {
						$item['quantity']+=$quantity;
						if ($item['quantity'] > $this->maxQuantity) {
							$this['quantity'] = $this->maxQuantity;
						}	
						$this->Session->write('Cart.OrderItem.' . $key . '.quantity', $item['quantity']);
						$this->Session->write('Cart.OrderItem.' . $key . '.subtotal', 
							sprintf('%01.2f', ($item['base_price'] + array_sum($item['orbopts_prices'])) * $item['quantity']));
						$matched = True;
						return $item;
					}
				}
			}
		}
		if(!$matched && $quantity > 0) {
			$item['orb_id'] = $orb['Orb']['id'];
			$item['title'] = $orb['Orb']['title'];
			$item['description'] = $orb['Orb']['description'];
			$item['price_rank'] = $price_rank;
			$item['base_price'] = $prices[$price_rank+1]; #id is index 0, 1-6 are prices/sizes
			$item['size_name'] = $size_names[$price_rank+1];

			$item['orbopts'] = $orbopts;
			$item['orbopts_prices'] = empty($opts_prices) ? array() : $opts_prices;
			$item['orbopts_arrangement'] = $orbopts_list;

			$item['preparation_instructions'] = $preparation_instructions;
			$item['quantity'] = $quantity;
			$item['subtotal'] = sprintf('%01.2f', ($item['base_price'] + array_sum($item['orbopts_prices'])) * $quantity);
			
			$current_data[] = $item;
			
			$this->Session->write('Cart.OrderItem', $current_data);
			return $item;
		} else {
			return false;
		}
	}

//////////////////////////////////////////////////

	public function remove($id) {
		if($this->Session->check('Cart.OrderItem.' . $id)) {
			$order_item = $this->Session->read('Cart.OrderItem.' . $id);
			$this->Session->delete('Cart.OrderItem.' . $id);

			return $order_item;
		}
		return false;
	}

//////////////////////////////////////////////////

	public function update() {
		$cart = $this->Session->read('Cart');
		$quantity = 0;
		$subtotal = 0;
		$total = 0;
		$order_item_count = 0;
		$HST_MULT = 0.15;
		$HST = 0;
		$delivery = 3.00;
		$deliverable = false;

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
			$d['deliverable'] = $total >= 10.0;
			$this->Session->write('Cart.Order', $d);
			return true;
		}
		else {
			$d['quantity'] = 0;
			$d['subtotal'] = 0;
			$d['total'] = 0;
			$d['deliverable'] = false;
			$this->Session->write('Cart.Order', $d);
			return false;
		}
	}

//////////////////////////////////////////////////

	public function clear() {
		$this->Session->delete('Cart');
	}

//////////////////////////////////////////////////

	#public function beforeFilter() {
#		parent::beforeFilter();
#		$this->Auth->allow();
#	}

}
