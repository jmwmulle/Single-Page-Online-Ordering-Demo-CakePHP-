<?php echo $this->set('title_for_layout', 'Order Review'); ?>

<?php $this->Html->addCrumb('Order Review'); ?>
<?php echo $this->Element('modal_masthead', array('header' => "Review Your Order",
                                                 'subheader' => "So close to food you can almost taste it..."));?>
<?php echo $this->Html->script(array('shop_review.js'), array('inline' => false)); ?>
<!---->
<!--<style type="text/css">-->
<!--	#ccbox {-->
<!--		background: transparent url("--><?php //echo $this->webroot; ?><!--img/cards.png");-->
<!--		margin: 0 0 10px 0;-->
<!--		padding: 0 0 0 150px;-->
<!--		width: 0;-->
<!--		height: 23px;-->
<!--		overflow: hidden;-->
<!--	}-->
<!--</style>-->
<?php
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
			<div class="large-6 columns">
				<?php if ( count($address) == 0  ) { ?>
				<div class="row">
					<div class="large-12 columns">
						<a href="#" class="modal-button" data-route="order_method/review/delivery">Set Delivery Address</a>
					</div>
				</div>
				<?php } else {?>
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
			<?php }?>
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
		<?php } else { ?>
			<div class="large-12 columns">
				<a href="#" class="modal-button full-width active" data-route="order_method/review/delivery"><span class="text">Order is for Pick-Up</span></a>
			</div>
		<?php } ?>
		</div>
	<?php } ?>
	</div>
</div>
<hr />
<div class="row">
	<div class="large-6 columns">
		<div class="row">
<?php foreach ($cart['OrderItem'] as $item) { ?>
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
<?php echo $this->Form->create('Order'); ?>

<?php if((Configure::read('Settings.AUTHORIZENET_ENABLED') == 1) && $cart['Order']['order_type'] == 'creditcard') : ?>

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
	<div class="col col-sm-2">
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

<br />

<div class="row">
	<div class="col col-sm-2">
		<?php echo $this->Form->input('creditcard_code', array('label' => 'Card Security Code', 'class' => 'form-control', 'maxLength' => 4)); ?>
	</div>
</div>

<br />

<?php endif; ?>

<?php echo $this->Form->button('Submit Order', array('class' => 'btn btn-primary', 'ecape' => false)); ?>

<?php echo $this->Form->end(); ?>

<div id="on-close" class="true-hidden" data-action="unstash"></div>
