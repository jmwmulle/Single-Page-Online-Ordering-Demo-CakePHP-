<div class="orbsOrbopts form">
<?php echo $this->Form->create('OrbsOrbopt'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orbs Orbopt'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('orb_id');
		echo $this->Form->input('orbopt_id');
		echo $this->Form->input('default');
		echo $this->Form->input('deprecated');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrbsOrbopt.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OrbsOrbopt.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs Orbopts'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
