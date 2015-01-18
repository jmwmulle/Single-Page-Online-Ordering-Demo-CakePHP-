<?php
/**
 * J. Mulle, for app, 1/18/15 7:35 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<?php
	echo $this->Element('modal_masthead', array('header' => 'Order Confirmation',
	                                            'subheader' => 'Contacting a real person... might take a minute!'));?>
<div id="order-confirmation-spinner" class="row">
	<div class="large-8 large-centerd columns">
		<svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
			<circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>
		</svg>
	</div>
</div>