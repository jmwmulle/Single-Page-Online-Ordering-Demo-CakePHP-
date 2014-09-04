<div class="orbs index">
	<h2><?php echo __('Orbs'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('subtitle'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('price_matrix'); ?></th>
			<th><?php echo $this->Paginator->sort('config'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orbs as $orb): ?>
	<tr>
		<td><?php echo h($orb['Orb']['id']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['title']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['subtitle']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['description']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['price_matrix']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['config']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['created']); ?>&nbsp;</td>
		<td><?php echo h($orb['Orb']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orb['Orb']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orb['Orb']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orb['Orb']['id']), array(), __('Are you sure you want to delete # %s?', $orb['Orb']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orb'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('controller' => 'orbextras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
	</ul>
</div>
