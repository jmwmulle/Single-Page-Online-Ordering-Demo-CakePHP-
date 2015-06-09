<?php
/**
 * J. Mulle, for app, 5/1/15 11:04 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

if ( $list_val ) {
	$list_val = str_replace(array('$',' '), array('',''), $list_val);  // pull whitespace & dollar signs out if present
	?>

	<span data-value="<?php echo $dict_val;?>" class='price-dict label'><?php echo $dict_val;?></span>
	<br />
	<span class='price-list' data-value="<?php echo $list_val;?>" ><?php echo money_format( "%#3.2n", $list_val);?></span>
<?php
} else { echo "-"; }?>
