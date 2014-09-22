<?php
/**
 * J. Mulle, for app, 9/12/14 9:41 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<ul class="text-center tight">
<?php
	foreach($elements as $i => $orblink) {
		$classes = array();
		if ($i == count($elements)) array_push($classes, "last-content-element");
		$data = array("scroll-to" => ___strToSel($orblink), "scroll-target" => "#primary-content");
	?>
<li <?php echo ___dA($data);?> <?php echo ___cD($classes);?>><?php echo ucwords($orblink);?></li>
<?php }?>
</ul>

