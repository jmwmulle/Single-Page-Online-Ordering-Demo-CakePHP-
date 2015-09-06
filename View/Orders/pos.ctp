<div id="order-tone-wrapper" class="true-hidden">
	<audio controls>
	  <source src="files/new_order_tone.mp3" type="audio/mpeg">
	</audio>
</div>
<div id="error-pane">
	<div id="error-text"></div>
</div>
<div id="back-splash" class="">
	NOTHING<br />
	IN<br />
	QUEUE
</div>

<div id="pos-ui" class="slide-up row">
	<div id="pending-orders-count" class="fade-out">
		<span id="order-count">0</span>
		<h5>IN QUEUE</h5>
	</div>
	<div class="small-12 columns panel">
		<div class="row">
			<div id="order-content" class="small-12 columns">
				<table>
					<tr class="address">
						<td class="order-label"><h2 class="order-method"><span>CUSTOMER</span></h2></td>
						<td id="address" class="value"></td>
					</tr>
					<tr class="food">
						<td class="order-label"><h2 class="food"><span>ORDER</span></h2></td>
						<td id="food" class="value"></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<a id="order-accept-button" class="modal-button success full-width huge" data-route="pos_reply/-1/accept">
					<span class="text">ACCEPT</span>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<a href="#" class="modal-button sml cancel full-width" data-route="vendor_reject/unconfirmed">
					<span class="icon-cancel"></span><span class="text">DECLINE</span>
				</a>
			</div>
		</div>
		<div id="order-reject-confirmation" class="slide-left text-center">
			<h1>Are you sure?</h1>
			<a href="#" class="modal-button cancel full-width left" data-route="vendor_reject/confirm">
				<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
			</a>
			<a href="#" class="modal-button confirm bisecting right" data-route="vendor_reject/cancel">
				<span class="icon-circle-arrow-r"></span><span class="text">Confirm</span>
			</a>
		</div>
	</div>
</div>
<div id="delivery-times" class="fade-out hidden">
	<div class="container">
		<div class="row">
			<div class="large-12 columns">
				<ul class="large-block-grid-2 activizing">
					<?php for ($i=30; $i<=105; $i+=15):?>
					<li class="delivery-time <?=$i == 45 ? "active" : "inactive";?>" data-route="set_delivery_time/<?=$i;?>">
						<h1 class="text"><?=$i;?> MIN</h1>
					</li>
					<?php endfor;?>
				</ul>
				<a href="#" class="modal-button lrg full-width cancel" data-route="delivery_time_buttons/hide">
					<span class="icon-cancel"></span><span class="text">CANCEL</span>
				</a>
			</div>
		</div>
	</div>
</div>
<a href="#" id="delivery-time-button" data-route="delivery_time_buttons/show">
	<span class="text">SET DELIVERY TIME</span>
</a>

<div id="accept-acknowledge" class="slide-right">
	<p id="message" class="fade-out">
		<span>Grumpy wizards make toxic brew for evil queen and jack.</span></p>
</div>

<main id="js-console" class="hidden">
<h4 id="called-from"></h4>
</main>

