<?php
/**
 * J. Mulle, for app, 9/3/14 9:42 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus

 * subnav Element

 * sample of expected data
 * $elements = array(
 *          0 => array(
 *                  'label' => << the text to be shown to users; may very by model, but, generally "title" or "name">>,
 *                  'url' => array('controller' => <<duh>>,
 *                                 'action' => << controller method >>,
 *                                 'args' => << array of args, even if 0 or 1, ie. array() or array(1) >>
 *      )
 * );
 */
?>

<ul class="container">
	<?php foreach ($elements as $i => $e) {
		$classes = array("js-link", ___strToSel($e['label']));
		if ($e['active']) array_push($classes, "active");
		?>
	<li <?php echo ___cD($classes);?> data-url="/xtreme/<?php echo $e['url'];?>"><?php echo ucwords($e['label']);?></li>
	<?php } ?>
</ul>