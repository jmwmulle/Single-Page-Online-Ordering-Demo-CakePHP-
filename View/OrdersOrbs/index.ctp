<div class="ordersOrbs index">
	<h2><?php echo __('Orders Orbs'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orb_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orb_uid'); ?></th>
			<th><?php echo $this->Paginator->sort('quantity'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($ordersOrbs as $ordersOrb): ?>
	<tr>
		<td><?php echo h($ordersOrb['OrdersOrb']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($ordersOrb['Order']['id'], array('controller' => 'orders', 'action' => 'view', $ordersOrb['Order']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($ordersOrb['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $ordersOrb['Orb']['id'])); ?>
		</td>
		<td><?php echo h($ordersOrb['OrdersOrb']['orb_uid']); ?>&nbsp;</td>
		<td><?php echo h($ordersOrb['OrdersOrb']['quantity']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $ordersOrb['OrdersOrb']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $ordersOrb['OrdersOrb']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $ordersOrb['OrdersOrb']['id']), array(), __('Are you sure you want to delete # %s?', $ordersOrb['OrdersOrb']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Orders Orb'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
