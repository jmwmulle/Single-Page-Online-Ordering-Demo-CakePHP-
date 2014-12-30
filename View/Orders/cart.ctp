<?php echo $this->set( 'title_for_layout', 'Shopping Cart' ); ?>

<?php $this->Html->addCrumb( 'Shopping Cart' ); ?>

<?php echo $this->Html->script( array( 'cart.js' ), array( 'inline' => false ) );
//	db($cart);
?>
<div class="row">
	<div class="large-12 columns">
		<div class="row">
			<div class="large-4 columns">
				<?php echo $this->element("modal_masthead"); ?>
			</div>
			<div class="large-8 columns">
				<h1>Mmmm... Cart Contents</h1>
				<h3>Let's see what you're getting </h3>
			</div>
		</div>
		<div class="row">
			<div id="empty-cart-message" class="large-8 large-centered columns">
				<?php if (empty( $cart[ 'OrderItem' ] )) { ?>
					<p>Well, "nothing" probably won't make for a satisfying meal.</p>
					<p>But on the upside it's free?</p>
				<?php } else { ?>
			</div>
		</div>
		<div class="row">
			<div class="large-1 large-push-11 columns">
				<a href="#">Clear Cart</a>
			</div>
		</div>
		<div class="row">
			<ul>
				<li class="large-7 columns inline">ITEM</li>
				<li class="large-2 columns inline">QUANTITY</li>
				<li class="large-2 columns inline">PRICE</li>
				<li class="large-1 columns inline">&nbsp;</li>
			</ul>
		</div>
		<?php foreach ($cart[ 'OrderItem' ] as $index => $item) { ?>
		<div class="row">
			<ul id="order-item-<?php echo $index; ?>">
				<li class="large-7 columns inline">
					<div class="row">
						<div class="large-12 columns">
							<h3><?php echo $item[ 'title' ]; ?></h3>
						</div>
					</div>
				<?php if ( array_key_exists('orb_opts', $item) ) { ?>
					<div class="row">
						<div class="large-12 columns">
							<ul class="large-block-grid-5">
							<?php foreach ( $item[ 'orbopts' ] as $opt_id => $opt ) {
								$opt = $opt['Orbopt'];
								?>
								<li><?php echo $opt[ 'title' ];?></li>
							<?php }?>
							</ul>
						</div>
					</div>
				<?php }
					if ( array_key_exists('preparation_instructions', $item) ) {?>
					<div class="row">
						<div class="large-12 columns">
							<span class="preparation-instructions"><?php echo $item['preparation_instructions'];?></span>
						</div>
					</div>
				<?php } ?>
				</li>
				<li class="large-2 columns inline"><?php echo $item['quantity'];?></li>
				<li class="large-2 columns inline"><?php echo money_format("%#3.2n", $item['subtotal']);?></li>
				<li class="large-1 columns inline"><span class="icon-cancel"></li>
			</ul>
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
			<?php } ?>
		<div class="row">
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
	<?php echo $this->Form->end();
			}?>
<div id="on-close" class="true-hidden" data-action="unstash"></div>