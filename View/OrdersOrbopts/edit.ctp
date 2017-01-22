<div class="ordersOrbopts form">
<?php echo $this->Form->create('OrdersOrbopt'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orders Orbopt'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('order_id');
		echo $this->Form->input('orbopt_id');
		echo $this->Form->input('orb_uid');
		echo $this->Form->input('coverage');
		echo $this->Form->input('included');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrdersOrbopt.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OrdersOrbopt.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orders Orbopts'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
