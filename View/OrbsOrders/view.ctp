<div class="orbsOrders view">
<h2><?php echo __('Orbs Order'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orbsOrder['OrbsOrder']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orb'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrder['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $orbsOrder['Orb']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrder['Order']['id'], array('controller' => 'orders', 'action' => 'view', $orbsOrder['Order']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Config'); ?></dt>
		<dd>
			<?php echo h($orbsOrder['OrbsOrder']['config']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbs Order'), array('action' => 'edit', $orbsOrder['OrbsOrder']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbs Order'), array('action' => 'delete', $orbsOrder['OrbsOrder']['id']), array(), __('Are you sure you want to delete # %s?', $orbsOrder['OrbsOrder']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs Orders'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbs Order'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
