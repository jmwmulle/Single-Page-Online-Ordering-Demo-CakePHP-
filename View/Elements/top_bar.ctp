<?php
/**
 * J. Mulle, for app, 9/3/14 9:58 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus

 * topNav element

 * sample data
 * $navopts = (array) <<topnav opts, not expected to change, but who can know? >>
 * $here = (str) << currently viewed page,  must exactly match one of the navopts >>
 */

?>

<nav id="topbar" class="text-center">
	<div class="row">
		<div class="large-4 columns">
			<ul id="top-bar-social-icons" class="horizontal">
				<li class="social-icon imgur"></li>
				<li class="social-icon tumblr"></li>
				<li class="social-icon twitter"></li>
				<li class="social-icon email"></li>
				<li class="social-icon pinterest"></li>
				<li class="social-icon facebook"></li>
				<li class="social-icon gplus"></li>
			</ul>
		</div>
		<div class="large-1 columns text-center"><h3>OPEN</h3></div>
		<div class="large-2 columns text-center splash-logo">&nbsp</div>
		<div class="large-1 columns text-center"><h3>DELIVERING</h3></div>
		<div class="large-4 columns">
			<ul id="hours-and-location">
				<li>
					Sun-Thurs: 11am - 3am&nbsp;&nbsp;&nbsp;&nbsp;
					<span class="header-red">Fri & Sat:</span> 11am-<span class="header-red">4am</span>
				</li>
				<li>902.404.1600</li>
				<li>1234 Coburg Road, Halifax, Nova Scotia</li>
			</ul>
		</div>
	</div>
</nav>