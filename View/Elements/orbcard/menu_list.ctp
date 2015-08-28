<?php
/**
 * J. Mulle, for app, 8/14/15 1:08 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$classes = ['flush', 'l-3', "box", "rel", "rightward", "stretch", 'orb-card-stage-menu', 'text-center', $type];
if ($type == "orb") array_push($classes, "activizing");
if ($portionable and $type == "opt") array_push($classes, "portionable");
if ($ajax or $type == "opt") {
	array_push($classes, "hidden");
	array_push($classes, "fade-out");
}
$opts = $active ? $active["Orbopt"] : $content;
?>
<?=sprintf('<ul id="orb-card-stage-menu-%s" %s>', $type, ___cD($classes));?>
<?php
	if ($type == "orb"):
		foreach($content['Orb'] as $i => $orb):
			$classes = array('active-orbcat-item', "xtreme-select-list", "inactive");
			if ( $orb['id'] == $active['id'] ) $classes[2] = 'active';
			echo sprintf('<li id="orb-%s" %s data-route="load_orb/%s">', $orb['id'], $orb['id'] != -1 ? ___cD($classes) : null, $orb['id']);
			echo sprintf('<a href="#">%s</a></li>', $orb['id'] == -1 ? "&nbsp" : strtoupper($orb['title']));
		endforeach;
	else:
		foreach ($opts as $opt) echo $this->Element("orbcard/opt_row", compact('opt'));
	endif?>
<?='</ul>'?>