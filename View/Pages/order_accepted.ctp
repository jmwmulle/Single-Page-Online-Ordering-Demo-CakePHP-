<?php
/**
 * J. Mulle, for app, 1/25/15 11:48 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

echo $this->element('modal_masthead', array('header' => "Order accepted",
                                            'subheader' => 'In ancient times it took months to order pizza.'));
?>

<div class="row">
	<div class="large-8 large-centered columns">
		<h3>Good news everyone, your order is underway!</h3>
		<p>The Xtreme Team have received your order and are busying themselves preparing it.</p>
		<p>If your order was for <em>DELIVERY</em>, just sit tight and we'll be on our way.</p>
		<p>If your order was for <em>PICKUP</em>, we'll be expecting you, come on by!</p>
	</div>
</div>
<div class="row">
	<div class="large-12 columns">
		<a href="#" class="modal-button lrg full-width confirm">
			<span class="text">Great!</span><span class="icon-circle-arrow-r"></span>
		</a>
	</div>
</div>
<div id="on-arrival" class="true-hidden" data-action="no-close"></div>
<div id="on-close" class="true-hidden" data-action="unstash"></div>