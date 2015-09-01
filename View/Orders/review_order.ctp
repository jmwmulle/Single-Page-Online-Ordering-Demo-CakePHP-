<?php
	$credit_card_available = Configure::read( 'Settings.AUTHORIZENET_ENABLED' ) == 1;
	$cart = $this->Session->read( 'Cart' );
	$order = $cart['Order'];
	$service = $cart['Service'];
	$user = $cart['User'];
	$invoice = $cart['Invoice'];

	$confirm_order_classes = ["modal-button", "bisecting", "confirm", "right"];
	if ($service['order_method'] != PICKUP and !$service['address_valid']) array_push($confirm_order_classes, "disabled");
	$method_element = $service['order_method'] == JUST_BROWSING ? "unselected" : "selected";
?>

<?=$this->Element( 'primary_modal'.DS.'masthead', $masthead);?>

<hr>
<?=$this->Element( 'cart'.DS.'order_methods'.DS.$method_element, compact('service'))?>
<hr/>

<div class="row<?php if ( !empty($cart[ 'Order' ]) ) echo " true-hidden";?>">
	<div id="empty-cart-message" class="large-8 large-centered columns">
			<p>Well, "nothing" probably won't make for a satisfying meal.</p>
			<p>But on the upside it's free?</p>
	</div>
</div>
<div class="row<?php if ( empty($cart[ 'Order' ]) ) echo " true-hidden";?>">
	<div id="micro-cart-contents" class="large-6 columns">
		<div class="row">
			<div class="large-12 columns"><h5 class="panel-title">Your Order</h5></div>
		</div>
		<?php foreach ( $order as $uid => $oi ) echo $this->element('cart'.DS.'micro_cart_row', compact('uid', 'oi')); ?>
	</div>
	<div class="large-6 columns">
		<div id="payment-method" class="row<?php if ( $service['order_method'] != DELIVERY ) echo " true-hidden";?>">
			<div class="large-12 columns">
				<a id="payment-cash" href="#" class="modal-button sml discreet bisecting active" data-route="payment_method/review_modal/cash">
					<span>Cash</span>
				</a>
				<a id="payment-debit" href="#" class="modal-button sml discreet bisecting cancel  inactive" data-route="payment_method/review_modal/debit">
					<span>Debit</span>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<ul class="">
					<li>SubTotal:</li>
					<li>HST:</li>
					<li>Total:</li>
				</ul>
			</div>
			<div class="large-6 columns">
				<ul>
					<li><?=money_format( "%#3.2n", $cart['Invoice']['subtotal'])?></li>
					<li><?=money_format( "%#3.2n", $cart['Invoice']['hst'])?></li>
					<li><?=money_format( "%#3.2n", $cart['Invoice']['total'])?></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
		<a href="#" class="modal-button bisecting cancel left" data-route="menu/unstash">
			<span class="icon-circle-arrow-l"></span><span class="text">Continue Ordering</span>
		</a>
		<a id="finalize-order-button" href="#" <?=___cD($confirm_order_classes);?> data-route="finalize_order">
			<span class="text">Confirm & Order</span><span class="icon-circle-arrow-r"></span>
		</a>
	</div>
</div>

<?=$this->Form->create( 'Order' )?>
<?=$credit_card_available ? $this->element('cart'.DS.'credit_card') : ""?>
<?=$this->Form->input( 'payment_method', array( 'type' => 'hidden', 'value' => 'cash' ) )?>
<?=$this->Form->end()?>

<div id="on-close" class="true-hidden" data-action="unstash"></div>
