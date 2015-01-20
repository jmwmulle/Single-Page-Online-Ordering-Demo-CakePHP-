<?php
/**
 * J. Mulle, for app, 1/19/15 9:20 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 *
 * */
$response = null;
$f_orders = array();
try {
	foreach ($orders as $order) {
		$detail = json_decode($order['Order']['detail'], true);
	pr($detail);
		$address = $detail['Order']['address'];
		if (!array_key_exists('firstname', $address) ) $address['firstname'] = 'Anon';
		if (!array_key_exists('lastname', $address) ) $address['lastname'] = 'Anon';
		$f_order = array(
			'id' => $order['Order']['id'],
			'title' => $address['address'],
		    'customer' => sprintf("%s %s", $address['firstname'], $address['lastname']),
		    'food' => array()
		);
		$food_array = array();
		foreach ($detail['OrderItem'] as $orb) {
			$food_array[$orb['title']] = $orb['orbopts'];
	    }

		$f_order['food'][$orb['title']] = $food_array;
		$f_orders[] = $f_order;
	}
	$response = array('success' => true, 'error' => false, 'orders' => $f_orders);
} catch (Exception $e) {
	$response = array('success' => false, 'error' => json_encode($e), 'orders' => false);
}

echo json_encode($response);
?>
