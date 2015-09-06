<div class="variables view">
<h2><?php echo __('Variable'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($variable['Variable']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Variable'); ?></dt>
		<dd>
			<?php echo h($variable['Variable']['variable']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($variable['Variable']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Checked'); ?></dt>
		<dd>
			<?php echo h($variable['Variable']['last_checked']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Variable'), array('action' => 'edit', $variable['Variable']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Variable'), array('action' => 'delete', $variable['Variable']['id']), array(), __('Are you sure you want to delete # %s?', $variable['Variable']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Variables'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Variable'), array('action' => 'add')); ?> </li>
	</ul>
</div>
