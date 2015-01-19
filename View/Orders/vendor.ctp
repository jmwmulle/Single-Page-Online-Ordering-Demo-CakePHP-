<?php
/**
 * J. Mulle, for app, 1/6/15 10:02 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<script type="text/javascript"> var cart = {}; </script>
<div id="vendor-page">
<div class="row">
	<div class="large-12 medium-8 medium-pull-4 columns">
		<div class="row">
			<div class="small-8 columns">
				<h1>Next Order</h1>

				<ul class="pending-order panel">
					<li></li>
				</ul>
				<div onClick="showMessage('Order Approved', 'Status Update')" class="large button success">Accept</div>

				<script type="text/javascript">
					function showMessage(message, title) {
	            				Android.showDialog(message, title);
		        		}
				</script>
				<div class="large button alert">Decline</div>
			</div>
			<div class="small-4 columns">
				<h3>Pending Order3</h3>
				<ul class="panel">
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
					<li>$37.85, Smith, T. 902-441-4893</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script>
var cart = {};
//setTimeout(function() { window.location("http://development-xtreme-pizza.ca/pages/vendor");}, 20000);
setTimeout(function() { window.location.assign("http://kleinlab.psychology.dal.ca/xtreme/pages/vendor");}, 10000);
</script>
</div>
