<section id="splash" class="pane loading">
	<div id="splash-modal-wrapper" >
		<div id="splash-modal">
			<h1></h1>
			<div class="content">
			<?php $this->fetch('flash'); ?>
			</div>
			<a class="close-splash-modal">&#215;</a>
		</div>
	</div>
	<div id="grand-opening-deal" data-splash-modal data-source="register">
<!--		<div>-->
<!--			<h3>Join With:-->
<!--				<ul id="deal-button-panel">-->
<!--				<li class="facebook"></li>-->
<!--				<li class="gplus"></li>-->
<!--				<li class="twitter"></li>-->
<!--				<li class="email"></li>-->
<!--				</ul>-->
<!--			</h3>-->
<!--			<h4>Free Medium<br />Garlic Fingers</h4>-->
<!--			<p>When you first order online by signing up for an <a href="#" id="mini-logo"></a> account today!</p>-->
<!--			<a href="#" id="register-button">OK!</a>-->
<!--		</div>-->
	</div>
	<div class="spacer"></div>
	<div class="content">
		<div id="splash-circle-wrapper" class="detach wrapper" data-static="true">
			<div id="splash-circle" class="detach">
				<div id="order-wrapper" class="wrapper">
					<div class="spacer">&nbsp;</div>
					<?php $orderAttr = array("splash-redirect" => "pages/order", 'aspect-ratio' => array('x'=>380,'y'=>192, 'respect'=> 'y'));?>
					<a href="#" id="order" <?php //echo ___dA($orderAttr);?> data-on="click">&nbsp;</a>
				</div>
				<div id="splash-bar-wrapper" class="detach wrapper">
					<div id="splash-bar" class="detach">
						<div class="spacer">&nbsp;</div>
						<div id="splash-logo-wrapper">
							<div id="splash-logo" class="preserve-aspect-ratio" <?php echo ___dA(array('aspect-ratio' => array('x'=>552,'y'=>274, 'respect'=> 'y')));?>></div>
						</div>
					</div>
				</div>
				<div id="menu-wrapper" class="wrapper">
					<?php $menuAttr = array("splash-redirect" => "menu", 'aspect-ratio' => array('x'=>380,'y'=>192, 'respect'=> 'y'));?>
					<div class="spacer"></div>
					<a href="#" id="menu" <?php echo ___dA($menuAttr);?> data-on="click">&nbsp;</a>
				</div>
			</div>
		</div>
	</div>
</section>