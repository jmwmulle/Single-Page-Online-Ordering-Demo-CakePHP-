<div class="orbcats form">
<?php echo $this->Form->create('Orbcat'); ?>
	<fieldset>
		<legend><?php echo __('Add Orbcat'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('subtitle');
		echo $this->Form->input('description');
		echo $this->Form->input('Orb');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Orbcats'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
