<?php
/**
 * J. Mulle, for app, 9/21/14 5:58 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */ ?>
<div id="loadingScreen" class="pane l-0">
	<section>
	<div id="load-messages-wrapper">
		<h1 id="loading-message">Hang tight—we're just getting our menus ready...</h1>
		<h1 id="ready-message" class="hidden">...and that about does it—all set!</h1>
	</div>
	<a href="#" id="dismiss-loading-screen" class="lrg rounded hidden yellow-on-black xtreme-button" onclick="XBS.layout.toggleLoadingScreen()">OK! LET ME IN!</a>
	<div id="pizza-loader-gif-wrapper">
		<?php echo $this->Html->Image("pizza_loader.gif", array("id" => "pizza-loader-gif"));?>
	</div>
	</section>
</div>