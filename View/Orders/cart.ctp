<?php echo $this->set( 'title_for_layout', 'Shopping Cart' ); ?>

<?php $this->Html->addCrumb( 'Shopping Cart' ); ?>

<?php echo $this->Html->script( array( 'cart.js' ), array( 'inline' => false ) );
//	db($cart);
//	db($cart);
echo $this->element("modal_masthead", array(
	"header" => "Mmmm... Cart Contents",
"subheader" => "Couldn't have chosen better ourselves!")); ?>

<div class="row">
	<div class="large-12 columns default-content">
	<?php if (empty( $cart[ 'OrderItem' ] )) { ?>
		<div class="row">
			<div id="empty-cart-message" class="large-8 large-centered columns">
					<p>Well, "nothing" probably won't make for a satisfying meal.</p>
					<p>But on the upside it's free?</p>
			</div>
		</div>
		<?php } else { ?>
		<div class="row view-cart-row cart-header">
			<div class="large-7 columns"><span>ITEM</span></div>
			<div class="large-2 columns text-center"><span>QUANTITY</span></div>
			<div class="large-2 columns"><span>PRICE</span></div>
			<div class="large-1 columns text-center">&nbsp;</div>
		</div>

		<?php foreach ($cart[ 'OrderItem' ] as $index => $item) { $item = $item[0]?>
		<div class="row view-cart-row">
			<div class="large-12 columns row-wrapper">
				<div id="order-item-<?php echo $index; ?>" class="row primary-row">
					<div class="large-7 columns"><span><?php echo $item[ 'title' ]; ?></span></div>
					<div class="large-2 columns text-center"><span><?php echo $item['quantity'];?></span></div>
					<div class="large-2 columns "><span><?php echo money_format("%#3.2n", $item['subtotal']);?></span></div>
					<div class="large-1 columns text-center"><span class="icon-cancel"></div>
				</div>
			<?php if ( array_key_exists('orbopts', $item) ) { ?>
				<div class="row">
					<div class="large-12 columns secondary-row orbopts">
						<?php foreach ( $item[ 'orbopts' ] as $opt_id => $opt ) {
							$opt = $opt['Orbopt'];
							$opt_weight = $item['orbopts_arrangement'][$opt_id];
							?>
							<a href="#" data-route="edit_orbopt_in_cart<?php echo DS.$index.DS.$opt_id;?>">
							<span class="opt-label"><?php echo $opt[ 'title' ];?>
							<?php switch($opt_weight) {
										case "R":
											echo '<span class="icon-right-side"></span>';
											break;
										case "L":
											echo '<span class="icon-left-side"></span>';
											break;
										case "D":
											echo '<span class="icon-double"></span>';
											break;
									}?>
							</span></a>
						<?php }?>
					</div>
				</div>
			<?php }
			if ( array_key_exists('preparation_instructions', $item) && !empty($item['preparation_instructions']) ) {?>
				<div class="row">
					<div class="large-12 columns secondary-row preparation-instructions">
						<span class="preparation-instructions"><?php echo $item['preparation_instructions'];?></span>
					</div>
				</div>
			<?php } ?>
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
		</div>
			<?php } ?>
		<div class="row view-cart-row total">
			<div class="large-9 columns">
				<div class="row view-cart-row payment">
					<?php echo $this->Form->create( null, array( 'url' => ___cakeUrl('orders','step1' )) );?>
					<div class="large-2 columns">
						<h5>Pay With:</h5>
					</div>
					<div class="large-4 columns">
						<input type='image' name='submit' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' border='0'
							       align='top' alt='Check out with PayPal'/>
					</div>
					<div class="large-2 columns">
						<span>Debit</span>
					</div>
					<div class="large-2 columns">
						<span>Cash</span>
					</div>
					<div class="large-2 columns">
						<span>Credit</span>
					</div>
					<?php echo $this->Form->end();?>
				</div>
			</div>
			<div class="large-3 columns">
				<ul>
					<li>Subtotal: <span class="normal" id="subtotal">$<?php echo $cart[ 'Order' ][ 'subtotal' ]; ?></span></li>
					<li>Sales Tax: <span class="normal" id="HST">$<?php echo $cart[ 'Order' ][ 'HST' ]; ?></span></li>
					<li>Delivery: <span class="normal" id="delivery">$<?php echo $cart[ 'Order' ][ 'delivery' ]; ?></span></li>
					<li>Order Total: <span class="red" id="total">$<?php echo $cart[ 'Order' ][ 'total' ]; ?></span></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="large-12 large-centered columns">
				<a href="#" class="xtreme-button" data-route="order/clear">Clear Cart</a>
				<a href="#" class="xtreme-button" data-route="order/review">Review My Order</a>
			</div>
		</div>
<?php } ?>
	</div>
</div>
<div class="deferred-content slide-left"></div>
<div id="on-close" class="true-hidden" data-action="unstash"></div>