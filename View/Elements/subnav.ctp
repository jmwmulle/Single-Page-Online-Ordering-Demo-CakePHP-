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

		<ul>
			<?php foreach ($elements as $e) {?>
			<li <?php echo __cD(array($label));?> data-get="<?php echo $url;?>" data-for="#main-content"><?php echo strtolower(ucwords($label));?></li>
			<?php } ?>
		</ul>