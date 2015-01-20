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
<main id="vendor-page">
	<div id="back-splash">
		NOTHING</br>
		IN<br />
		QUEUE
	</div>
	<div id="next-order" class="slide-up">
		<div id="pending-orders-count">
			<span id="order-count">96</span>
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
					<span id='customer-name' class='value'>Jonathan Mulle</span>
					<span id='order-title' class='value'>1527 Chestnut</span>
					<ul id='food-list' class='value'>
						<li>Bacon CheeseBurger Pizza</li>
						<li>2L Pepsi</li>
						<li>Lrg. Garlic Fingers w/ 2x Bacon</li>
					</ul>
				</div>
			</div>
		</div>

		<div id="order-accept-button" class="vendor-button" data-route="vendor/accept/print">ACCEPT</div>
		<div id="order-reject-button" class="vendor-button" data-route="vendor/reject/unconfirmed">DECLINE</div>
		<div id="order-reject-confirmation" class="slide-left text-center">
			<h1>Are you sure?</h1>
			<a href="#" class="modal-button cancel bisecting left" data-route="vendor/reject/confirm">
				<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
			</a>
			<a href="#" class="modal-button confirm bisecting right" data-route="vendor/reject/cancel>
				<span class="icon-circle-arrow-r"></span><span class="text">Confirm</span>
			</a>
		</div>
	</div>



</main>
<script>
//String message, String title
function show_dialog(message, title) {
	Android.showDialog(message, title);
}
//String text, int font_id, String alignment, int line_space, int size_w, int size_h, int x_pos, boolean bold, boolean underline
function print_text(text, font_id, alignment, line_space, size_w, size_h, x_pos, bold, underline){
	Android.printText(text, font_id, alignment, line_space, size_w, size_h, x_pos, bold, underline);
}

function print_simple(text) {
	print_text(text, 1, 'left', 1, 1, 1, 1, false, false);
}

//boolean feed
function cut(feed) {
	Android.cut(feed);
}

function print_address(name, address1, address2, postal_code) {
	print_simple(name+'\n');
	print_simple(address1+'\n');
	print_simple(address2+'\n');
	print_simple(postal_code+'\n\n');
}

function print_item(name, price, quantity, toppings, instructions) {
	print_simple(quantity+'x '+name+'\n');
	print_simple('$'+price+'\n');
	print_simple(instructions+'\n');
}

function print_contact_info(phone, email) {
	print_simple('E-Mail: '+email+'\n');
	print_simple('Phone #: '+phone+'\n');
}

var cart = {};
//setTimeout(function() { window.location.assign("http://development-xtreme-pizza.ca/pages/vendor");}, 10000);
//setTimeout(function() { window.location.assign("http://kleinlab.psychology.dal.ca/xtreme/pages/vendor");}, 10000);
</script>
</div>
