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

$logged_in =  $this->Session->read('Auth.User') ? true : false;
$logged_in = true;
$twitter_text = $logged_in ? "Tweet about Xtreme!" : "Login via Twitter";
$gplus_text = $logged_in ? "+1 Xtreme!" : "Login via GooglePlus";
$fb_text = $logged_in ? "Like Xtreme!" : "Login via Facebook";
$social_route = $logged_in ? "social" : "login/topbar";
?>

<nav id="topbar" class="text-center">
	<div class="row">
		<div class="large-5 small-12 columns text-center topbar-social">
			<div class="row">
				<div id="social-loading" class="large-12 columns">
					<span class="one icon-full"></span>
					<span class="two icon-full"></span>
					<span class="three icon-full"></span>
				</div>
				<div class="large-12 columns icon-row fade-out true-hidden">
					<?php if (!$logged_in) { ?>
					<a href="<?php echo $logged_in ? "#" : "http://development-xtreme-pizza.ca/auth/twitter";?>" data-hover_text="<?php echo $twitter_text;?>">
						<span class="icon-twitter"></span>
					</a>
					<?php } else { ?>
					<a href="https://twitter.com/share" class="twitter-share-button" data-text="XtremePizza! Delicious! Best online-ordering in #Halifax!" data-hashtags="XtremePizza,XtremePizzaHalifax">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					<?php } ?>
					<?php if (!$logged_in) { ?>
					<a href="<?php if ($logged_in) {
						echo "http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fdevelopment-xtreme-pizza.ca&width&layout=button_count&action=recommend&show_faces=true&share=true&height=21";
					} else { echo "http://development-xtreme-pizza.ca/auth/facebook";}?>" data-hover_text="<?php echo $fb_text;?>">
						<span class="icon-facebook"></span>
					</a>
					<?php } else { ?>
					<div class="fb-like" data-href="http://development-xtreme-pizza.ca" data-width="48px" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
<!--						<fb:like href="http://development-xtreme-pizza.ca" layout="button_count" action="recommend" show_faces="true" share="true"></fb:like>-->
					<?php } ?>
					<a href="<?php echo $logged_in ? "#" : "http://development-xtreme-pizza.ca/auth/google";?>"
					   <?php if ($logged_in) echo "data-route='social/google'";?> data-hover_text="<?php echo $gplus_text;?>">
						<span class="icon-gplus  g-plusone"></span>
					</a>
					<?php if (!$logged_in) {?>
					<a href="#" data-route="login/topbar/email" data-hover_text="Login With Your E-Mail Address">
						<span class="icon-topbar-email"></span>
					</a>
					<a href="#" data-route="register/topbar" data-hover_text="Sign-Up To Save You Address & Favorites!">
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
					<?php }?>
					<a  id="top-bar-view-cart" href="#"
						<?php if (!$this->Session->read('Cart.OrderItem')) echo "style='display:none;' class='fade-out' ";?>
					    data-route="order/view" data-hover_text="View Your Cart">
						<span class="icon-shopping"></span>
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
		<div class="large-3 large-push-1 columns">
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