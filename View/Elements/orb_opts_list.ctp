<?php
/**
 * J. Mulle, for app, 12/21/14 9:57 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$classes = array("box", "rel", "rightward", "stretch", "active", "orb-card-stage-menu", "multi-activizing", "flush");
foreach ($orb["Orbopt"] as $opt) {
//	pr($opt);
//	continue;
	echo $this->Element("orb_opt_row", array("opt" => $opt));
}
