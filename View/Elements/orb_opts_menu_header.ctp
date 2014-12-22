<?php
/**
 * J. Mulle, for app, 12/21/14 9:55 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

$classes = array("orb-opt-filter", "inline", "text-left", "orb-card-stage-menu");?>
><div id="orb-opts-menu-header" class="slide-right hidden box rel orb-card-stage-menu-header">
	<ul id="orb-opt-filters" class="orb-opt-filter, multiactivizing"
<?php foreach( array("premium", "meat", "veggie", "sauce", "cheese", "check all") as $filter) {
	$data = array("filter" => $filter);?>
	><li <?php echo ___cD($classes);?> <?php echo ___dA($data);?>><span class="icon-checked"></span> <?php echo strtoupper($filter);?></li
	<?php }
	$classes = array("box", "rel", "rightward", "stretch", "active", "orb-card-stage-menu", "multi-activizing", "flush");?>
	></ul>
</div