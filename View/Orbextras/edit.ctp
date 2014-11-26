<div class="orbextras form">
<?php echo $this->Form->create('Orbextra'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orbextra'); ?></legend>
	<?php
		echo $this->Form->input('id');
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Orbextra.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Orbextra.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
