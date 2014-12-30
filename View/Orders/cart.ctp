<?php echo $this->set( 'title_for_layout', 'Shopping Cart' ); ?>

<?php $this->Html->addCrumb( 'Shopping Cart' ); ?>

<?php echo $this->Html->script( array( 'cart.js' ), array( 'inline' => false ) ); ?>

<h1>Your Order</h1>
<?php if (empty( $cart[ 'OrderItem' ] )) { ?>
	<p>You haven't ordered anything yet.</p>
<?php } else { ?>
<ul class="large-block-grid-5">
	<li class="small-1 columns">#</li>
	<li class="small-7 columns">ITEM</li>
	<li class="small-1 columns">PRICE</li>
	<li class="small-1 columns">QUANTITY</li>
	<li class="small-1 columns">SUBTOTAL</li>
	<li class="small-1 columns">REMOVE</li>
</ul>
<?php foreach ($cart[ 'OrderItem' ] as $index => $item) { ?>
<div id="order-item-<?php echo $index; ?>">
	<h3><?php echo $item[ 'title' ]; ?></h3>
	<?php foreach ( $item[ 'orbopts' ] as $opt ) {
		echo $opt[ 'title' ];
	}?>
	<div id="subtotal_<?php echo $index; ?>"><?php echo $item[ 'subtotal' ]; ?></div>
	<div><span class="remove" id="<?php echo $index; ?>"></span></div>
	<div class="true-hidden"><?php
			echo $this->Form->create( null, array( 'url' => ___cakeUrl( 'orders', 'cartupdate' )) );
			echo $this->Form->input( 'orb_id', array( 'hiddenField' => true, 'value' => $item['orb_id']) );
			echo $this->Form->input( 'orbopts', array( 'hiddenField' => true, 'value' => $item['orbopts']) );
			echo $this->Form->input( 'preparation_instructions', array( 'div'     => false,
			                                                            'class'   => 'text form-control input-small',
			                                                            'label'   => false,
			                                                            'data-id' => $item['orb_id'],
			                                                            'value'   => $item['preparation_instructions'] )
			);
			echo $this->Form->input( 'price_rank', array( 'div'     => false,
			                                              'class'   => 'numeric form-control input-small',
			                                              'label'   => false,
			                                              'data-id' => $item['orb_id'],
			                                              'value'   => $item['price_rank'] ));
			echo $this->Form->input( 'quantity', array( 'div'     => false,
			                                            'class'   => 'numeric form-control input-small',
			                                            'label'   => false,
			                                            'data-id' => $item[ 'orb_id' ],
			                                            'value'   => $item[ 'quantity' ]));
			echo $this->Form->end();
		?>
	</div>
</div>
<?php }
}?>
<div>
	<a href="#">Clear Cart</a>
	<a href="#">Recalculate</a>
</div>
<ul>
	<li>Subtotal: <span class="normal" id="subtotal">$<?php echo $cart[ 'Order' ][ 'subtotal' ]; ?></span></li>
	<li>Sales Tax: <span class="normal" id="HST">$<?php echo $cart[ 'Order' ][ 'HST' ]; ?></span></li>
	<li>Delivery: <span class="normal" id="delivery">$<?php echo $cart[ 'Order' ][ 'delivery' ]; ?></span></li>
	<li>Order Total: <span class="red" id="total">$<?php echo $cart[ 'Order' ][ 'total' ]; ?></span></li>
	<li><a href="#" data-route="orders/address">CheckOut</a></li>
</ul>
	<?php echo $this->Form->create( null, array( 'url' => ___cakeUrl('orders','step1' )) );?>
	<input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0'
			       align='top' alt='Check out with PayPal' class="sbumit"/>
	<?php echo $this->Form->end(); ?>
<div id="on-close" class="true-hidden" data-action="unstash"></div>