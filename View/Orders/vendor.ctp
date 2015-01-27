<?php
/**
 * J. Mulle, for app, 1/6/15 10:02 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$tablet_debug = false;
?>
<div id="order-tone-wrapper" class="true-hidden">
	<audio controls>
	  <source src="files/new_order_tone.mp3" type="audio/mpeg">
	</audio>
</div>
<div id="error-pane">
	<div id="error-text"></div>
</div>
<?php if ($tablet_debug) echo '<div id="tablet-out"> </div>' ;?>
<main id="vendor-page">
	<div id="back-splash" class="true-hidden">
		NOTHING</br>
		IN<br />
		QUEUE
	</div>
	<div id="accept-acknowledge" class="true-hidden">OK!</div>
	<div id="next-order" class="slide-up row">
		<div id="pending-orders-count" class="fade-out">
			<span id="order-count">0</span>
			<h5>IN QUEUE</h5>
		</div>
		<div class="small-12 columns panel">
			<div class="row">
				<div id="order-content" class="small-12 columns">
					<div class="row">
						<div class="small-12 columns">
							<h1>INCOMING ORDER</h1>
						</div>
					</div>
					<div id="order-content-detail" class="row">
						<div id="labels" class="small-3 columns">
							<span id='title' class='label'>ADDRESS:</span>
							<span id='customer' class='label'>CUSTOMER:</span>
							<span id='food' class='label'>ORDER:</span>
						</div>
						<div id='values' class="small-9 columns">
							<span id='customer-name' class='value'></span>
							<span id='order-title' class='value'></span>
							<ul id='food-list' class='value'>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="small-12 columns">
					<a class="modal-button success full-width huge" data-route="vendor_accept">
						<span class="text">ACCEPT</span>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="small-12 columns">
					<a href="#" class="modal-button sml cancel bisecting left" data-route="vendor_reject/unconfirmed">
						<span class="icon-cancel"></span><span class="text">DECLINE</span>
					</a>
					<a href="#" class="modal-button sml confirm bisecting right" onclick="my_test()">
						<span class="text">SET DELIVERY TIME</span><span class="icon-circle-arrow-r"></span>
					</a>
			<div id="order-reject-confirmation" class="slide-left text-center">
				<h1>Are you sure?</h1>
				<a href="#" class="modal-button cancel bisecting left" data-route="vendor_reject/confirm">
					<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
				</a>
				<a href="#" class="modal-button confirm bisecting right" data-route="vendor_reject/cancel">
					<span class="icon-circle-arrow-r"></span><span class="text">Confirm</span>
				</a>
			</div>
		</div>
	</div>

	<div id="order-content-sample" class="true-hidden">
		<div id="order-content-detail" class="row">
			<div id="labels" class="small-3 columns">
				<span id='title' class='label'>ADDRESS:</span>
				<span id='customer' class='label'>CUSTOMER:</span>
				<span id='food' class='label'>ORDER:</span>
			</div>
			<div id='values' class="small-9 columns">
				<span id='customer-name' class='value'></span>
				<span id='order-title' class='value'></span>
				<ul id='food-list' class='value'>
				</ul>
			</div>
		</div>
	</div>

</main>
</div>
