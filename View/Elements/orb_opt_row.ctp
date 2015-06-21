<?php
	/**
	 * J. Mulle, for app, 11/27/14 2:28 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	$list_classes = array("fade-out", "orb-opt", "inactive", "xtreme-select-list");
if ($opt) {

	if ($opt['default']) $list_classes[2] = 'active';
	$optflags =  $this->get('optflags_list');
	$id = sprintf("orb-opt-%s", $opt['id']);
	$icons = array('right-side' => "R", 'full' => "F", 'left-side' => "L", 'double' => "D");
//	if (!$allow_half_portions) $icons = array_slice($icons, -1);
	$data = array("route" => "orb_opt/opt" . DS . sprintf("#%s", $id) . DS .___as_file_name($opt['title']), 'optflags' => array());
	foreach ($opt['Optflag'] as $flag) {
		if ($flag['title']) {
			array_push($list_classes, $flag['title']);
			array_push($data['optflags'], $flag['id']);
		}
	}?>
	<?=sprintf("<li id='$id' %s %s>", ___cD($list_classes), ___dA($data)); ?>
	<?='<ul class="stretch inline">';?>
	<?php foreach ($icons as $icon => $value) {
				$classes = array("orb-opt-coverage", $icon, "icon-$icon", "inactive", "disabled");
				if ($icon == "full") $classes[3] = "active";
				if ($opt['default']) $classes[4] = "enabled";
				$id = sprintf("orb-opt-%s-weight-%s", $opt['id'], $value);
				$data = array('route' => implode(DS, array("orb_opt","weight","#$id","false",$value)),
			                  'weight' => $value);
				echo sprintf("<li %s %s></li>", ___cD($classes), ___dA($data));
			}?>
	<?=sprintf('<li><a href="#">%s</a></li>', strtoupper($opt['title']));?>
	<?='</ul>';?>
	<?='</li>';?>
<?php } else {
	echo sprintf('<li %s></li>', ___cD($list_classes));
}