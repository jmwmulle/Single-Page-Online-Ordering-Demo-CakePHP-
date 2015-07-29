<?php echo  $this->Element( "menu_ui/top_bar" ); ?>
<main id="splash" class="pane loading">
	<div class="spacer"></div>
	<div class="content">
		<div id="splash-circle-wrapper" class="detach wrapper"  data-static="true">
			<div id="splash-circle" class="detach">
				<div id="order-wrapper" class="wrapper">
					<div class="spacer">&nbsp;</div>
					<?php $orderAttr = array("splash-redirect" => "pages/order", 'aspect-ratio' => array('x'=>380,'y'=>192, 'respect'=> 'y'));?>
					<a href="#" id="order" <?php //echo ___dA($orderAttr);?> data-route="splash_order">&nbsp;</a>
				</div>
				<div id="splash-bar-wrapper" class="detach wrapper">
					<div id="splash-bar" class="detach">
						<div id="splash-bar-content" class="text-center"
							><div id="splash-order-delivery-wrapper">
								<a id="splash-order-delivery" href="#" class="slide-left"  data-route="order_method/splash/delivery">Delivery</a>
							</div
							><div id="splash-logo-wrapper">
								<div id="splash-logo" data-route='launch_apology' class="preserve-aspect-ratio" <?php echo ___dA(array('aspect-ratio' => array('x'=>552,'y'=>274, 'respect'=> 'y')));?>></div>
							</div
							><div id="splash-order-pickup-wrapper">
								<a id="splash-order-pickup" href="#" class="slide-right" data-route="order_method/splash/pickup">Pick-Up</a>
							</div
						></div>
					</div>
				</div>
				<div id="menu-wrapper" class="wrapper preserve-3d">
					<?php $menuAttr = array("route" => "menu", 'aspect-ratio' => array('x'=>380,'y'=>192, 'respect'=> 'y'));?>
					<div class="spacer"></div>
					<a href="#" id="menu" <?php echo ___dA($menuAttr);?>>&nbsp;</a>
				</div>
			</div>
		</div>
	</div>
	<div id="splash-top-logo"></div>
</main>
