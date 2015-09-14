<div class="variables form">
<?php echo $this->Form->create('Variable'); ?>
	<fieldset>
		<legend><?php echo __('Add Variable'); ?></legend>
	<?php
		echo $this->Form->input('variable');
		echo $this->Form->input('status');
		echo $this->Form->input('last_checked');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Variables'), array('action' => 'index')); ?></li>
	</ul>
</div>
