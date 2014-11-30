<div class="feedbacks form">
<?php echo $this->Form->create('Feedback'); ?>
	<fieldset>
		<legend><?php echo __('Add Feedback'); ?></legend>
	<?php
		echo $this->Form->input('text');
		echo $this->Form->input('created');
        ?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Feedbacks'), array('action' => 'index')); ?></li>
	</ul>
</div>
