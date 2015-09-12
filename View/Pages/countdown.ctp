<?php
/**
 * J. Mulle, for app, 9/8/15 7:45 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>

<div class="row">
	<div id="mobile-countdown" class="show-for-small-only small-10 small-centered columns">
		<p>Our mobile site will follow the launch of our desktop site. For now you can visit us on your laptop, desktop or large-format tablet.</p>
		<p>But that doesn't mean you're out of luck; you can also order on-line from Xtreme Pizza via <a href="http://www.just-eat.ca">JustEat</a></p>
		<h3>Thanks for visiting!</h3>
	</div>
</div>
<div class="row">
	<div id="countdown" class="show-for-medium-up medium-12 large-12 columns">
		<div class="row">
			<div class="small-6 small-centered columns text-center">
				<?=$this->Html->image("countdown/logo.svg", ['id' => 'countdown-logo']);?>
			</div>
		</div>
		<div class="row">
			<div class="small-12 small-centered columns text-center">
				<hr class="glow">
			</div>
		<div class="row">
			<div class="small-12 small-centered columns text-center">
				<h1 class="shadowed">ONLINE ORDERING BEGINS IN</h1>
				<div id="countdown-clock-wrapper" class="small-7 small-centered columns">
					<div id="countdown-clock"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="special-box" class="small-12 small-centered columns">
				<h2 class="make-snazzy">FREE&nbsp;</h2><h2 class="no-snazz">medium garlic fingers with <span class="underline">ANY</span> pizza order over $10.00<span class="superscript">*</span>.</h2>
				<p><span class="superscript">*</span>before taxes</p>
			</div>
			<p class="banner">Launch Special</p>
		</div>
	</div>
</div>