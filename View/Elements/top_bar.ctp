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

$logged_in = false;
$twitter_text = $logged_in ? "Tweet about Xtreme!" : "Login via Twitter";
$gplus_text = $logged_in ? "+1 Xtreme!" : "Login via GooglePlus";
$fb_text = $logged_in ? "Like Xtreme!" : "Login via Facebook";
$social_route = $logged_in ? "social" : "login";
?>

<nav id="topbar" class="text-center">
	<div class="row">
		<div class="large-3 small-12 columns text-center topbar-social">
			<div class="row">
				<div class="large-12 columns icon-row">
					<a href="#" data-route="topbar_link/<?php echo $social_route;?>/twitter" data-hover_text="<?php echo $twitter_text;?>">
						<span class="icon-twitter"></span>
					</a>
					<a href="#" data-route="topbar_link/<?php echo $social_route;?>/facebook" data-hover_text="<?php echo $fb_text;?>">
						<span class="icon-facebook"></span>
					</a>
					<a href="#" data-route="topbar_link/<?php echo $social_route;?>/gplus" data-hover_text="<?php echo $gplus_text;?>">
						<span class="icon-gplus g-plusone"></span>
					</a>
					<?php if (!$logged_in) {?>
					<a href="#" data-route="topbar_link/login/email" data-hover_text="Login With Your E-Mail Address">
						<span class="icon-topbar-email"></span>
					</a>
					<a href="#" data-route="topbar_link/sign-up" data-hover_text="Sign-Up To Save You Address & Favorites!">
						<span class="icon-topbar-sign-up"></span>
					</a>
					<?php }
					if ($logged_in) { ?>
					<a href="#" data-route="topbar_link/favorites" data-hover_text="View Your Favorites">
						<span class="icon-favorites"></span>
					</a>
					<a href="#" data-route="topbar_link/settings" data-hover_text="Account Settings">
						<span class="icon-settings"></span>
					</a>
					<?php }
					if ($this->Session->read('Cart')) {?>
					<a  id="top-bar-view-cart" href="#" data-route="view_order/topbar" data-hover_text="View Your Cart">
						<span class="icon-shopping"></span>
					</a>
					<?php } ?>
					<hr id="topbar-divider" />
				</div>
			</div>
			<div class="row">
				<div id="topbar-hover-text-label" class="large-12 columns text-center">
					<span class="incoming slide-left"></span>
					<span class="outgoing">Halifax loves pizza and we love halifax!</span>
				</div>
			</div>
		</div>
		<div class="large-4 large-push-1 columns">
			<h4 class="loading fade-out">LOADING</h4>
		</div>
		<div class="large-4 columns show-for-large-up">
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