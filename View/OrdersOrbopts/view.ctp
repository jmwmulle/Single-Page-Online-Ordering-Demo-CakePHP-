<div class="ordersOrbopts view">
<h2><?php echo __('Orders Orbopt'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($ordersOrbopt['OrdersOrbopt']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordersOrbopt['Order']['id'], array('controller' => 'orders', 'action' => 'view', $ordersOrbopt['Order']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orbopt'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordersOrbopt['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $ordersOrbopt['Orbopt']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orb Uid'); ?></dt>
		<dd>
			<?php echo h($ordersOrbopt['OrdersOrbopt']['orb_uid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Coverage'); ?></dt>
		<dd>
			<?php echo h($ordersOrbopt['OrdersOrbopt']['coverage']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Included'); ?></dt>
		<dd>
			<?php echo h($ordersOrbopt['OrdersOrbopt']['included']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orders Orbopt'), array('action' => 'edit', $ordersOrbopt['OrdersOrbopt']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orders Orbopt'), array('action' => 'delete', $ordersOrbopt['OrdersOrbopt']['id']), array(), __('Are you sure you want to delete # %s?', $ordersOrbopt['OrdersOrbopt']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders Orbopts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orders Orbopt'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
