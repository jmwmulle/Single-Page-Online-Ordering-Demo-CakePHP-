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
				<li class="icon-email inline"></li>
				<li class="icon-twitter inline"></li>
				<li class="icon-facebook inline"></li>
				<li class="icon-gplus inline"></li>
			</ul>
		</div>
		<div class="large-4 columns">
			<a href="#" class="top-bar-link modal-link" data-route="view_order">View Cart</a>
			<a href="#" class="top-bar-link modal-link" data-route="cart">Checkout</a>
		</div>
		<div class="large-4 columns">
			<ul id="hours-and-location">
				<li>
					Sun-Thurs: 11am - 3am&nbsp;&nbsp;&nbsp;&nbsp;
					<span class="header-red">Fri & Sat:</span> 11am-<span class="header-red">4am</span>
				</li>
				<li>902.404.1600</li>
				<li>5970 Spring Garden Road, Halifax, Nova Scotia</li>
			</ul>
		</div>
	</div>
</nav>