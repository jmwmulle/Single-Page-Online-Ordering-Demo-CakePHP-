<?php
/**
 * J. Mulle, for app, 7/15/15 10:07 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$adr = $service['address'];
	$delivery_address_classes = ["modal-button", "bisecting", "discreet", "confirm"];
	$method_switch_classes = ["modal-button", "active", "bisecting", "discreet", "hover-switch"];
	$address_button_str = $service['address_valid'] ? "Change" : "Click to Set"
?>
<div class="row">
	<div class="large-12 columns">
		<a href="#" <?=___cD($delivery_address_classes);?> data-route="set_delivery_address/review/review">
			<span class="icon-delivery"></span><span class="text"><?=$address_button_str;?> Delivery Address</span>
		</a>
		<a href="#" <?=___cD($method_switch_classes);?> data-route="set_order_method/review/pickup">
			<span class="icon-pick-up"></span>
			<span class="text unhover">Order is for Delivery</span>
			<span class="text hover">Switch to Pick-Up</span>
		</a>
	</div>
</div>
<div class="row <?php if ( !$service['address_valid'] ) echo " true-hidden";?>">
	<div class="large-12 large-centered columns">
		<div class="row">
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns"><h5 class="panel-title">Your Contact Info</h5></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<span class="information"><strong>Name:<strong> <?=sprintf( "%s %s", $adr[ 'firstname' ], $adr[ 'lastname' ] ); ?></span>
						<span class="information"><strong>Email:<strong> <?=$adr['email'];?></span>
						<span class="information"><strong>Phone:<strong> <?=$adr[ 'phone' ];?></span>
					</div>
				</div>
			</div>
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns"><h5 class="panel-title">Your Address</h5></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<span class="information"><?=$adr[ "address" ];?></span>
						<?php if ( !empty( $adr[ "address_2" ] ) ) echo sprintf("<span class='information'>%s</span>", $adr[ "address_2" ]);?>
						<span class="information"><?=$adr[ 'postal_code' ];?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>