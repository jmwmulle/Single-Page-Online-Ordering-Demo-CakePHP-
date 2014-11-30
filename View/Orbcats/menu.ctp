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
	$orbcat_menu_classes = array("small-block-grid-6", "float-pane", "activizing", "");
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
		<li id="orbcat-menu-title">
			<h1>MENU/<span><?php echo substr($m_title, 0, 1) == " " ? substr($m_title, 1) : $m_title; ?></span>
			</h1>
		</li>
	</ul
<?php
$this->end('orbcats_menu');



$this->start('monthly_content');?>><div id="monthly-content-wrapper" class=""><?php echo $this->Html->Image('splash/logo.png');?></div
<?php
$this->end('monthly_content');


$this->start('active_orbs_menu');?><div id="orb-card-stage-right-wrapper">
		<?php echo $this->Element('active_orbs_menu', array('active_orbcat' => $active_orbcat, 'hide_text' => false)); ?>
	</div>
<?php
$this->end('active_orbs_menu');

$this->start('active_orb_card');
	// element (instead of inline to view) so it can also be fetched by ajax alone
	echo $this->Element('orb_card', array('orb' => $active_orbcat['orb_card']));
$this->end('active_orb_card');
?>
