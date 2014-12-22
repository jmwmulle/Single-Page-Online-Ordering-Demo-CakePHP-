<?php
/**
 * J. Mulle, for app, 11/27/14 2:28 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$list_classes = array("fade-out", "topping", "inactive", "xtreme-select-list");
	$icons = array('right-side' => "R", 'full' => "F", 'left-side' => "L", 'double' => "D");
	$data = array("id" => $opt['id'], "name" => strtolower($opt['title']));
	foreach (array_slice($opt, 3, -1) as $flag => $value) {
		if ($value) $data['flags'][] = $flag;
	}
?>
<li id="<?php echo sprintf("topping-coverage-%s", $opt['id']);?>" <?php echo ___cD($list_classes);?> <?php echo ___dA($data);?>
	><ul class="stretch inline"
         <?php foreach($icons as $icon => $value) {
         $classes = array("topping-coverage", $icon, "icon-$icon", "inactive", "disabled");
	      if ($icon == "full") $classes[3] = "active";?>
		><li <?php echo ___cD($classes); ?> data-weight="<?php echo $value;?>"></li
		<?php } ?>
		><li><a href="#"><?php echo strtoupper($opt['title']);?></a></li
	></ul
></li>