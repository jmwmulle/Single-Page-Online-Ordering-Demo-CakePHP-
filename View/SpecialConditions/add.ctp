<div class="specialConditions form">
<?php echo $this->Form->create('SpecialCondition'); ?>
	<fieldset>
		<legend><?php echo __('Add Special Condition'); ?></legend>
	<?php
		echo $this->Form->input('special_id');
		echo $this->Form->input('content');
		echo $this->Form->input('orblist_id');
		echo $this->Form->input('orbcat_id');
		echo $this->Form->input('price');
		echo $this->Form->input('price_above');
		echo $this->Form->input('order_method');
		echo $this->Form->input('Orb');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Special Conditions'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Specials'), array('controller' => 'specials', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Special'), array('controller' => 'specials', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orblists'), array('controller' => 'orblists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orblist'), array('controller' => 'orblists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
