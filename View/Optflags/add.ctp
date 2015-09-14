<div class="optflags form">
<?php echo $this->Form->create('Optflag'); ?>
	<fieldset>
		<legend><?php echo __('Add Optflag'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('Orbopt');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Optflags'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
