<div class="ordersOrbopts index">
	<h2><?php echo __('Orders Orbopts'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('order_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orbopt_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orb_uid'); ?></th>
			<th><?php echo $this->Paginator->sort('coverage'); ?></th>
			<th><?php echo $this->Paginator->sort('included'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($ordersOrbopts as $ordersOrbopt): ?>
	<tr>
		<td><?php echo h($ordersOrbopt['OrdersOrbopt']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($ordersOrbopt['Order']['id'], array('controller' => 'orders', 'action' => 'view', $ordersOrbopt['Order']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($ordersOrbopt['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $ordersOrbopt['Orbopt']['id'])); ?>
		</td>
		<td><?php echo h($ordersOrbopt['OrdersOrbopt']['orb_uid']); ?>&nbsp;</td>
		<td><?php echo h($ordersOrbopt['OrdersOrbopt']['coverage']); ?>&nbsp;</td>
		<td><?php echo h($ordersOrbopt['OrdersOrbopt']['included']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $ordersOrbopt['OrdersOrbopt']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $ordersOrbopt['OrdersOrbopt']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $ordersOrbopt['OrdersOrbopt']['id']), array(), __('Are you sure you want to delete # %s?', $ordersOrbopt['OrdersOrbopt']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orders Orbopt'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
