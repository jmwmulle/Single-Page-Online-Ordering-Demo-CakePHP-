<div class="orbs form">
<?php echo $this->Form->create('Orb'); ?>
	<fieldset>
		<legend><?php echo __('Add Orb'); ?></legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('subtitle');
		echo $this->Form->input('description');
		echo $this->Form->input('price_matrix');
		echo $this->Form->input('config');
		echo $this->Form->input('Orbcat');
		echo $this->Form->input('Orbextra');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Orbs'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('controller' => 'orbextras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
	</ul>
</div>
