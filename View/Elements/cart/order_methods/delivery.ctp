<?php
/**
 * J. Mulle, for app, 7/15/15 10:07 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<div class="row <?php if ( $address_valid ) echo " true-hidden";?>">
	<div class="large-12 columns">
		<a href="#" class="modal-button lrg full-width confirm" data-route="order_method/review/delivery">
			<span class="icon-delivery"></span><span class="text">Click to Set Delivery Address</span>
		</a>
	</div>
</div>
<div class="row">
	<div class="large-12 columns">
		<a href="#" class="modal-button secondary" data-route="order_method/review/just_browsing">
			<span class="icon-tab-arrow-l"></span><span class="text">Change From Delivery</span>
		</a>
	</div>
</div>
<div class="row <?php if ( !$address_valid ) echo " true-hidden";?>">
	<div class="large-8 large-centered columns">
		<div class="row">
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns"><h4 class="panel-title">Customer Information</h4></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						Name: <?php echo sprintf( "%s %s", $address[ 'firstname' ], $address[ 'lastname' ] ); ?>
					</div>
				</div>
				<?php if ( $email ) { ?>
					<div class="row">
						<div class="large-12 columns">
							Email: <?php echo $email; ?>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="large-12 columns">
						Phone: <?php echo $address[ 'phone' ]; ?>
					</div>
				</div>
			</div>
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns"><h4 class="panel-title">Delivery Address</h4></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<?php echo $address[ "address" ]; ?>
						<?php if ( !empty( $address[ "address_2" ] ) ) {
							echo $address[ "address_2" ];
						} ?>
						<?php echo $address[ 'postal_code' ]; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="large-12 columns">
		<a href="#" class="modal-button secondary" data-route="order_method/review/just_browsing">
			<span class="icon-tab-arrow-l"></span><span class="text">Change From Delivery</span>
		</a>
	</div>
</div>