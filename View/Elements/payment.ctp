<?php
/**
 * J. Mulle, for app, 1/8/15 5:45 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
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