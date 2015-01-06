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
<?php //db($this->Session->read());
	$customer = array('firstname' => 'bob' ,
                        'lastname' => 'smith',
                        'email' => "this.impetus@gmail.com",
                        'phone' => '1902787019'
	);
	$order_method = "delivery";
	$address = array('address_1' => "123 Somewhere St.", 'address_2' => "Apt. 7", "postal_code" => "B0J 2C0");
?>
<hr>

<div class="row">
	<div class="large-12 columns">
		<?php //if (!$this->Session->read('Cart.Order.order_method') ) {?>
		<?php if ( false ) {?>
		<div class="row">
			<div class="large-6 columns">
				<a href="#" data-route="order_method/review/pickup">Order for Pick-up</a>
			</div>
			<div class="large-6 columns">
				<a href="#" data-route="order_method/review/delivery">Order for Pick-up</a>
			</div>
		</div>
		<?php } else { ?>
		<div class="row">
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns"><h4 class="panel-title">Customer Information</h4></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						Name: <?php echo sprintf("%s %s", $customer['firstname'], $customer['lastname']);?>
					</div>
				</div>
				<?php if (!empty($customer['email']) ) {?>
				<div class="row">
					<div class="large-12 columns">
						Email: <?php echo $customer['email'];?>
					</div>
				</div>
				<?php } ?>
				<div class="row">
					<div class="large-12 columns">
						Phone: <?php echo $customer['phone'];?>
					</div>
				</div>
			</div>
			<div class="large-6 columns">
				<?php if ($order_method == "delivery") {?>
				<div class="row">
					<div class="large-12 columns"><h4 class="panel-title">Delievery Address</h4></div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<?php echo $address["address_1"];?>
						<?php if (!empty($address["address_2"]) ) {?>
						<?php echo  $address["address_2"];?>
						<?php }?>
						<?php echo $address['postal_code']; ?>
					</div>
				</div>
				<?php } else { ?>
					For Pick Up!
					<em>Map to Xtreme</em>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>
<div class="row">
	<div class="col col-sm-1">#</div>
	<div class="col col-sm-6">ITEM</div>
	<div class="col col-sm-1">WEIGHT</div>
	<div class="col col-sm-1">WEIGHT</div>
	<div class="col col-sm-1">PRICE</div>
	<div class="col col-sm-1" style="text-align: right;">QUANTITY</div>
	<div class="col col-sm-1" style="text-align: right;">SUBTOTAL</div>
</div>

<br />
<br />

<?php foreach ($cart['OrderItem'] as $item): ?>
<div class="row">
	<div class="col col-sm-6">
	<?php echo $item['size_name']." "; echo $item['title']; ?>
	</div>
	<div class="col col-sm-1" style="text-align: right;"><?php echo $item['quantity']; ?></div>
	<div class="col col-sm-1" style="text-align: right;">$<?php echo $item['subtotal']; ?></div>
</div>
<?php endforeach; ?>

<hr>

<div class="row">
	<div class="col col-sm-10">Products: <?php echo $cart['Order']['order_item_count']; ?></div>
	<div class="col col-sm-1" style="text-align: right;">Items: <?php echo $cart['Order']['quantity']; ?></div>
	<div class="col col-sm-1" style="text-align: right;">Total<br /><strong>$<?php echo $cart['Order']['total']; ?></strong></div>
</div>

<hr>

<br />
<br />

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

<br />
<br />

