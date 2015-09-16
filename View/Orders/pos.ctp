<div id="loading-img"></div>
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
	</div>
</div>
<div id="delivery-times" class="fade-out hidden">
	<div class="container">
		<div class="row">
			<div class="large-12 columns">
				<ul class="large-block-grid-1 medium-block-grid-2 small-block-grid-2 activizing">
					<?php foreach (array_slice($system, 5, -3) as $index => $sysvar):?>
					<li class="delivery-time <?=$sysvar['Sysvar']['status'] ? "active" : "inactive";?>" data-route="set_delivery_time/<?=$sysvar['Sysvar']['id'];?>">
						<h1 class="text"><?=($sysvar['Sysvar']['id'] - 4) * 15;?> MIN</h1>
					</li>
					<?php endforeach;?>
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

