<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>XtremePizza</title>
	<script type="text/javascript">
		if ( window.xtr === undefined) window.xtr = {};
		var XT = window.xtr;
		XT.host = "<?php switch($_SERVER['HTTP_HOST']) {
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
		XT.store_status = <?php echo $this->get('store_status') ? $this->get('store_status') : "{reachable:false, delivering:false, time:0}"; ?>;
		XT.is_splash = <?php echo ($this->get("is_splash")) ? 'true' : 'false';?>;
		XT.page_name = "<?php echo ($this->get("page_name")) ? $this->get("page_name") : "Xtreme Menu"; ?>";
		XT.vendor_ui = false;
		XT.route_collections = {};

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
			"jsnes/jsnes.min",
		                 "dynamic_audio",
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
		                      /*"$gulp_js_path/app" */];
		echo $this->Html->meta( 'icon' );
		echo $this->Html->css( "app" );
		echo $this->Html->script( "/bower_components/modernizr/modernizr" );
		echo $this->Html->script( $vendor_scripts, array( 'block' => 'vendor' ) );
		echo $this->Html->script( $xbs_scripts, array( 'block' => 'app' ) );
//		echo $this->Html->script( "xtreme", array( 'block' => 'app' ) );
	?>

	<?php
		$body_id = $this->get( 'page_name' );
		$body_class = array( "menu", $this->get( "is_splash" ) ? "splash" : "" ); ?>
</head>

<body id="<?php echo $body_id; ?>" data-poop="true" <?php echo ___cD( $body_class ); ?>>
<div id="page-content" class="show-for-medium-up">
	<?=$this->Html->image('puff.svg', ['id' => 'loading-img', 'class'=> ['fade-out','hidden'] ]);?>
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
<div id="emulator"></div>
<script>

/*	window.___gcfg = {
		lang: 'zh-CN',
		parsetags: 'onload'
	}; */

</script>
</body>
</html>

