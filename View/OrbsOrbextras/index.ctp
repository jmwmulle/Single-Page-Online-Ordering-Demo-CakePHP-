<div class="orbsOrbextras index">
	<h2><?php echo __('Orbs Orbextras'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('orb_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orbextra_id'); ?></th>
			<th><?php echo $this->Paginator->sort('pricing_matrix'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orbsOrbextras as $orbsOrbextra): ?>
	<tr>
		<td><?php echo h($orbsOrbextra['OrbsOrbextra']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($orbsOrbextra['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $orbsOrbextra['Orb']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($orbsOrbextra['Orbextra']['title'], array('controller' => 'orbextras', 'action' => 'view', $orbsOrbextra['Orbextra']['id'])); ?>
		</td>
		<td><?php echo h($orbsOrbextra['OrbsOrbextra']['pricing_matrix']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orbsOrbextra['OrbsOrbextra']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orbsOrbextra['OrbsOrbextra']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orbsOrbextra['OrbsOrbextra']['id']), array(), __('Are you sure you want to delete # %s?', $orbsOrbextra['OrbsOrbextra']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orbs Orbextra'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('controller' => 'orbextras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
	</ul>
</div>
