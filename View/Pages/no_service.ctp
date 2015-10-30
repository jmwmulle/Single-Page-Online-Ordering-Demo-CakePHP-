<?php
/**
 * J. Mulle, for app, 2/3/15 3:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<div class="row">
	<div id="no-service" class="large-8 medium-6 small-4 large-centered medium-centerd small-centered columns panel">
		<div class="row">
			<div class="small-12 columns">
			<?php echo $this->Element('modal_masthead', array('header' =>  "Uh oh, We're Incompatible!",
                                                          'subheader' => "But that doesn't mean we can't be friends!"));?>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns user-info">
				<h5 class="text-center">Please accept our apologies, but our site currently won't work on your device or browser.</h5>
				<p>The reason you'll have been brought to this page is most likely because you're using an older browser or Internet Explorer, or that you're on a mobile device that doesn't support the new web technologies we use on our site. You could say we're a bit too... Xtreme! But of course we'd never want to deprive you, so here's a quick guide for getting yourself into the site:</p>
				<h4 class="expanded text-center">How To Get Access</h4>
				<h5>If you're on a mobile device:</h5>
				<p>Just visit our site on a laptop or desktop computer; simply view our site in "desktop mode" won't work,
				unfortunately, as our website uses current-generation standards that aren't completely mobile-ready. Don't worry, we'll have a mobile version of the site along soon enough!</p>

				<h5>If you're using Internet Explorer or other incompatible browser:</h5>
				<p>It is unlikely that our site will ever work fully for Internet Explorer as it does not comply with modern web standards, sorry! But you'll have no trouble accessing our site with the latest versions of:
				<ul>
					<li><?php echo $this->Html->link("Chrome", "http://chrome.google.com");?></li>
					<li><?php echo $this->Html->link("Firefox", "https://www.mozilla.org/en-US/firefox/desktop");?></li>
					<li><?php echo $this->Html->link("Safari", "https://www.apple.com/safari/");?></li>
					<li><?php echo $this->Html->link("Opera", "http://www.opera.com/");?></li>
				</ul>

				<p>We're working hard to bring our website to as broad an audience as possible, thanks for your patience!</p>
			</div>
		</div>
	</div>
</div>
