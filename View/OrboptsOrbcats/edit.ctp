<div class="orboptsOrbcats form">
<?php echo $this->Form->create('OrboptsOrbcat'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orbopts Orbcat'); ?></legend>
	<?php
		echo $this->Form->input('orbopt_id');
		echo $this->Form->input('orbcat_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrboptsOrbcat.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OrboptsOrbcat.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts Orbcats'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
	</ul>
</div>
