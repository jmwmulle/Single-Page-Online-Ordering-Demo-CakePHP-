<div class="orbsOrbextras form">
<?php echo $this->Form->create('OrbsOrbextra'); ?>
	<fieldset>
		<legend><?php echo __('Add Orbs Orbextra'); ?></legend>
	<?php
		echo $this->Form->input('orb_id');
		echo $this->Form->input('orbextra_id');
		echo $this->Form->input('pricing_matrix');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Orbs Orbextras'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('controller' => 'orbextras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
	</ul>
</div>
