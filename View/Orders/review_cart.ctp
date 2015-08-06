<?php
	$header = "Mmmm... Cart Contents";
	$subheader = "Couldn't have chosen better ourselves!";
?>

<?=$this->element("primary_modal/masthead", compact("header","subheader"));?>
<div class="row">
	<div class="large-12 columns default-content">
		<div class="row<?php if ( !empty($cart[ 'Order' ]) ) echo " true-hidden";?>">
			<div id="empty-cart-message" class="large-8 large-centered columns">
					<p>Well, "nothing" probably won't make for a satisfying meal.</p>
					<p>But on the upside it's free?</p>
			</div>
		</div>
		<div class="row<?php if ( empty($cart[ 'Order' ]) ) echo " true-hidden";?>">
			<div class="large-3 columns end">
				<a href="#" class="cancel left tiny" data-route="cart/clear">
					<span class="icon-cancel icon"></span>
					<span class="text">Clear Cart</span>
				</a>
			</div>
		</div>
		<div class="row view-cart-row cart-header <?php if ( empty($cart[ 'Order' ]) ) echo " true-hidden";?>">
			<div class="large-7 columns"><span class="cart-row-item-title">ITEM</span></div>
			<div class="large-2 columns text-center"><span>QUANTITY</span></div>
			<div class="large-2 columns"><span>PRICE</span></div>
			<div class="large-1 columns text-center">&nbsp;</div>
		</div>

		<?php foreach ($cart[ 'Order' ] as $uid => $oi) echo $this->element("cart/review_cart_row", compact("uid", "oi"));?>

		<div class="row">
			<div class="large-12 modal-nav columns">
				<a href="#" class="modal-button bisecting cancel left" data-route="menu/unstash">
					<span class="icon-circle-arrow-l icon"></span><span class="text">Continue Ordering</span>
				</a>
				<a href="#" class="modal-button bisecting confirm right<?php if ( empty($cart['Order']) ) echo " disabled";?>" data-route="order/review">
					<span class="text">Review My Order</span><span class="icon-circle-arrow-r icon"></span>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="deferred-content slide-left"></div>
<div id="on-close" class="true-hidden" data-action="unstash"></div>