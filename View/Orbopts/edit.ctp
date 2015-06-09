<div class="orbopts form">
<?php echo $this->Form->create('Orbopt'); ?>
	<fieldset>
		<legend><?php echo __('Edit Orbopt'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('pricelist_id');
		echo $this->Form->input('title');
		echo $this->Form->input('meat');
		echo $this->Form->input('veggie');
		echo $this->Form->input('sauce');
		echo $this->Form->input('cheese');
		echo $this->Form->input('condiment');
		echo $this->Form->input('burger');
		echo $this->Form->input('salad');
		echo $this->Form->input('pizza');
		echo $this->Form->input('premium');
		echo $this->Form->input('pita');
		echo $this->Form->input('subs');
		echo $this->Form->input('donair');
		echo $this->Form->input('nacho');
		echo $this->Form->input('poutines');
		echo $this->Form->input('fingers');
		echo $this->Form->input('exception_products');
		echo $this->Form->input('Orb');
		echo $this->Form->input('Orbcat');
		echo $this->Form->input('Optflag');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Orbopt.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Orbopt.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Pricelists'), array('controller' => 'pricelists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pricelist'), array('controller' => 'pricelists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('controller' => 'optflags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
	</ul>
</div>
