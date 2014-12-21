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

$this->start('orbcats_menu');
	$orbcat_menu_classes = array("small-block-grid-6", "float-pane", "activizing", "left", "box", "rel");
?>><ul id="orbcat-menu" <?php echo ___cD($orbcat_menu_classes);?>>
		<?php
		$m_title = $active_orbcat['name'];
		foreach ($orbcats_list as $id => $orbcat) {
			$orbcat_item_classes = array("orbcat-refresh",
			                             ___strToSel($orbcat), "orbcat",
			                             $id == $active_orbcat['id'] ? "active" : "inactive" );
			$data = array("orbcat" => $id, "orbcat-name" => ucwords($orbcat));
		?>
		<li <?php echo ___cD($orbcat_item_classes);?> <?php echo ___dA($data);?>">
			<a class="text-center"><?php echo ucwords($orbcat);?></a>
		</li>
		<?php } ?>
		<li id="orbcat-menu-title" class="stretch box rel downward">
			<h1>MENU/<span><?php echo substr($m_title, 0, 1) == " " ? substr($m_title, 1) : $m_title; ?></span>
			</h1>
		</li>
	</ul
<?php
$this->end('orbcats_menu');



$this->start('monthly_content');?>><div id="monthly-content-wrapper" class=""><?php echo $this->Html->Image('splash/logo.png');?></div
<?php
$this->end('monthly_content');


$this->start('active_orbs_menu');?><div id="orb-card-stage-menu" class="box rightward xtreme-blue-bg">
		<?php echo $this->Element('active_orbs_menu', array('active_orbcat' => $active_orbcat, 'hide_text' => false)); ?>
	</div>
<?php
$this->end('active_orbs_menu');

$this->start('active_orb_card');
	// element (instead of inline to view) so it can also be fetched by ajax alone
	echo $this->Element('orb_card', array('orb' => $active_orbcat['orb_card']));
$this->end('active_orb_card');
?>

<main id="menu" class="box rel">
	<div class="row">
		<div class="large-12 columns">
			<ul id="user-activity-panel" class="show-for-large-up activizing text-center">
				<li><h2 class="body-font-color">I AM</h2></li>
				<li class="active"><a class="body-font-color block">Just<br />Browsing</a></li>
				<li class="inactive coming-soon"><a class="body-font-color block">Ordering<br />(Delivery)</a></li>
				<li class="inactive coming-soon"><a class="body-font-color block">Ordering<br />(Pick-Up)</a></li>
			</ul
			<?php echo $this->fetch('orbcats_menu').$this->fetch('monthly_content'); ?>></div>
	</div
	><div class="row"
		><div class="large-12  columns"
			><div id="orb-card-wrapper" class="float-pane box rel xtreme-blue-bg"
				><div id="order-modal" class="box leftward left" style="display:none;">
					<div id="order-modal-content">
						<div class="row">
							<h4 class="text-center"><?php echo strtoupper("Success! your cart now tastes better!");?></h4>
							<div class="large-8 medium-6 small-12 large-centered medium-centered columns">
								<ul class="small-block-grid-3" class="hn-l-cn">
									<li class="text-center">
										<a id="continue-ordering"  href="#" class="modal-link order" data-action="continue_ordering"><?php echo strtoupper("Continue Ordering");?></a>
									</li>
									<li class="text-center">
										<a id="view-order" href=#" class="modal-link order" data-action="view_order"><?php echo strtoupper("View Order");?></a>
									</li>
									<li class="text-center">
										<a id="finish-ordering" href="#" class="modal-link order" data-action="finish_ordering"><?php echo strtoupper("Finish Ordering");?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<?php echo $this->fetch('active_orb_card');?>
				<?php echo $this->fetch('active_orbs_menu');?>
			</div>
		</div>
	</div>
</main>
<?php
$this->start('cart');

$this->end('cart');
?>