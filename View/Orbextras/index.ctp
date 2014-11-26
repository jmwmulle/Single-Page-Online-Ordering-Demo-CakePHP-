<div class="orbextras index">
	<h2><?php echo __('Orbextras'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('subtitle'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orbextras as $orbextra): ?>
	<tr>
		<td><?php echo h($orbextra['Orbextra']['id']); ?>&nbsp;</td>
		<td><?php echo h($orbextra['Orbextra']['title']); ?>&nbsp;</td>
		<td><?php echo h($orbextra['Orbextra']['subtitle']); ?>&nbsp;</td>
		<td><?php echo h($orbextra['Orbextra']['description']); ?>&nbsp;</td>
		<td><?php echo h($orbextra['Orbextra']['created']); ?>&nbsp;</td>
		<td><?php echo h($orbextra['Orbextra']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orbextra['Orbextra']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orbextra['Orbextra']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orbextra['Orbextra']['id']), array(), __('Are you sure you want to delete # %s?', $orbextra['Orbextra']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orbextra'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
