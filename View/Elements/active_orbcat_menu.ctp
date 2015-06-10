<?php
/**
 * J. Mulle, for app, 11/29/14 3:09 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$active_orb = null;

$classes = array('flush', 'l-3', "box", "rel", "rightward", "stretch", "activizing", 'orb-card-stage-menu', 'text-center');
//echo $this->Html->scriptBlock("pr($.parseJSON('".json_encode($active_orbcat)."'), 'active_orbcat');", array('block' => 'debug')); ?>
<?='<div id="orb-stage-menu-header-container">';?>
<?=     '<div id="active-orbcat-menu-header" class="html5-3d-perspective-container box rel text-center orb-card-stage-menu-header">';?>
<?=         '<div id="active-orb-name-3d-context" class="html5-3d-context preserve-3d">';?>
<?=             sprintf('<h2 id="active-orb-name-front-face" class="box downward preserve-3d card-face ">%s</h2>', $active_orbcat['name']);?>
<?=             sprintf('<h2 id="active-orb-name-back-face" class="box downward preserve-3d card-face back-face-x">%s</h2>', $active_orbcat['name']);?>
<?='</div></div>';
	// when loading from the server, spare the client-side js by writing this here from the outset; ajax-loading done different
if (!$ajax) echo $this->Element('optflag_filter_header', array('optflags' => $active_orbcat['optflags']));?>
<?='</div>';?>
<?= sprintf('<ul id="orb-card-stage-menu" %s>', ___cD($classes));?>
<?php foreach($active_orbcat['orbs'] as $i => $orb) {
		$active_orb = $active_orbcat['orb_card']['Orb']['id'];
		if ($orb['Orb']['id'] != -1) { // ie if it's not a dummy orb
			$classes = array('active-orbcat-item', "xtreme-select-list",
			                 $orb['Orb']['id'] == $active_orb ? 'active' : 'inactive'
			);
			$data = array("route" => "orb".DS.$orb['Orb']['id']);
		}
?>
<?=sprintf('<li %s %s>', ___dA($data),  ___cD($classes));?>
<?=sprintf('<a href="#">%s</a></li>', $orb['Orb']['id'] == -1 ? "&nbsp" : strtoupper($orb['Orb']['title']));
	}
	if (!$ajax && $active_orb) echo $this->Element('orb_opts_list', array('orb' => $orb,
	                                                                      'allow_half_portions' => $active_orbcat['orb_card']['Orb']['allow_half_portions']));?>
<?='</ul>';?>