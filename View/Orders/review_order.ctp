<?php
	$header = "Review Your Order";
	$subheader = "So close to food you can almost taste it...";

	$credit_card_available = Configure::read( 'Settings.AUTHORIZENET_ENABLED' ) == 1;
	$cart = $this->Session->read( 'Cart' );
	$email = array_key_exists( 'email', $cart[ 'Order' ] ) ? $cart[ 'Order' ][ 'email' ] : false;
	$order = $this->Session->read('Cart.Order');
	$order_method = $this->Session->read('Cart.Service.order_method');
?>

<?=$this->Element( 'primary_modal/masthead', compact('header', 'subheader'));?>

<hr>
<?=$this->Element( 'cart'.DS.'order_methods'.DS.$order_method)?>
<hr/>

<div class="row">
	<div id="micro-cart-contents" class="large-6 columns">
		<?php foreach ( $order as $uid => $oi ) echo $this->element('cart'.DS.'micro_cart_row', compact('uid', 'oi')); ?>
	</div>
	<div class="large-6 columns">
		<div id="payment-method" class="row<?php if ( $order_method != DELIVERY ) echo " true-hidden";?>">
			<div class="large-12 columns">
				<ul class="large-block-grid-2 activizing">
					<li id="order-payment-cash" class="active">
						<a href="#" class="modal-button full-width rounded" data-route="payment_method/review_modal/cash">
							<span class="text">Cash</span>
						</a>
					</li>
					<li id="order-payment-cash" class="inactive">
						<a href="#" class="modal-button full-width rounded" data-route="payment_method/review_modal/debit">
							<span class="text">Debit</span>
						</a>
					</li>
				</ul>
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
		<a id="finalize-order-button" href="#" class="modal-button bisecting confirm right disabled" data-disabled-tip='Choose "Order for Pick-up" or "Order for Delivery" Before Confirming!' data-data-route="order/finalize">
			<span class="text">Confirm & Order</span><span class="icon-circle-arrow-r"></span>
		</a>
	</div>
</div>

<?=$this->Form->create( 'Order' )?>
<?=$credit_card_available ? $this->element('cart'.DS.'credit_card') : ""?>
<?=$this->Form->input( 'payment_method', array( 'type' => 'hidden', 'value' => 'cash' ) )?>
<?=$this->Form->end()?>

<div id="on-close" class="true-hidden" data-action="unstash"></div>
