<?php
/**
 * J. Mulle, for app, 6/17/15 8:23 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
echo "<ul id='orbcat-menu' class='large-block-grid-6 small-block-grid-3 float-pane activizing left box rel'>";
foreach ($orbcats as $id => $orbcat) {
	$uc_orbcat = ucfirst($orbcat);
	echo sprintf("<li %s data-route='orbcat/$id/$uc_orbcat'>", ___cD(array("orbcat", $id == $active['id'] ? "active" : "inactive" )));
	echo sprintf("<a class='text-center'><span class='orbcat-icon icon-%s'></span>%s</a></li>", ___strToSel($orbcat), $uc_orbcat);
}
echo '<li id="orbcat-menu-title" class="stretch box rel downward">';
echo sprintf("<h1>MENU/<span>%s</span></h1></li></ul>", $active['menu_title']);