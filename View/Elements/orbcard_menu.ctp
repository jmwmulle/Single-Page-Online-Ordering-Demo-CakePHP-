<?php
/**
 * J. Mulle, for app, 11/29/14 3:09 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$oc = $content['Orbcat'];
$classes = array('flush', 'l-3', "box", "rel", "rightward", "stretch", "activizing", 'orb-card-stage-menu', 'text-center');
if ($portionable) array_push($classes, "portionable");
?>

<?='<div id="orb-stage-menu-header-container">';?>
<?=     '<div id="active-orbcat-menu-header" class="html5-3d-perspective-container box rel text-center orb-card-stage-menu-header">';?>
<?=         '<div id="active-orb-name-3d-context" class="html5-3d-context preserve-3d">';?>
<?=             sprintf('<h2 id="active-orb-name-front-face" class="box downward preserve-3d card-face ">%s</h2>', $oc['menu_title']);?>
<?=             sprintf('<h2 id="active-orb-name-back-face" class="box downward preserve-3d card-face back-face-x">%s</h2>', $oc['menu_title']);?>
<?='</div></div>';?>
<?=$this->Element('optflag_filter_header', array('optflags' => $optflags));?>
<?='</div>';?>
<?= sprintf('<ul id="orb-card-stage-menu" %s>', ___cD($classes));?>
<?php foreach($content['Orb'] as $i => $orb) {
		$classes = array('active-orbcat-item', "xtreme-select-list", "inactive");
		if ( $orb['id'] == $active['id'] ) {
			$classes[2] = 'active';
		}
		echo sprintf('<li %s data-route="orb/%s">', $orb['id'] != -1 ? ___cD($classes) : null, $orb['id']);
		echo sprintf('<a href="#">%s</a></li>', $orb['id'] == -1 ? "&nbsp" : strtoupper($orb['title']));
		}
echo $this->Element('orb_opts_list', array('orb' => $active));
	echo '</ul>';