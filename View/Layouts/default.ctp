<!doctype html>
<html class="no-js" lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>XtremePizza</title>
	<script src="https://apis.google.com/js/platform.js" async defer></script>
<!--	<script>  {"parsetags": "explicit"}</script>-->
	<?php
		echo $this->Html->meta( 'icon' );
		echo $this->Html->css( "app" );
		echo $this->Html->script( "/bower_components/modernizr/modernizr" );
		echo $this->Html->script( array( "lib/selectorManifest.js",
		                                 "lib/constants",
		                                 "/bower_components/jquery/dist/jquery.min",
		                                 "//code.jquery.com/ui/1.11.1/jquery-ui.js",
		                                 "utilities",
		                                 "/bower_components/foundation/js/foundation.min",
		                                 "lib/cart",
		                                 "lib/route",
		                                 "lib/bootstrap",
		                                 "vendor/jquery.validate.min",
		                                 "vendor/additional-methods.min",
		                                 "application"
			), array( 'block' => 'app' )
		);
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
	<?php $body_class = array( "menu", $this->get( "is_splash" ) ? "splash" : "" ); ?>
</head>

<body <?php echo ___cD( $body_class ); ?>>
<?php
	echo $this->Element( 'feedback' );
	echo $this->Element( "top_bar" );
?>
<div id="page-content">
<?php
	echo sprintf( "<script>var cart = %s;</script>", $this->Session->read( 'Cart' ) ? json_encode( $this->Session->read( 'Cart' ) ) : "{}" );
	echo $this->fetch( 'content' );
?>
</div>
<?php
	echo $this->Element( 'footer' );
	echo $this->fetch( 'app' );
	echo $this->fetch( 'main' );
?>
<script >
  window.___gcfg = {
    lang: 'zh-CN',
    parsetags: 'onload'
  };
</script>
</body>
</html>

