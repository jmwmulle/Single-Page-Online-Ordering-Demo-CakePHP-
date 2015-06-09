<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza</title>

	<?php
		$vendor_scripts = array(
			"/bower_components/jquery/dist/jquery.min",
			"//code.jquery.com/ui/1.11.1/jquery-ui.js",
			"/bower_components/foundation/js/foundation.min",
			"vendor/jquery.validate.min",
			"vendor/additional-methods.min" );
		$xbs_scripts = array( "utilities",
		                      "lib/XSM.js",
		                      "lib/XCL",
		                      "lib/Route",
		                      "lib/Printer",
		                      "lib/xbs_vendor_ui",
		                      "lib/xbs_data",
		                      "lib/xbs_routing",
		                      "lib/xbs_layout",
		                      "lib/xbs_menu",
		                      "lib/xbs_cart",
		                      "lib/xbs_splash",
		                      "lib/xbs_validation",
		                      "lib/xbs_vendor",
		                      "lib/XBS",
		                      "application" );
		echo $this->Html->meta( 'icon' );
		echo $this->Html->css( "app" );
		echo $this->Html->script( "/bower_components/modernizr/modernizr" );
		echo $this->Html->script( $vendor_scripts, array( 'block' => 'vendor' ) );
		echo $this->Html->script( $xbs_scripts, array( 'block' => 'app' ) );
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
		var store_status = <?php echo $this->get('store_status') ? $this->get('store_status') : "{reachable:false, delivering:false, time:0}"; ?>;
		var is_splash = <?php echo ($this->get("is_splash")) ? 'true' : 'false';?>;
		var page_name = "<?php echo ($this->get("page_name")) ? $this->get("page_name") : "default"; ?>";
		var vendor_ui = false;
	</script>
	<?php
		$body_id = $this->get( 'page_name' );
		$body_class = array( "menu", $this->get( "is_splash" ) ? "splash" : "" ); ?>
</head>

<body id="<?php echo $body_id; ?>" data-poop="true" <?php echo ___cD( $body_class ); ?>>

<div id="page-content" class="show-for-medium-up">
	<?php
		echo sprintf( "<script>var cart = %s;</script>", $this->Session->read( 'Cart' ) ? json_encode( $this->Session->read( 'Cart' ) ) : "{}" );
		echo $this->fetch( 'content' );
	?>
</div>
<?php
	echo $this->Element( 'footer' );
	echo $this->fetch( 'vendor' );
	echo $this->fetch( 'app' );
	echo $this->fetch( 'main' );
	echo $this->fetch( 'debug' );
?>
<script>
/*	window.___gcfg = {
		lang: 'zh-CN',
		parsetags: 'onload'
	}; */
</script>
</body>
</html>

