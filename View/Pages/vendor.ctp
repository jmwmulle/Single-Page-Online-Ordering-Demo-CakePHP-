<?php
/**
 * J. Mulle, for app, 1/6/15 10:02 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<div id="vendor-page">
<div class="row">
	<div class="large-12 medium-8 medium-push-12 columns">
		<div class="row">
			<div class="small-8 columns">
				<h1>Next Order</h1>

				<ul class="pending-order panel">
					<li></li>
				</ul>
				<div class="large button success" onClick="showDialog('Order Approved!', 'Status')" data-route="print">Accept</div>
				<div class="large button alert">Decline</div>
			</div>
			<div class="small-4 columns">
				<h3>Pending Orders</h3>
				<ol class="panel">
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
				</ol>
			</div>
		</div>
	</div>
</div>
<script>
//String message, String title
function show_dialog(message, title) {
	Android.showDialog(message, title);
}
//String text, int font_id, String alignment, int line_space, int size_w, int size_h, int x_pos, boolean bold, boolean underline
function print_text(text, font_id, alignment, line_space, size_w, size_h, x_pos, bold, underline){
	Android.printText(text, font_id, alignment, line_space, size_w, size_h, x_pos, bold, underline);
}

//boolean feed
function cut(feed) {
	Android.cut(feed);
}

var cart = {};
//setTimeout(function() { window.location.assign("http://development-xtreme-pizza.ca/pages/vendor");}, 10000);
//setTimeout(function() { window.location.assign("http://kleinlab.psychology.dal.ca/xtreme/pages/vendor");}, 10000);
</script>
</div>
