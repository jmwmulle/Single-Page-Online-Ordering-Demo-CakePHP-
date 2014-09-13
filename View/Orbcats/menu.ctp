<?php
/**
 * J. Mulle, for app, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

$this->start('subnav');
echo $this->Element( 'subnav', array( 'elements' => $subnav ) );
$this->end('subnav');

$this->start('topnav');
echo $this->Element('topnav', array('navopts' => $topnav, 'selected' => $here));
$this->end('topnav');

$this->start('toc');
echo $this->Element('toc', array('elements' => $toc));
$this->end('toc');
?>
<ul class="container orbcard-list">
<?php foreach($orbs as $o) {?>
	<li id="<?php echo ___strToSel($o['title']);?>" class="orbcard"><?php echo $this->Element('orbcard', array('orb' => $o));?></li>
<?php } ?>
</ul>