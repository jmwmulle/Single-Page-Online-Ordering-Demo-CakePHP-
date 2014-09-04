<div class="orbsOrders form">
<?php echo $this->Form->create('OrbsOrder'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orbs Order'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('orb_id');
		echo $this->Form->input('order_id');
		echo $this->Form->input('config');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrbsOrder.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OrbsOrder.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs Orders'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
