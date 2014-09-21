<?php
/**
 * J. Mulle, for app, 9/12/14 9:41 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<dl class="text-center tight">
<?php
	foreach($elements as $i => $orblink) {
		$classes = array();
		if ($i == count($elements)) array_push($classes, "last-content-element");
		$data = array("magellan-arrival" => ___strToSel($orblink), "scroll-target" => "#primary-content");
	?>
<dd <?php echo ___dA($data);?> <?php echo ___cD($classes);?>>
	<a href="<?php echo ___strToSel($orblink, "id");?>"><?php echo ucwords($orblink);?></a>
</dd>
<?php }?>
</dl>

