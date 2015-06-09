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
	$optflags =  $this->get('optflags_list');
	$id = sprintf("orb-opt-%s", $opt['id']);
	$icons = array('right-side' => "R", 'full' => "F", 'left-side' => "L", 'double' => "D");
	if (!$allow_half_portions) $icons = array_slice($icons, -1);
	$data = array("route" => "orb_opt/opt" . DS . sprintf("#%s", $id) . DS .___as_file_name($opt['title']), 'optflags' => array());
	foreach ($opt['Optflag'] as $flag) {
		if ($flag['title']) {
			array_push($list_classes, $flag['title']);
			array_push($data['optflags'], $flag['id']);
		}
	}
?>
<li id="<?php echo $id; ?>" <?php echo ___cD($list_classes); ?> <?php echo ___dA($data); ?>>
	<ul class="stretch inline"
		<?php foreach ($icons as $icon => $value) {
			$classes = array("orb-opt-coverage", $icon, "icon-$icon", "inactive", "disabled");
			if ($icon == "full") $classes[3] = "active";
			$id = sprintf("orb-opt-%s-weight-%s", $opt['id'], $value);
		?>
		>
		<li <?php echo ___cD($classes); ?> data-route="<?php echo "orb_opt/weight" . DS . sprintf("#%s", $id) . DS . "false" . DS . $value; ?>"></li
			<?php } ?>
			>
		<li><a href="#"><?php echo strtoupper($opt['title']); ?></a></li>
	</ul>
</li>
<?php } else { echo sprintf('<li %s></li>', ___cD($list_classes));}