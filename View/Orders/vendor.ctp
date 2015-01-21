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

		<div id="order-accept-button" class="vendor-button" data-route="vendor/accept/print">ACCEPT</div>
		<div id="order-reject-button" class="vendor-button" data-route="vendor/reject/unconfirmed">DECLINE</div>
		<div id="order-reject-confirmation" class="slide-left text-center">
			<h1>Are you sure?</h1>
			<a href="#" class="modal-button cancel bisecting left" data-route="vendor/reject/confirm">
				<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
			</a>
			<a href="#" class="modal-button confirm bisecting right" data-route="vendor/reject/cancel">
				<span class="icon-circle-arrow-r"></span><span class="text">Confirm</span>
			</a>
		</div>
	</div>



</main>
<script>
function accept_order (order) {
	print_simple(order.address);
	print_simple('Delivery Instructions: '+order.delivery_instructions+'\n');
	print_simple('Time Ordered: '+order.time+'\n');
	print_simple('Total: $'+order.price+'\n');
	print_simple('Ordered for: '+order.order_method+'\n');
	print_simple('Paying with: '+order.payment_method+'\n');
	if (order.paid) {
		print_simple('Paid: Yes\n');
	} else {
		print_simple('Paid: No\n'
	}
	print_items(order.food);

			
}

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

function print_title(text) {
	print_text(text, 1, 'left', 1, 2, 2, 1, true, false);
}

//boolean feed
function cut(feed) {
	Android.cut(feed);
}

function print_items(items) {
	for (var name in items) {
		print_item(name, items[name]);
	}
}

function print_item(name, item) {
	print_simple(item.quantity+'x '+name+'\n');
	print_simple('$'+item.price+'\n');
	for (var topping in toppings) {
		print_simple('\t'+topping.title+' '+topping.weight+'\n');
	}
	print_simple(item.instructions+'\n');
}

var cart = {};
//setTimeout(function() { window.location.assign("http://development-xtreme-pizza.ca/pages/vendor");}, 10000);
//setTimeout(function() { window.location.assign("http://kleinlab.psychology.dal.ca/xtreme/pages/vendor");}, 10000);
</script>
</div>
