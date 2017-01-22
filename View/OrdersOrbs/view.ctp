<div class="ordersOrbs view">
<h2><?php echo __('Orders Orb'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($ordersOrb['OrdersOrb']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordersOrb['Order']['id'], array('controller' => 'orders', 'action' => 'view', $ordersOrb['Order']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orb'); ?></dt>
		<dd>
			<?php echo $this->Html->link($ordersOrb['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $ordersOrb['Orb']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orb Uid'); ?></dt>
		<dd>
			<?php echo h($ordersOrb['OrdersOrb']['orb_uid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Quantity'); ?></dt>
		<dd>
			<?php echo h($ordersOrb['OrdersOrb']['quantity']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orders Orb'), array('action' => 'edit', $ordersOrb['OrdersOrb']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orders Orb'), array('action' => 'delete', $ordersOrb['OrdersOrb']['id']), array(), __('Are you sure you want to delete # %s?', $ordersOrb['OrdersOrb']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders Orbs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orders Orb'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
