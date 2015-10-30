<div class="orbopts index">
	<h2><?php echo __('Orbopts'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('pricelist_id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('meat'); ?></th>
			<th><?php echo $this->Paginator->sort('veggie'); ?></th>
			<th><?php echo $this->Paginator->sort('sauce'); ?></th>
			<th><?php echo $this->Paginator->sort('cheese'); ?></th>
			<th><?php echo $this->Paginator->sort('condiment'); ?></th>
			<th><?php echo $this->Paginator->sort('burger'); ?></th>
			<th><?php echo $this->Paginator->sort('salad'); ?></th>
			<th><?php echo $this->Paginator->sort('pizza'); ?></th>
			<th><?php echo $this->Paginator->sort('premium'); ?></th>
			<th><?php echo $this->Paginator->sort('pita'); ?></th>
			<th><?php echo $this->Paginator->sort('subs'); ?></th>
			<th><?php echo $this->Paginator->sort('donair'); ?></th>
			<th><?php echo $this->Paginator->sort('nacho'); ?></th>
			<th><?php echo $this->Paginator->sort('poutines'); ?></th>
			<th><?php echo $this->Paginator->sort('fingers'); ?></th>
			<th><?php echo $this->Paginator->sort('exception_products'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orbopts as $orbopt): ?>
	<tr>
		<td><?php echo h($orbopt['Orbopt']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($orbopt['Pricelist']['id'], array('controller' => 'pricelists', 'action' => 'view', $orbopt['Pricelist']['id'])); ?>
		</td>
		<td><?php echo h($orbopt['Orbopt']['title']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['meat']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['veggie']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['sauce']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['cheese']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['condiment']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['burger']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['salad']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['pizza']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['premium']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['pita']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['subs']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['donair']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['nacho']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['poutines']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['fingers']); ?>&nbsp;</td>
		<td><?php echo h($orbopt['Orbopt']['exception_products']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orbopt['Orbopt']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orbopt['Orbopt']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orbopt['Orbopt']['id']), array(), __('Are you sure you want to delete # %s?', $orbopt['Orbopt']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orbopt'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Pricelists'), array('controller' => 'pricelists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pricelist'), array('controller' => 'pricelists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('controller' => 'optflags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
	</ul>
</div>
