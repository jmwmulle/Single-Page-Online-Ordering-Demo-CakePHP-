<?php
/**
 * J. Mulle, for app, 6/17/15 8:42 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

$order_method = array_key_exists('order_method', $order) ? $order['order_method']: false;
$delivery_class = $order_method == "delivery" ? "active" : "inactive";
if (!$system[3]['Sysvar']['status']) $delivery_class = "disabled";
?>
<ul id="user-activity-panel" class="activizing text-center">
	<li><h2 class="body-font-color">I AM</h2></li>
	<li class="<?=!$order_method ? "active" : "inactive";?> default"
		><a class="body-font-color block" data-route="set_order_method/menu/just_browsing">Just<br />Browsing</a
			></li>
	<li <?=___cD($delivery_class);?>
		><a class="body-font-color block modal-link overlay <?=$system[3]['Sysvar']['status'] ? "" : " disabled";?>" data-route="set_order_method/menu/delivery">Ordering<br />(Delivery)</a
	></li>
	<li class="<?=$order_method == "pickup" ? "active" : "inactive"; ?>">
		<a class="body-font-color block modal-link overlay" data-route="set_order_method/menu/pickup">Ordering<br />(Pick-Up)</a>
	</li>
</ul>