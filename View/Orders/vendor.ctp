<?php
/**
 * J. Mulle, for app, 1/6/15 10:02 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<div id="order-tone-wrapper" class="true-hidden">
	<audio controls>
	  <source src="files/new_order_tone.mp3" type="audio/mpeg">
	</audio>
</div>
<div id="error-pane">
	<div id="error-text"></div>
</div>
<main id="vendor-page">
	<div id="back-splash">
		NOTHING</br>
		IN<br />
		QUEUE
	</div>
	<div id="order-accepted" class="fade-out">Loading...</div>
	<div id="next-order" class="slide-up">
		<div id="pending-orders-count">
			<span id="order-count">0</span>
			<h5>IN QUEUE</h5>
		</div>
		<div id="order-content">
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

		<div id="order-accept-button" class="vendor-button">ACCEPT</div>
		<div id="order-reject-button" class="vendor-button" data-route="vendor_reject/unconfirmed">DECLINE</div>
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
<script>

//setTimeout(function() { window.location.assign("http://development-xtreme-pizza.ca/pages/vendor");}, 10000);
//setTimeout(function() { window.location.assign("http://kleinlab.psychology.dal.ca/xtreme/pages/vendor");}, 10000);
</script>
</div>
