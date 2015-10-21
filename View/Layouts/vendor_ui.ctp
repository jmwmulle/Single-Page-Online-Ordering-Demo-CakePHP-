<?php
/**
 * J. Mulle, for app, 2/2/15 6:05 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza</title>
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
		window.xtr.is_vendor_ui = true;
		window.xtr.route_collections = {};

	</script>
	<?php
		$vendor_scripts = array(
				    "vendor/complete",
					"/bower_components/jquery/dist/jquery.min",
					"vendor/jquery.jquery-ui",
					"/bower_components/foundation/js/foundation.min",
				    "vendor/jquery.dataTables.min",
					"vendor/jquery.validate.min",
				    "vendor/jquery.dataTables.editable",
				    "vendor/jquery.dataTables.rowGrouping",
				    "vendor/jquery.form",
				    "vendor/ColReorder",
				    "vendor/ColVis",
					"vendor/additional-methods.min",
				);
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
				                      "$gulp_js_path/app" ];
			echo $this->Html->meta( 'icon' );
//			echo $this->Html->css( "app" );
			echo $this->Html->css( [ "jquery-ui.min", "vendor" ] );
			echo $this->Html->script( "/bower_components/modernizr/modernizr" );
			echo $this->Html->script( $vendor_scripts, [ 'block' => 'vendor' ] );
			echo $this->Html->script( $xbs_scripts, [ 'block' => 'app' ] );
	?>
	<?php
		$body_id = $this->get( 'page_name' );
		$body_class = array( "menu", $this->get( "is_splash" ) ? "splash" : "" ); ?>
</head>
<body>
<div id="loading-screen">
	<div id="loading-screen-message">
		<h2>Loading...</h2>
		<?=$this->Html->image('ajax-loader.gif');?>
	</div>
</div>
<?=$this->Html->image('puff.svg', ['id' => 'loading-img', 'class'=> ['fade-out','hidden'] ]);?>
<?php
echo $this->fetch('content');
echo $this->fetch( 'vendor' );
echo $this->fetch( 'app' );
//echo $this->fetch( 'main' );
?>
<div id="primary-modal" class="slide-up">
	<div class="row">
		<div id="primary-modal-content" class="large-12 columns"></div>
		<a href="#" id="close-modal" data-route="close_modal/primary" class="box rightward"><span>CANCEL</span><span class="icon-cancel icon-hn-inline"></span></a>
	</div>
</div>

</body>
</html>