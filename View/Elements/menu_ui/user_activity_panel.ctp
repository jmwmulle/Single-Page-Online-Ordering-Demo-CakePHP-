<?php
/**
 * J. Mulle, for app, 6/17/15 8:42 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

$order_method = array_key_exists('order_method', $order) ? $order['order_method']: false;
$pickup_class = [$order_method == PICKUP ? "active" : "inactive"];
$pickup_route_class = ["body-font-color", "block", "modal-link", "overlay"];
$delivery_class = [$order_method == DELIVERY ? "active" : "inactive"];
$delivery_route_class = ["body-font-color", "block", "modal-link", "overlay"];
if (!$system[DELIVERY_AVAILABLE - 1]['Sysvar']['status']) {
	array_push($delivery_class, "disabled");
	array_push($delivery_route_class, "disabled");
}
if (!$system[ONLINE_ORDERING - 1]['Sysvar']['status']) {
	array_push($pickup_class, "disabled");
	array_push($pickup_route_class, "disabled");
	if ( !in_array("disabled", $delivery_class) ) array_push($delivery_class, "disabled");
	if ( !in_array("disabled", $delivery_route_class) ) array_push($delivery_route_class, "disabled");

}
?>
<ul id="user-activity-panel" class="activizing text-center">
	<li><h2 class="body-font-color">I AM</h2></li>
	<li class="<?=!$order_method ? "inactive": "active";?> default"
		><a class="body-font-color block" data-route="set_order_method/menu/just_browsing">Just<br />Browsing</a
			></li>
	<li <?=___cD($delivery_class);?>
		><a <?=___cD($delivery_route_class);?> data-route="set_order_method/menu/delivery">Ordering<br />(Delivery)</a
	></li>
	<li <?=___cD($pickup_class);?>
		><a <?=___cD($pickup_route_class);?> data-route="set_order_method/menu/pickup">Ordering<br />(Pick-Up)</a>
	</li>
</ul>