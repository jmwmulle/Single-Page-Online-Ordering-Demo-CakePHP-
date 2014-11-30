<div class="feedbacks form">
<?php echo $this->Form->create('Feedback'); ?>
	<fieldset>
		<legend><?php echo __('Edit Feedback'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('text');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Feedback.id')), array(), __('Are you sure you want to delete # %s?', $this->Form->value('Feedback.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Feedbacks'), array('action' => 'index')); ?></li>
	</ul>
</div>
