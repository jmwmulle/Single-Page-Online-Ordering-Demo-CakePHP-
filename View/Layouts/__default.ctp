<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza - <?php echo $title_for_layout;?></title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css(array("http://fonts.googleapis.com/css?family=Fredericka+the+Great", "http://fonts.googleapis.com/css?family=Indie+Flower","http://fonts.googleapis.com/css?family=Mouse+Memoirs"));
		echo $this->Html->css(array("/bower_components/foundation/css/normalize",'app'));
		echo $this->Html->script("/bower_components/modernizr/modernizr");
		echo $this->Html->script(array("lib/selectorManifest.js",
			"/bower_components/jquery/dist/jquery.min",
			"//code.jquery.com/ui/1.11.1/jquery-ui.js",
			"vendor/jquery-image-loader",
			"vendor/jquery.cacheImages.min",
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
	var isSplash = <?php echo ($this->get("is_splash")) ? 'true' : 'false';?>;
</script>
</head>
<body>
<?php echo $this->Element("loadingscreen");?>
<?php if (!$this->get("is_splash")) echo $this->element("flash");//todo: perhaps turn this into both the topbar nav AND the flash area? ?>
	<nav id="login">
		<h2 class="text-center">Sign In!</h2>
		<ul id="login-options">
			<li class="facebook" data-splash-modal data-source="orbs/add"></li>
			<li class="google" data-splash-modal data-source="auth/google"></li>
			<li class="twitter" data-splash-modal data-source="auth/twitter"></li>
			<li class="email" data-splash-modal data-source="users/add"></li>
		</ul>
	</nav>
<?php $this->start('main');?>
	<nav id="subnav-toc" class="container">
		<?php echo $this->fetch('toc');?>
	</nav>
	<nav id="subnav" class="xtreme-blue" data-scroll-target>
	<?php echo $this->fetch('subnav');?>
	</nav>
	<main id="menu" class="loading" >
		<div class="row" >
			<div class="large-12 columns">
				<div class="row">
					<nav id="topnav" class="large-12 columns">
						<?php echo $this->fetch('topnav');?>
					</nav>
					<section class="content">
						<div class="large-12 columns">
							<div>
								<?php $scrollUpAttr = array('scroll' => 'parent', 'scroll-direction' => 'up');
									  $scrollDownAttr = array('scroll' => 'parent', 'scroll-direction' => 'down');?>
								<nav class="scroll-area up" <?php echo ___dA($scrollUpAttr);?>></nav>
									<div id="primary-content" class="large-12 columns content-area" __data-scrolling-target data-initial-offset="0">
										<?php if (!$this->get('is_splash') ) echo $this->fetch('content');?>
									</div>
								<nav class="scroll-area down" <?php echo ___dA($scrollDownAttr);?>></nav>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</main>

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
	<div id="image-loading-queue" style="display:none;">
<?php
	foreach ($img as $i) {
		echo $this->Html->Image("#", array("alt"=>"image-loader-object","data-src"=> "/xtreme".DS."img".DS.$i, "class" => "image-loader preload"));
	}
?></div>
<?php
	if ($this->get('is_splash')) echo $this->fetch('content');
	echo $this->fetch('main');
	echo $this->fetch('app');
?>
</body>
</html>
