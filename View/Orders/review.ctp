
<?php $this->Html->addCrumb('Order Review'); ?>
<?php echo $this->Element('modal_masthead', array('header' => "Review Your Order",
                                                 'subheader' => "So close to food you can almost taste it..."));?>
<?php echo $this->Html->script(array('shop_review.js'), array('inline' => false)); ?>

<?php
	$credit_card_available = Configure::read('Settings.AUTHORIZENET_ENABLED') == 1;
	$cart = $this->Session->read('Cart');
	$order = array_key_exists('Order', $cart) ? $cart['Order'] : array();
	$address = array_key_exists('address', $order) ? $order['address'] : array();
	$order_method = array_key_exists('order_method', $order) ? $order['order_method'] : false;
?>
<hr>

<div class="row">
	<div class="large-12 large-centered columns">
		<?php if (!$order_method) {?>
				<a href="#" class="modal-button bisecting left" data-route="order_method/review/pickup"><span class="text">Order for Pick-up</span></a
				><a href="#"  class="modal-button bisecting right" data-route="order_method/review/delivery"><span class="text">Order for Delivery</span></a>
		<?php } else { ?>
		<div class="row">
		<?php if ($order_method == "delivery") { ?>
				<?php if ( empty($address) ) { ?>
				<div class="large-12 columns">
					<a href="#" class="modal-button bisecting cancel left" data-route="order_method/review/just_browsing">
						<span class="text">Change</span>
					</a>
					<a href="#" class="modal-button bisecting confirm right" data-route="order_method/review/delivery">
						<span class="text">Set Delivery Address</span>
					</a>
				</div>
				<?php } else {?>
				<div class="large-6 columns">
					<div class="row">
						<div class="large-12 columns"><h4 class="panel-title">Customer Information</h4></div>
					</div>
					<div class="row">
						<div class="large-12 columns">
							Name: <?php echo sprintf("%s %s", $address['firstname'], $address['lastname']);?>
						</div>
					</div>
				<?php if (array_key_exists('email', $address) && !empty($address['email']) ) {?>
					<div class="row">
						<div class="large-12 columns">
							Email: <?php echo $customer['email'];?>
						</div>
					</div>
				<?php } ?>
					<div class="row">
						<div class="large-12 columns">
							Phone: <?php echo $address['phone'];?>
						</div>
					</div>
			</div>
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns"><h4 class="panel-title">Delivery Address</h4></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<?php echo $address["address"];?>
						<?php if (!empty($address["address_2"]) )echo  $address["address_2"];?>
						<?php echo $address['postal_code']; ?>
					</div>
				</div>
			</div>
			<?php }
			} else { ?>
			<div class="large-12 columns">
				<a href="#" class="modal-button full-width active" data-route="order_method/review/just_browsing"><span class="text">Order is for Pick-Up</span></a>
			</div>
		<?php } ?>
		</div>
	<?php } ?>
	</div>
</div>
<hr />
<div class="row">
	<div id="micro-cart-contents" class="large-6 columns">
<?php foreach ($cart['OrderItem'] as $item) { ?>
		<div class="row">
			<div class="large-6 columns"> <?php echo $item['size_name']." "; echo $item['title']; ?></div>
			<div class="large-2 columns"><?php echo $item['quantity']; ?></div>
			<div class="large-4 columns"><?php echo $item['subtotal']; ?></div>
		</div>
<?php } ?>
	</div>
	<div class="large-6 columns">
		<h3>Total: <strong>$<?php echo $cart['Order']['total']; ?></strong></h3>
	</div>
</div>
<?php if ($order_method == "delivery") {?>
<div id="payment-method" class="row">
	<div class="large-12 columns">
		<a id="order-payment-cash" href="#" class="modal-button bisecting left active activizing" data-route="order/payment/cash">
			<span class="text">Cash</span>
		</a>
		<a id="order-payment-cash" href="#" class="modal-button bisecting right activizing"  data-route="order/payment/debit">
			<span class="text">Debit at your Door</span>
		</a>
	</div>
</div>
<?php } ?>
<div class="row">
	<div class="large-12 columns">
		<a href="#" class="modal-button bisecting cancel left" data-route="order/clear"><span class="text">Cancel Order</span></a>
		<a href="#" class="modal-button bisecting confirm right" data-route="order/finalize"><span class="text">Confirm & Order</span></a>
	</div>
</div>
<?php echo $this->Form->create('Order'); ?>

<?php if ($credit_card_available) {?>
<div id="ccbox">
	Credit Card Type.
</div>

<div class="row">
	<div class="col col-sm-3">
		<?php echo $this->Form->input('creditcard_number', array('class' => 'form-control ccinput', 'maxLength' => 16, 'autocomplete' => 'off')); ?>
	</div>
</div>

<br />

<div class="row">
	<div class="large-4 columns">
		<?php echo $this->Form->input('creditcard_month', array(
			'label' => 'Expiration Month',
			'class' => 'form-control',
			'options' => array(
				'01' => '01 - January',
				'02' => '02 - February',
				'03' => '03 - March',
				'04' => '04 - April',
				'05' => '05 - May',
				'06' => '06 - June',
				'07' => '07 - July',
				'08' => '08 - August',
				'09' => '09 - September',
				'10' => '10 - October',
				'11' => '11 - November',
				'12' => '12 - December'
			)
		)); ?>
	</div>
	<div class="col col-sm-2">
		<?php echo $this->Form->input('creditcard_year', array(
			'label' => 'Expiration Year',
			'class' => 'form-control',
			'options' => array_combine(range(date('y'), date('y') + 10), range(date('Y'), date('Y') + 10))
		));?>
	</div>
</div>

<div class="row">
	<div class="large-6 columns">
		<?php echo $this->Form->input('creditcard_code', array('label' => 'Card Security Code', 'class' => 'form-control', 'maxLength' => 4)); ?>
	</div>
</div>
<?php }
	echo $this->Form->input('payment_method', array('type' => 'hidden', 'value' => 'cash'));
	echo $this->Form->end();
?>

<div id="on-close" class="true-hidden" data-action="unstash"></div>
