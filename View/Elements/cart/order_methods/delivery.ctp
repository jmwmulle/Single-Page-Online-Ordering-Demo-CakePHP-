<?php
/**
 * J. Mulle, for app, 7/15/15 10:07 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$adr = $service['address'];
?>
<div class="row">
	<div class="large-12 columns">
		<a href="#" class="modal-button full-width active hover-switch" data-route="set_order_method/review/pickup">
			<span class="icon-pick-up"></span>
			<span class="text unhover">Order is for Delivery</span>
			<span class="text hover">Switch to Pick-Up</span>
		</a>
	</div>
</div>
<div class="row <?php if ( $service['flags']['address_valid'] ) echo " true-hidden";?>">
	<div class="large-12 columns">
		<a href="#" class="modal-button lrg full-width confirm" data-route="set_order_method/review/delivery">
			<span class="icon-delivery"></span><span class="text">Click to Set Delivery Address</span>
		</a>
	</div>
</div>
<div class="row <?php if ( !$service['flags']['address_valid'] ) echo " true-hidden";?>">
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