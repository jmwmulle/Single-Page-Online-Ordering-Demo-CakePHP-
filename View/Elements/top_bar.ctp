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
$email_text = $logged_in ? "" : "Login via E-mail";
$cart_text = $this->Session->read('Cart') ? "View Your Cart" : "";
$settings_text = $logged_in ? "Account Settings" : "";
$favorites_text = $logged_in ? "View Your Favorites" : "";
?>

<nav id="topbar" class="text-center">
	<div class="row">
		<div class="large-3 small-12 columns text-center topbar-social">
			<div class="row">
				<div class="large-12 columns icon-row">
					<a href="#" data-route="topbar_social/twitter" data-hover_text="<?php echo $twitter_text;?>">
						<span class="icon-twitter"></span>
					</a>
					<a href="#" data-route="topbar_social/facebook" data-hover_text="<?php echo $fb_text;?>">
						<span class="icon-facebook"></span>
					</a>
					<a href="#" data-route="topbar_social/gplus" data-hover_text="<?php echo $gplus_text;?>">
						<span class="icon-gplus"></span>
					</a>
					<a href="#"<?php if ($logged_in) echo "class='disabled' ";?>data-route="topbar_social/email" data-hover_text="<?php echo $email_text;?>">
						<span class="icon-original-pizzas"></span>
					</a>
					<a  id="top-bar-view-cart" href="#"<?php if (!$this->Session->read('Cart')) echo "class='disabled' ";?>data-route="view_order/topbar" data-hover_text="<?php echo $cart_text;?>">
						<span class="icon-shopping"></span>
					</a>
					<a href="#"<?php if (!$logged_in) echo "class='disabled'";?>data-route="topbar_favorites" data-hover_text="<?php echo $favorites_text;?>">
						<span class="icon-favorites"></span>
					</a>
					<a href="#"<?php if (!$logged_in) echo "class='disabled'";?>data-route="topbar_settings" data-hover_text="<?php echo $settings_text;?>">
						<span class="icon-settings"></span>
					</a>
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