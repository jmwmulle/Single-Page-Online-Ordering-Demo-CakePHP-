<?php
/**
 * J. Mulle, for app, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 *
 *  Receives:
 * $active[id, name, orbs]
 * $here
 * $orbcats_list
 */
$order = $this->Session->read('Cart.Order');
$this->start('orbcats_menu');
	$orbcat_menu_classes = array("large-block-grid-6", "small-block-grid-3", "float-pane", "activizing", "left", "box", "rel");
?>
<ul id="orbcat-menu" <?php echo ___cD($orbcat_menu_classes);?>>
	<?php
	$m_title = $active_orbcat['name'];
	foreach ($orbcats_list as $id => $orbcat) {
		$orbcat_item_classes = array("orbcat", $id == $active_orbcat['id'] ? "active" : "inactive", "route" );
		$data = array("route" =>implode(DS,array("orbcat",$id, ucwords($orbcat))));
	?>
	<li <?php echo ___cD($orbcat_item_classes);?> <?php echo ___dA($data);?>>
		<a class="text-center"><span class="orbcat-icon icon-<?php echo ___strToSel($orbcat);?>"></span><?php echo ucwords($orbcat);?></a>
	</li>
	<?php } ?>
	<li id="orbcat-menu-title" class="stretch box rel downward">
		<h1>MENU/<span><?php echo substr($m_title, 0, 1) == " " ? substr($m_title, 1) : $m_title; ?></span>
		</h1>
	</li>
</ul>
<?php
$this->end('orbcats_menu');

$this->start('orb_card_modal');?>
	<div id="orbcard-modal" class="box leftward left small-12 columns" style="display:none;">
		<div id="orbcard-modal-content">
			<div class="row">
				<h4 class="text-center"><?php echo strtoupper("Success! your cart now tastes better!");?></h4>
				<div class="large-8 medium-6 small-12 large-centered medium-centered columns">
					<ul class="small-block-grid-3" class="hn-l-cn">
						<li class="text-center">
							<a id="continue-ordering"  href="#" data-route="continue_ordering"><?php echo strtoupper("Continue Ordering");?></a>
						</li>
						<li class="text-center">
							<a id="view-order" href=#" data-route="order/view"><?php echo strtoupper("View Order");?></a>
						</li>
						<li class="text-center">
							<a id="finish-ordering" href="#" data-route="finish_ordering"><?php echo strtoupper("Finish Ordering");?></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
<?php $this->end('orb_card_modal');

$this->start('monthly_content');?><div id="monthly-content-wrapper" class=""><?php echo $this->Html->Image('splash/logo.png');?></div>
<?php
$this->end('monthly_content');


$this->start('active_orbcat_menu');
	echo $this->Element('active_orbcat_menu', array('active_orbcat' => $active_orbcat, 'hide_text' => false, 'ajax'=> false));
$this->end('active_orbcat_menu');

$this->start('active_orb_card');
	// element (instead of inline to view) so it can also be fetched by ajax alone
	echo $this->Element('orb_card', array('orb' => $active_orbcat['orb_card'], 'ajax' => false));
$this->end('active_orb_card');
?>
<div class="row">
	<main id="menu" class="large-12 columns<?php if ($this->get( "is_splash" )) echo " fade-out";?>">
		<div class="row">
			<div class="large-9 small-5 columns">
				<div class="row">
					<div class="large-1 columns show-for-large-up">
						<ul id="user-activity-panel" class="activizing text-center">
							<li><h2 class="body-font-color">I AM</h2></li>
							<?php $order_method = array_key_exists('order_method', $order) ? $order['order_method']: false; ?>
							<li class="<?php echo !$order_method ? "active" : "inactive";?> default"
								><a class="body-font-color block" data-route="order_method/menu/browsing">Just<br />Browsing</a
									></li>
							<li class="<?php echo $order_method == "delivery" ?"active" : "inactive"; ?>"
								><a class="body-font-color block modal-link overlay" data-route="order_method/menu/delivery">Ordering<br />(Delivery)</a
							></li>
							<li class="<?php echo $order_method == "pickup" ? "active" : "inactive"; ?>">
								<a class="body-font-color block modal-link overlay" data-route="order_method/menu/pickup">Ordering<br />(Pick-Up)</a>
							</li>
						</ul>
					</div>
					<div class="large-11 small-12 columns">
						<?php echo $this->fetch('orbcats_menu');?>
					</div>
				</div>
			</div>
			<div class="large-3 columns show-for-large-up">
				<?php echo $this->fetch('monthly_content');?>
			</div>
		</div>
		<div class="row">
			<div class="large-12  columns show-for-large-up">
				<div id="orb-card-wrapper" class="float-pane box rel xtreme-blue-bg"
					><?php echo $this->fetch('orb_card_modal');?>
					<?php echo $this->fetch('active_orb_card');?>
					<div id="orb-card-stage-menu-wrapper" class="box rightward xtreme-blue-bg">
						<?php echo $this->fetch('active_orbcat_menu');?>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>
<?php
$this->start('cart');

$this->end('cart');
?>
