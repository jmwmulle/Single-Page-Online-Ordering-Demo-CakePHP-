<?php
/**
 * J. Mulle, for app, 11/25/14 6:56 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

echo $response;
?>

<?php echo $this->Form->create(Null, array('url' => array('controller' => 'Orders', 'action' => 'add_to_cart'))); ?>
<?php echo $this->Form->input('identity'); ?>
<?php echo $this->Form->input('quantity'); ?>
<?php echo $this->Form->input('price_rank'); ?>
<?php echo $this->Form->input('orbopts'); ?>
<?php echo $this->Form->button('Add to Cart', array('style'=> 'position:absolute; top:10px; left:10px', 'class' => 'btn btn-primary addtocart', 'id' => 'addtocart'));?>
<?php echo $this->Form->end(); ?>
