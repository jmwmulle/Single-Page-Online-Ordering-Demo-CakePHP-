<?php
/**
 * J. Mulle, for app, 11/29/14 3:09 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */?>


<div id="active-orb-name" class="box rel text-center html5-3d-perspective-container orb-card-stage-menu-header">
	<div id="active-orb-name-3d-context" class="html5-3d-context preserve-3d">
		<h2 id="active-orb-name-front-face" class="preserve-3d card-face"><?php echo $active_orbcat['name'];?></h2>
		<h2 id="active-orb-name-back-face" class="preserve-3d card-face back-face-x"><?php echo $active_orbcat['name'];?></h2>
	</div>
</div>

<?php $classes = array('text-center', 'flush', 'l-3', 'activizing', 'orb-card-stage-menu', "box", "rel", "rightward", "stretch"); ?>
<ul <?php echo ___cD($classes);?>>
<?php
	foreach($active_orbcat['orbs'] as $i => $orb) {
		if ($orb['id'] != -1) { // ie if it's not a dummy orb
			$classes = array('orb-card-refresh', 'menu-shadow', "xtreme-select-list",
			                 $orb['id'] == $active_orbcat['orb_card']['id'] ? 'active' : 'inactive'
			);
			$data = array("orb" => $orb['id']);
		}
	?>
<li <?php echo ___dA($data);?> <?php echo ___cD($classes);?>>
	<a href="#" <?php echo $hide_text ? ___cD(array('fade-out')) : null;?>><?php echo $orb['id'] == -1 ? "&nbsp" : strtoupper($orb['title']);?></a>
</li>
<?php }?>
</ul>
