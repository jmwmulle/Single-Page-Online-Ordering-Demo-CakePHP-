<?php
/**
 * J. Mulle, for app, 2/1/15 4:59 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<?php echo $this->Element('modal_masthead', array('header' => "Just to let you know",
                                                  'subheader' => "Our Online Ordering is Temporarily Out-of-Order"));?>
<div class="row">
	<div class="large-12 columns">
		<p>Welcome to our new site! Unfortunately, it's still got a few bugs. :( </p>
		<p>Please feel free to browse our menu at your leisure and price your order, but for now you'll need to give us a call to place it.</p>
		<p>Thanks for your patience while we work out the kinks, we're very excited to be bringing you on-line ordering and
		will have it running smoothly in soon.</p>
		<h5>Enjoy exploring our site, thanks for coming by!</h5>
		<a href="#" class="full-width confirm modal-button" data-route="launch_apology/close">
			<span class="text">Thanks for the head's up!</span>
			<span class="icon-circle-arrow-r"></span>
		</a>
	</div>
</div>
