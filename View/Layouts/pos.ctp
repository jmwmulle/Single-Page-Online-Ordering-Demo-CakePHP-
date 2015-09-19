<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza - Vendor</title>
	<link href='https://fonts.googleapis.com/css?family=Bangers' rel='stylesheet' type='text/css'>
	<script type="text/javascript">
		if ( window.xtr === undefined) window.xtr = {};
		var XT = window.xtr;
		window.xtr.host = "<?php switch($_SERVER['HTTP_HOST']) {
					case "www.xtreme-pizza.ca":
						echo "xProd";
						break;
					case "xtreme-pizza.ca":
							echo "xProd";
							break;
					case "development-xtreme-pizza.ca":
						echo "xDev";
						break;
					default:
						echo "xLoc";
						break;
					}?>";
		window.xtr.store_status = <?php echo $this->get('store_status') ? $this->get('store_status') : "{reachable:false, delivering:false, time:0}"; ?>;
		window.xtr.is_splash = <?php echo ($this->get("is_splash")) ? 'true' : 'false';?>;
		window.xtr.page_name = "<?php echo ($this->get("page_name")) ? $this->get("page_name") : "Xtreme Menu"; ?>";
		window.xtr.vendor_ui = false;
		window.xtr.route_collections = {};

	</script>
	<?php
		$vendor_scripts = [
			"/bower_components/jquery/dist/jquery.min",
			"//code.jquery.com/ui/1.11.1/jquery-ui.js",
			"/bower_components/foundation/js/foundation.min",
			"vendor/jquery.validate.min",
			"vendor/additional-methods.min" ];
		$gulp_js_path = "../gulp_files/js/Xtreme";
		$xbs_scripts = [
//			"jsnes/jsnes.min",
//		                 "dynamic_audio",
		                 "$gulp_js_path/utilities",
		                      "$gulp_js_path/data/XSM",
		                      "$gulp_js_path/data/XCL",
		                      "$gulp_js_path/data/xtreme_data",
		                      "$gulp_js_path/routing/collections/layout_api",
		                      "$gulp_js_path/routing/collections/menu_ui",
		                      "$gulp_js_path/routing/collections/orders_api",
		                      "$gulp_js_path/routing/collections/pos_api",
		                      "$gulp_js_path/routing/collections/user_accounts",
		                      "$gulp_js_path/routing/collections/vendor_ui",
		                      "$gulp_js_path/routing/XtremeRoute",
		                      "$gulp_js_path/routing/XtremeRouter",
		                      "$gulp_js_path/structure/OrbcardMenu",
		                      "$gulp_js_path/structure/Orbcard",
		                      "$gulp_js_path/structure/XtremeLayout",
		                      "$gulp_js_path/structure/XtremeMenu",
		                      "$gulp_js_path/model/XtremeCart",
		                      "$gulp_js_path/model/Orbopt",
		                      "$gulp_js_path/model/Orbopt",
		                      "$gulp_js_path/model/Optflag",
		                      "$gulp_js_path/model/Orb",
		                      "$gulp_js_path/structure/Modal",
		                      "$gulp_js_path/structure/xbs_splash",
		                      "$gulp_js_path/structure/XtremePOS",
		                      "$gulp_js_path/structure/xbs_vendor_ui",
		                      "$gulp_js_path/xbs_validation",
		                      "$gulp_js_path/Printer",
		                      "$gulp_js_path/EffectChain",
		                      "$gulp_js_path/XBS",
		                      "$gulp_js_path/exceptions",
		                      "$gulp_js_path/app" ];
		echo $this->Html->meta( 'icon' );
		echo $this->Html->css( "app" );
//		echo $this->Html->css( "vendor" );
		echo $this->Html->script( "/bower_components/modernizr/modernizr" );
		echo $this->Html->script( $vendor_scripts, array( 'block' => 'vendor' ) );
		echo $this->Html->script( $xbs_scripts, array( 'block' => 'app' ) );
//		echo $this->Html->script( "xtreme", array( 'block' => 'app' ) );
	?>
</head>

<body id="pos">

<?php
echo $this->fetch( 'content' );
echo $this->fetch( 'vendor' );
echo $this->fetch( 'app' );
echo $this->fetch( 'main' );
echo $this->fetch( 'debug' );
?>
</body>
</html>
