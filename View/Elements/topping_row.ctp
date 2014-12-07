<?php
/**
 * J. Mulle, for app, 11/27/14 2:28 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$list_id = sprintf('id="topping-coverage-%s"', $opt['id']);
	$list_data = array("deactivize-when" => "active", "opt-id" => $opt['id']);
	$list_classes = array("topping", "multi-activizing", "inactive");
	$cancel_id = sprintf('id="topping-coverage-%s"', $opt['id']."-cancel");
	$cancel_data = array("reactivize" => $list_id);
?>
<li <?php echo $list_id;?> <?php echo ___cD($list_classes);?> <?php echo ___dA($list_data);?>
	><ul class="inline activizing"
		><li class="topping-coverage double inactive">&nbsp;</li
		><li class="topping-coverage right-side inactive">&nbsp;</li
		><li class="topping-coverage full inactive">&nbsp;</li
		><li class="topping-coverage left-side inactive">&nbsp;</li
		><li <?php echo $cancel_id;?> <?php echo ___dA($cancel_data);?> class="cancel inactive disabled">&nbsp;</li
		><li><h5><?php echo strtoupper($opt['title']);?></h5></li
	></ul
></li>