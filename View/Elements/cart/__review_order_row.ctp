<?php
/**
 * J. Mulle, for app, 7/15/15 8:26 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 * 
 *  $invoice = [subtotal, total, item_count, invoice_rows, hst]
 * 
 */ 
?>

<div class="row view-cart-row total">
	<div class="large-12 columns">
		<ul class="right">
			<?php foreach ($invoice as $key => $val) {?>
				<?=sprintf("<li>%s: <span class='$key normal' >%s</span></li>", ucfirst($key), money_format("%#3.2n",$val));?>
			<?php } ?>
		</ul>
	</div>
</div>