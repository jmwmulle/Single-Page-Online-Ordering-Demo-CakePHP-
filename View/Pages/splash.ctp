<main id="splash" class="pane loading">
	<div class="spacer"></div>
	<div class="content">
		<div id="splash-circle-wrapper" class="detach wrapper"  data-static="true">
			<div id="splash-circle" class="detach">
				<div id="order-wrapper" class="wrapper preserve-3d">
					<div class="spacer">&nbsp;</div>
					<?php $orderAttr = array("splash-redirect" => "pages/order", 'aspect-ratio' => array('x'=>380,'y'=>192, 'respect'=> 'y'));?>
					<a href="#" id="order" <?php //echo ___dA($orderAttr);?> data-route="order_method/splash/launch">&nbsp;</a>
				</div>
				<div id="splash-bar-wrapper" class="detach wrapper">
					<div id="splash-bar" class="detach">
						<div class="spacer">&nbsp;</div>
						<div id="splash-logo-wrapper">
							<div id="splash-logo" class="preserve-aspect-ratio" <?php echo ___dA(array('aspect-ratio' => array('x'=>552,'y'=>274, 'respect'=> 'y')));?>></div>
						</div>
					</div>
				</div>
				<div id="menu-wrapper" class="wrapper preserve-3d">
					<?php $menuAttr = array("route" => "splash_link/menu", 'aspect-ratio' => array('x'=>380,'y'=>192, 'respect'=> 'y'));?>
					<div class="spacer"></div>
					<a href="#" id="menu" <?php echo ___dA($menuAttr);?>>&nbsp;</a>
				</div>
			</div>
		</div>
	</div>
</main>
