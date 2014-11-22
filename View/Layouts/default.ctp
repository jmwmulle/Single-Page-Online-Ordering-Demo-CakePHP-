<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza - <?php echo $title_for_layout;?></title>
	<?php
		echo $this->Html->meta('icon');
		//echo $this->Html->css(array("http://fonts.googleapis.com/css?family=Fredericka+the+Great", "http://fonts.googleapis.com/css?family=Indie+Flower","http://fonts.googleapis.com/css?family=Mouse+Memoirs"));
		echo $this->Html->css(array("/bower_components/foundation/css/normalize",'app'));
		echo $this->Html->script("/bower_components/modernizr/modernizr");
		echo $this->Html->script(array("lib/selectorManifest.js",
			"/bower_components/jquery/dist/jquery.min",
			"//code.jquery.com/ui/1.11.1/jquery-ui.js",
//			"vendor/jquery-image-loader",
//			"vendor/jquery.cacheImages.min",
			"utilities",
			"/bower_components/foundation/js/foundation.min",
			"lib/bootstrap",
			"application"), array('block' => 'app'));
	?>
<script type="text/javascript">

	var host = "<?php switch($_SERVER['HTTP_HOST']) {
					case "kleinlab.psychology.dal.ca":
						echo "xLoc";
						break;
					case "www.development-xtreme-pizza.ca":
						echo "xDev";
						break;
					default:
						echo "xProd";
						break;
					}?>";
	var isSplash = <?php echo ($this->get("isSplash")) ? 'true' : 'false';?>;
</script>
</head>
<body id="menu">
<?php //echo $this->Element("loadingscreen");?>
<?php //echo $this->Element("login");?>
<?php if (!$this->get("isSplash")) echo $this->element("flash");//todo: perhaps turn this into both the topbar nav AND the flash area? ?>
<?php $this->start('main');?>
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
		<div class="large-2 columns text-center"><?php echo $this->Html->image('splash/logo_mini.png');?></div>
		<div class="large-1 columns text-center"><h3>DELIVERING</h3></div>
		<div class="large-4 columns">
		<span class="header-thin block">Sun-Thurs: 11am - 3am&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="header-red">Fri & Sat:</span> 11am-<span class="header-red">4am</span>
		</span>
		<span class="header-thin block">
			1234 Coburg Road, Halifax, Nova Scotia | 902.404.1600
		</span>
		</div>
	</div>
</nav>
<main id="menu">
	<div class="row">
		<div class="large-12 columns">
			<ul id="user-activity-panel" class="show-for-large-up vertical text-center activizing">
				<li><h2 class="body-font-color">I AM</h2></li>
				<li class="inactive"><a class="body-font-color block">Just<br />Browsing</a></li>
				<li class="inactive"><a class="body-font-color block">Ordering<br />(Delivery)</a></li>
				<li class="inactive"><a class="body-font-color block">Ordering<br />(Pick-Up)</a></li>
			</ul
			<?php echo $this->fetch('orbcats_menu'); ?>
		</div>
	</div
	><div class="row">
		<div class="large-12 medium-8 small-4 columns">
			<div id="orb-card-wrapper" class="float-pane">
				<?php echo $this->fetch('active_orb_card');?>
				<?php echo $this->fetch('active_orbs_menu');?>
			</div>
		</div>
	</div>
</main>
<footer>
	<section class="pagespan">
		<div class="row">
			<div class="large-8 large-centered medium-12 small-12 columns">
			</div>
		</div>
	</section>
</footer>





<?php
	$this->end('main');
	$img = array("splash/bluecircle.png",
				"splash/deal.png",
				"splash/email_icon.png",
				"splash/email_icon.hover.png",
				"splash/facebook_icon.png",
				"splash/facebook_icon.hover.png",
				"splash/twitter_icon.png",
				"splash/twitter_icon.hover.png",
				"splash/gplus_icon.png",
				"splash/gplus_icon.hover.png",
				"splash/logo.png",
				"splash/logo_mini.png",
				"splash/menu.png",
				"splash/menu_hover.png",
				"splash/order.png",
				"splash/order_soon.png",
				"splash/pizza-bg.jpg",
				"splash/logo_mini.png");?>
	<div id="image-loading-queue" class="hidden">
<?php
	foreach ($img as $i) {
		true;
		//echo $this->Html->Image("#", array("alt"=>"image-loader-object","data-src"=> "/xtreme".DS."img".DS.$i, "class" => "image-loader preload"));
	}
?></div>
<?php
	if ($this->get('isSplash')) echo $this->fetch('content');
	echo $this->fetch('main');
	echo $this->fetch('app');
?>
</body>
</html>
