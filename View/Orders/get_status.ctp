<?php
/**
 * J. Mulle, for app, 1/18/15 7:35 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$r = 70;
	$d = 2 * $r;
	$vb = $d + 1;
	$c = $r + 1;
?>
<?php
	echo $this->Element('modal_masthead', array('header' => 'Order Confirmation',
	                                            'subheader' => 'Contacting a real person... might take a minute!'));?>
<div id="order-confirmation-spinner" class="row">
	<div id="load-dot-box" class="large-8 large-centered columns">
		<div class="row">
			<div class="large-12 columns text-left">
			<h5 class="text-center">Just letting the Xtreme Team know about your order.</h5>
			<p>This could take a minute; a real human being is making sure this order can be fulfilled. In just a moment
				we'll let you know if it's been accepted, and then you'll receive an e-mail as further confirmation.
				So please don't close your browser!</p>
			<p class="text-center">Thanks so much for your patience!</p>
		</div>
		</div>
		<div class="row">
			<div class="large-12 text-center columns">
				<div id="load-dot-1" class="modal-load-dot"></div>
				<div id="load-dot-2" class="modal-load-dot"></div>
				<div id="load-dot-3" class="modal-load-dot"></div>
				<div id="load-dot-4" class="modal-load-dot"></div>
			</div>
		</div>
	</div>
</div>