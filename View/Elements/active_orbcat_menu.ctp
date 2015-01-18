<?php
/**
 * J. Mulle, for app, 11/29/14 3:09 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */?><div id="orb-stage-menu-header-container"
	><div id="active-orbcat-menu-header" class="html5-3d-perspective-container box rel text-center orb-card-stage-menu-header"
		><div id="active-orb-name-3d-context" class="html5-3d-context preserve-3d"
			><h2 id="active-orb-name-front-face" class="box downward preserve-3d card-face "><?php echo $active_orbcat['name'];?></h2
			><h2 id="active-orb-name-back-face" class="box downward preserve-3d card-face back-face-x"><?php echo $active_orbcat['name'];?></h2
		></div>
	</div
<?php
	// when loading from the server, spare the client-side js by writing this here from the outset; ajax-loading done different
	if (!$ajax) echo $this->Element('orb_opts_menu_header', array('filters' => $active_orbcat['orb_card']['filters']));

$classes = array('flush', 'l-3', "box", "rel", "rightward", "stretch", "activizing", 'orb-card-stage-menu', 'text-center'); ?>
></div
><ul id="orb-card-stage-menu" <?php echo ___cD($classes);?>><?php
	foreach($active_orbcat['orbs'] as $i => $orb) {
		if ($orb['id'] != -1) { // ie if it's not a dummy orb
			$classes = array('active-orbcat-item', "xtreme-select-list",
			                 $orb['id'] == $active_orbcat['orb_card']['id'] ? 'active' : 'inactive'
			);
			$data = array("route" => "orb".DS.$orb['id']);
		}
?><li <?php echo ___dA($data);?> <?php echo ___cD($classes);?>>
	<a href="#"><?php echo $orb['id'] == -1 ? "&nbsp" : strtoupper($orb['title']);?></a>
</li>
<?php
	}
	if (!$ajax) {
		echo $this->Element('orb_opts_list', array('orb' => $orb, 'ul' => false));
	}
?>
</ul>