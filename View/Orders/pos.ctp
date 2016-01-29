
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
			<div  class="small-12 columns">
				<div id="order-accept-panel">
					<a id="order-accept-button" class="modal-button pos-button success full-width huge" data-pressroute="pos_reply/-1/accept">
						<span class="text">ACCEPT</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<!--- DELIVERY TIMES PANE --->

<div id="delivery-times" class="fade-out hidden tab-panel">
	<div class="container">
		<div class="row">
			<div class="large-12 columns">
				<ul class="large-block-grid-1 medium-block-grid-1 small-block-grid-1 activizing">
					<?php foreach (array_slice($system, 5, -3) as $index => $sysvar):?>
					<li class="delivery-time <?=$sysvar['Sysvar']['status'] ? "active" : "inactive";?>" data-route="set_delivery_time/<?=$sysvar['Sysvar']['id'];?>">
						<h1 class="text"><?=($sysvar['Sysvar']['id'] - 4) * 15;?> MIN</h1>
					</li>
					<?php endforeach;?>
				</ul>
				<a href="#" class="modal-button lrg full-width cancel bottom-rounded" data-route="delivery_time_buttons/hide">
					<span class="icon-cancel"></span><span class="text">CANCEL</span>
				</a>
			</div>
		</div>
	</div>
</div>

<!--- ORDER HISTORY PANE --->

<div id="order-history" class="fade-out hidden tab-panel">
	<div class="container">
	</div>
</div>

<!--- TABS --->
<a href="#" id="delivery-time-button" class="pos-button pos-tab" data-pressroute="delivery_time_buttons/show">
	<span class="text">SET DELIVERY TIME</span>
</a>
<a href="#" id="order-history-button" class="pos-button pos-tab" data-pressroute="order_history/show">
	<span class="text">ORDER HISTORY</span>
</a>

<!--- ORDER UI --->
<div id="accept-acknowledge" class="slide-right">
	<div id="post-accept-wrapper" class="">
		<div id="post-accept-panel" class="small-12 columns">
			<a id="order-print-button" class="modal-button full-width pos-button" data-pressroute="pos_print">
				<span class="text">PRINT</span>
			</a>
			<a id="order-clear-button" class="modal-button full-width pos-button" data-pressroute="pos_clear">
				<span class="text">CLEAR</span>
			</a>
		</div>
	</div>
	<p id="message" class="fade-out">
		<span>Grumpy wizards make toxic brew for evil queen and jack.</span>
	</p>
</div>

<!--- ERROR MESSAGES --->
<div id="fatal-error" class="fade-out hidden">
	<h2>ERROR: <span class="error-message"></span></h2>
	<h3>What To Do:</h3>
	<ul>
		<li>1. Restart the tablet app</li>
		<li>2. If that failed, clear the app data. Open the tablet's Settings app, find the application management area, find the Xtreme app and then delete local data (NOT the app).</li>
		<li>3. Turn the printer and the tablet OFF and then on again.</li>
		<li>4. You should never have got here, this can't be good. Call Jon.</li>
	</ul>
</div>

<div id="polling-button" style="display:none;" onclick="XT.pos.toggle_polling()"> TOGGLE POLLING </div>
<main id="js-console" class="hidden">
<h4 id="called-from"></h4>
</main>

