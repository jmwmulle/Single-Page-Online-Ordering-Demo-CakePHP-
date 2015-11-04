<div class="pricelists form">
<?php echo $this->Form->create('Pricelist'); ?>
	<fieldset>
		<legend><?php echo __('Edit Pricelist'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('p1');
		echo $this->Form->input('p2');
		echo $this->Form->input('p3');
		echo $this->Form->input('p4');
		echo $this->Form->input('p5');
		echo $this->Form->input('p6');
		echo $this->Form->input('label');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Pricelist.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Pricelist.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Pricelists'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
