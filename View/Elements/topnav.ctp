<?php
/**
 * J. Mulle, for app, 9/3/14 9:58 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus

 * topNav element

 * sample data
 * $navopts = (array) <<topnav opts, not expected to change, but who can know? >>
 * $here = (str) << currently viewed page,  must exactly match one of the navopts >>
 */

$activeNavOptions = array("Menu","Deals");
?>

<ul class="large-block-grid-6 text-center">
	<li class="ornament">&nbsp;</li>
<?php foreach ($navopts as $no) {
	$classList = array("nav");
	if ($no === $selected) $classList[] = "selected";
	if (!in_array($no, $activeNavOptions)) $classList[] = "disabled";
	?>
	<li><h1  <?php  echo ___cD($classList);?>><?php echo ucwords($no);?></h1></li>
<?php } ?>
	<li class="ornament right">&nbsp;</li>
</ul>