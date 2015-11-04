<div class="orboptsOptflags form">
<?php echo $this->Form->create('OrboptsOptflag'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orbopts Optflag'); ?></legend>
	<?php
		echo $this->Form->input('orbopt_id');
		echo $this->Form->input('optflag_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('OrboptsOptflag.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('OrboptsOptflag.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts Optflags'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('controller' => 'optflags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
	</ul>
</div>
