<?php
/**
 * J. Mulle, for app, 12/21/14 9:55 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

echo '<div id="optflag-filter-header" class="slide-right hidden box rel orb-card-stage-menu-header">';
if (!empty($optflags) ) {
	echo '<ul id="orb-opt-filters" class="multiactivizing">';
	foreach( $optflags  as $id => $optflag) {
		$data = array("optflag-id" => $id, "route" => "optflag/$id/filter");
		$classes = array("orb-opt-filter", "active");?>
	<?= sprintf("<li id='optflag-$id' %s %s><span class='icon-checked'></span>%s</li>", ___cD($classes), ___dA($data),strtoupper($optflag));
	}
	echo '<li id="orb-opt-filters-all" class="box rightward">';
	echo '<span class="icon-tab-arrow-l"></span>';
	echo '<ul class="flush">';
	echo '<li><a href="#" class="orb-opt-filter-all" data-all="uncheck">UNCHECK ALL</a></li>';
	echo '<li><a href="#" class="orb-opt-filter-all" data-all="check" >CHECK ALL</a></li>';
	echo '</ul></li></ul>';
	} else {
		echo '<h2>Nothing to see here!</h2>';
	}
	echo '</div>';