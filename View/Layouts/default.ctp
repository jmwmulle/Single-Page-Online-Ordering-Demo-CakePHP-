<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css("app");
		echo $this->Html->script("/bower_components/modernizr/modernizr");
		echo $this->Html->script(array("lib/selectorManifest.js",
			"/bower_components/jquery/dist/jquery.min",
			"//code.jquery.com/ui/1.11.1/jquery-ui.js",
			"utilities",
			"/bower_components/foundation/js/foundation.min",
			"lib/cart",
			"lib/bootstrap",
			"application"), array('block' => 'app'));
	?>
<script type="text/javascript">

	var host = "<?php switch($_SERVER['HTTP_HOST']) {
					case "kleinlab.psychology.dal.ca":
						echo "xLoc";
						break;
					case "development-xtreme-pizza.ca":
						echo "xDev";
						break;
					default:
						echo "xProd";
						break;
					}?>";
	var is_splash = <?php echo ($this->get("is_splash")) ? 'true' : 'false';?>;
	var page_name = "<?php echo ($this->get("page_name")) ? $this->get("page_name") : "default"; ?>";
</script>
<?php $body_class = array("menu", $this->get("is_splash") ? "splash" : ""); ?>
</head>

<body <?php echo ___cD($body_class); ?>>
<?php echo $this->Element('feedback');?>
<?php
//echo $this->Element("loadingscreen");
//echo $this->Element("login");
if (!$this->get("is_splash")) echo $this->element("flash");//todo: perhaps turn this into both the topbar nav AND the flash area?
echo $this->Element("top_bar");
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
	echo sprintf("<script>var cart = %s;</script>", $this->Session->read('Cart') ? json_encode($this->Session->read('Cart')) : "{}");
	echo $this->fetch('content');
	echo $this->fetch('app');
	echo $this->fetch('main');
?>
<footer>
	<section class="pagespan">
		<div class="row">
			<div class="large-8 large-centered medium-12 small-12 columns">
			</div>
		</div>
	</section>
</footer>


</body>
</html>
