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

$this->start('orbcats_menu');?>
	<div id="category-nav-wrapper" class="inline-wrapper">
		<ul id="category-nav" class="small-block-grid-6 box-wrapper">
			<?php
			foreach ($orbcats_list as $id => $orbcat) {
				$classes = array("js-link", ___strToSel($orbcat), "orbcat",  );
				if ($id == $active_orbcat['id']) array_push($classes, "active");
				if ($orbcat != "XTREME SUBS") $orbcat = str_replace("XTREME", "", $orbcat);
				?>
			<li <?php echo ___cD($classes);?> data-url="/xtreme/menu/<?php echo $id;?>">
				<a class="text-center"><?php echo ucwords($orbcat);?></a>
			</li>
			<?php } ?>
		</ul>
		<h1 id="orbcat-menu-title">MENU/<?php echo $active_orbcat['name'];?></h1>
		<div class="orbcat-triangle-outer">
		</div>
		<div class="orbat-triangle-inner"></div>
	</div>
<?php
$this->end('orbcats_menu');

$this->start('active_orbs_menu');?>
	<h2 class="orbcat-header"><?php echo $active_orbcat['name']?></h2>
	<ul id="orbcat-list" class="text-center tight l-3">
		<li><?php echo $active_orbcat['name'];?></li>
	<?php
		foreach($active_orbcat['orbs'] as $i => $orb) {
			if ($orb['id'] != -1) { // ie if it's not a dummy orb
				$classes = array('jloader');
				$data = array("url" => $orb['url'], 'target' => '#active_orb_card');
			}
		?>
	<li <?php echo ___dA($data);?> <?php echo ___cD($classes);?>>
		<a href="#"><?php echo $orb['id'] == -1 ? "&nbsp" : strtoupper($orb['title']);?></a>
	</li>
	<?php }?>
	</ul>
<?php
$this->end('active_orbs_menu');

$this->start('active_orb_card');
	// element (instead of inline to view) so it can also be fetched by ajax alone
	echo $this->Element('orb_card', array('orb' => $active_orbcat['orb_card']));
$this->end('active_orb_card');
?>
