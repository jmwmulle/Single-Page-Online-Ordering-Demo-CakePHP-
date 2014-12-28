<?php
/**
 * J. Mulle, for app, 12/21/14 9:55 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>

><div id="orb-opts-menu-header" class="slide-right hidden box rel orb-card-stage-menu-header"
	><ul id="orb-opt-filters" class="multiactivizing"
<?php
	$filters =  array("premium", "meat", "veggie", "sauce", "cheese");
	foreach( $filters  as $filter) {
		$data = array("filter" => $filter);
		$classes = array("orb-opt-filter", "active");?>
		><li <?php echo ___cD($classes);?> <?php echo ___dA($data);?>><span class="icon-checked"></span> <?php echo strtoupper($filter);?></li
	<?php } ?>
	><li id="orb-opt-filters-all" class="box rightward"
		><span class="icon-tab-arrow-l"></span
		><ul class="flush"
			><li><a href="#" class="orb-opt-filter-all" data-all="uncheck" >UNCHECK ALL</a></li
			><li><a href="#" class="orb-opt-filter-all" data-all="check" >CHECK ALL</a></li
		></ul
	></li
	></ul
></div