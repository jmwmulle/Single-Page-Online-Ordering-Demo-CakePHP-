<?php echo $this->set('title_for_layout', 'Shopping Cart'); ?>

<?php $this->Html->addCrumb('Shopping Cart'); ?>

<?php echo $this->Html->script(array('cart.js'), array('inline' => false)); ?>

<h1>Your Order</h1>

<?php if(empty($cart['OrderItem'])) : ?>

You haven't ordered anything yet.

<?php else: ?>

<?php echo $this->Form->create(NULL, array('url' => array('controller' => 'orders', 'action' => 'cartupdate'))); ?>

<hr>

<div class="row">
	<div class="col col-sm-1">#</div>
	<div class="col col-sm-7">ITEM</div>
	<div class="col col-sm-1">PRICE</div>
	<div class="col col-sm-1">QUANTITY</div>
	<div class="col col-sm-1">SUBTOTAL</div>
	<div class="col col-sm-1">REMOVE</div>
</div>

<?php $tabindex = 1; $size = 0;?>
<?php foreach ($cart['OrderItem'] as $key => $item): ?>

	<div class="row" id="row-<?php echo $key; ?>">
		<div class="col col-sm-7">
			<strong><?php echo $this->Html->link($item['Orb']['title'], array('controller' => 'orbs', 'action' => 'view')); ?></strong>
			<?php
			$mods = 0;
			if(isset($item['Orb']['title'])) :
				$mods = $item['Orb']['id'];
				?>
				<br />
				<small><?php foreach($item['orbopts'] as $opt) {echo $opt['title'];} ?></small>
			<?php endif; ?>
		</div>
                <?php echo $this->Form->input('id', array('hiddenField'=>true, 'value'=>$key)); ?>
                <div class="col col-sm-1" id="desc-<?php echo $key; ?>"> <?php echo $item['Orb']['description'] ?></div>
		<div class="col col-sm-1"><?php echo $this->Form->input('preparation_instructions', array('div' => false, 'class' => 'text form-control input-small', 'label' => false, 'size' => 2, 'maxlength' => 144, 'tabindex' => $tabindex++, 'data-id' => $item['Orb']['id'], 'data-mods' => $mods, 'value' => $item['preparation_instructions'])); ?></div>
                <div class="col col-sm-1"><?php echo $this->Form->input('price_rank', array('div' => false, 'class' => 'numeric form-control input-small', 'label' => false, 'size' => 1, 'maxlength' => 1, 'tabindex' => $tabindex++, 'data-id' => $item['Orb']['id'], 'data-mods' => $mods, 'value' => $item['price_rank'])); ?></div>
                <div class="col col-sm-1"><?php echo $this->Form->input('quantity', array('div' => false, 'class' => 'numeric form-control input-small', 'label' => false, 'size' => 2, 'maxlength' => 2, 'tabindex' => $tabindex++, 'data-id' => $item['Orb']['id'], 'data-mods' => $mods, 'value' => $item['quantity'])); ?></div>
		<div class="col col-sm-1" id="subtotal_<?php echo $key; ?>"><?php echo $item['subtotal']; ?></div>
		<div class="col col-sm-1"><span class="remove" id="<?php echo $key; ?>"></span></div>
	</div>
<?php endforeach; ?>

<hr>

<div class="row">
	<div class="col col-sm-12">
		<div class="pull-right">
		<?php echo $this->Html->link('<i class="icon-remove icon"></i> Clear Cart', array('controller' => 'orders', 'action' => 'clear'), array('class' => 'btn btn-danger', 'escape' => false)); ?>
		&nbsp; &nbsp;
		<?php echo $this->Form->button('<i class="icon-refresh icon"></i> Recalculate', array('class' => 'btn btn-default', 'escape' => false));?>
		<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>

<hr>

<div class="row">
	<div class="col col-sm-12 pull-right tr">
		Subtotal: <span class="normal" id="subtotal">$<?php echo $cart['Order']['subtotal']; ?></span>
		<br />
		<br />
		Sales Tax: <span class="normal" id="HST">$<?php echo $cart['Order']['HST']; ?></span>
		<br />
		<br />
		Delivery: <span class="normal" id="delivery">$<?php echo $cart['Order']['delivery']; ?></span>
		<br />
		<br />
		Order Total: <span class="red" id="total">$<?php echo $cart['Order']['total']; ?></span>
		<br />
		<br />

		<?php echo $this->Html->link('<i class="glyphicon glyphicon-arrow-right"></i> Checkout', array('controller' => 'orders', 'action' => 'address'), array('class' => 'btn btn-primary', 'escape' => false)); ?>

		<br />
		<br />

		<?php echo $this->Form->create(NULL, array('url' => array('controller' => 'orders', 'action' => 'step1'))); ?>
		<input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0' align='top' alt='Check out with PayPal' class="sbumit" />
		<?php echo $this->Form->end(); ?>

	</div>
</div>

<br />
<br />

<?php endif; ?>
