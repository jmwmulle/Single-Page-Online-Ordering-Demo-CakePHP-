<?php
/**
 * J. Mulle, for app, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<ul class="container orbcard-list">
<?php foreach($orbs as $o) {?>
	<li id="<?php echo ___strToSel($o['title']);?>" class="orbcard"><?php echo $this->Element('orbcard', array('orb' => $o));?></li>
<?php } ?>
</ul>