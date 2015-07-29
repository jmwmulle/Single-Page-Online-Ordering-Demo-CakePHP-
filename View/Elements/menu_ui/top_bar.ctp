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

$auth_user =  $this->Session->read('Auth.User') ? true : false;
$auth_str = "http://development-xtreme-pizza.ca/auth/";
$login_route_str = "data-route='login/topbar/%s'";
$social_route_str = "data-route='social/%s'";

/*  Twitter Deets  */
$twitter_auth = sprintf($auth_str, 'twitter');
$twitter_text = $auth_user ? "Tweet about Xtreme!" : "Login via Twitter";
$twitter_icon = "<span class='icon-twitter'></span>";

/*  Google Deets  */
$google_icon = "<span class='icon-gplus'></span>";
$google_text = $auth_user ? "+1 Xtreme!" : "Login via GooglePlus";
$google_auth = sprintf($auth_str, 'google');

/* Facebook Deets  */
$fb_text = "Login via Facebook";
$fb_auth = sprintf($auth_str, 'facebook');
$fb_like_str = "http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fdevelopment-xtreme-pizza.ca&width&layout=button_count&action=recommend&show_faces=true&share=true&height=21";
$fb_icon = "<span class='icon-facebook'></span>";
$fb_data = array( 'href'       => "http://development-xtreme-pizza.ca",
                        'layout'     => 'button_count',
                        'action'     => 'like',
                        'show-faces' => 'false',
                        'share'      => 'false' ,
						'hover-text' => 'Like Xtreme!'
	);
/*  E-mail Deets */
$email_text = "Login With Your E-Mail Address";
$email_icon = "<span class='icon-topbar-email'></span>";


/*  Register Deets  */
$register_text = "Sign-Up To Save You Address & Favorites!";
$register_icon = "<span class='icon-topbar-sign-up'></span>";

/*  Favorites Deets  */
$favorites_icon = "<span class='icon-favorites'></span>";
$favorites_data = array('route' => 'topbar_link/favorites', 'text' => "View Your Favorites");

/*  Account Deets  */
$account_icon = "<span class='icon-settings'></span>";
$account_data = array('route' => 'topbar_link/settings', 'text' => "View Your Favorites");

/*  Cart Deets  */
$cart_css = !$this->Session->read('Cart.OrderItem') ?" class='' " : ' ';
$cart_data =  array('hover-text' => "View Your Cart", 'route' => 'order/view');
$cart_icon = "<span class='icon-shopping'></span>";


$social_route = $auth_user ? "social" : "login/topbar";
$social_live = false;
$auth_live = false;
?>

<nav id="topbar" class="text-center">
	<div class="row">
		<div class="large-4 small-12 columns text-center topbar-social third">
			<div class="row">
				<div id="social-loading" class="large-12 columns">
					<span class="one icon-full"></span>
					<span class="two icon-full"></span>
					<span class="three icon-full"></span>
				</div>
				<div class="large-12 columns icon-row fade-out true-hidden">
					<?php
						if (!$auth_user) {
							if ( $auth_live ) {
								echo sprintf( "<a href='%s' data-hover-text='%s'>%s</a>", $twitter_auth, $twitter_text, $twitter_icon );
								echo sprintf( "<a href='%s' data-hover-text='%s'>%s</a>", $fb_auth, $fb_text, $fb_icon );
								echo sprintf( "<a href='%s' data-hover-text='%s'>%s</a>", $google_auth, $google_text, $google_icon );
								echo sprintf( "<a href='#' data-hover-text='%s' data-route='login/topbar/email'>%s</a>", $email_text, $email_icon );
								echo sprintf( "<a href='#' data-hover-text='%s' data-route='register/topbar'>%s</a>", $register_text, $register_icon );
							}
						} else {
							if ( $social_live ) {
								echo sprintf( "<a href='%s' %s>%s</a>", ___dA($fb_data), $fb_icon );
							}
							if ( $auth_live ) {
								echo sprintf( "<a href='#' %s>%s</a>", ___dA($favorites_data), $favorites_icon );
								echo sprintf( "<a href='#' %s>%s</a>", ___dA($account_data), $account_icon );
							}

						}
						echo sprintf("<a id='top-bar-view-cart' href='#' %s %s> %s </a>", $cart_css, ___dA($cart_data), $cart_icon);
					?>
					<hr id="topbar-divider" />
				</div>
			</div>
			<div class="row">
				<div id="topbar-hover-text-label" class="large-12 columns text-center ">
					<span class="incoming slide-left"></span>
					<span class="outgoing">Halifax loves pizza and we love halifax!</span>
				</div>
			</div>
		</div>
		<div class="large-4 columns third"
			<div class="row">
				<?php
					$site_fucking_works = false;
					if ($site_fucking_works) {?>
				<div id="unknown-status" class="large-12 columns top-bar-status unknown true-hidden">
					<span>We're having trouble reaching the store just now, please call for delivery and confirmation that we're open.</span>
				</div>
				<div class="large-4 columns">
					<h3 class="top-bar-status-header">WE ARE:</h3>
				</div>
				<div class="large-8 columns">
					<span id="store-status" class="top-bar-status store"></span>
					<span id="delivery-status" class="top-bar-status delivery"></span>
				</div>
				<?php } else { ?>
						&nbsp;
				<?php }?>
			</div>
		<div class="large-4 columns show-for-large-up third">
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