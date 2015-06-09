<?php
/**
 * J. Mulle, for app, 5/20/15 4:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$store_active = array("modal-button", "vendor-status", "lrg", "bisecting", "left", $store ? "active" : "inactive",);
$delivery_available = array("modal-button", "vendor-status", "lrg", "bisecting", "left", $delivery ? "active" : "inactive",);
$store_inactive = array("modal-button", "vendor-status", "lrg", "bisecting", "right", $store ? "inactive" : "active");
$delivery_unavailable = array("modal-button", "vendor-status", "lrg", "bisecting", "right", $delivery ? "inactive" : "active");
$closing_time = new DateTime($closing);
$now = new DateTime('now');
?>
<div id="vendor-home-tab">
	<div class="row">
		<div class="large-12 columns text-center">
			<?php echo $this->Html->Image('splash/logo.png', array('id' => 'vendor-home-logo'));?>
			<h3>Menu & Store Activity Manager</h3>
			<h4><?php echo $now->format("l, F jS, H:iA");?></h4>
		</div>
	</div>
	<div class="row">
		<div class="large-6 columns">
			<div class="row">
				<div class="large-12 columns text-center">
					<h2>Store Status</h2>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<a id='store-status-open' href="#" <?php echo ___cD($store_active);?> data-route="vendor_ui/store/close">
						<span class='text'>OPEN</span>
					</a>
					<a id='store-status-closed' href="#" <?php echo ___cD($store_inactive);?> data-route="vendor_ui/store/open">
					<span class='text'>CLOSED</span>
					</a>
				</div>
			</div>
			<div id="set-closing-time" class="hidden">
				<input type="text" id="store-closing-time" value=""/>
				<a href="#" class="modal-button medium full-width" data-route="vendor_ui/closing/set">
					<span class="text">Set Closing Time (Today Only)</span>
				</a>
			</div>
		</div>
		<div class="large-6 columns">
			<div class="row">
				<div class="large-12 columns  text-center">
					<h2>Delivery Status</h2>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<a id='delivery-status-available' href="#" <?php echo ___cD($delivery_available);?> data-route="vendor_ui/delivery/unavailable">
						<span class='text'>AVAILABLE</span>
					</a>
					<a id='delivery-status-unavailable' href="#" <?php echo ___cD($delivery_unavailable);?> data-route="vendor_ui/store/available">
						<span class='text'>UNAVAILABLE</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<P>Store will automatically be set to 'OPEN' or 'CLOSED' according to the normal store hours. Delivery will automatically end 30 minutes before close. Opening or closing the store early/late will persist until the next scheduled open or close (ie. if you close the store at 5pm, it will remain closed until the next automatic opening; if you open the store at 4am to be open late, you will have to manually close it or it will remain open until the next scheduled closing time).</p>
		</div>
	</div>
</div>