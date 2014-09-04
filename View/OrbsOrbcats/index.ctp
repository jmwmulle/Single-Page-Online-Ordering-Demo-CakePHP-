<div class="orbsOrbcats index">
	<h2><?php echo __('Orbs Orbcats'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('orb_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orbcat_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orbsOrbcats as $orbsOrbcat): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($orbsOrbcat['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $orbsOrbcat['Orb']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($orbsOrbcat['Orbcat']['title'], array('controller' => 'orbcats', 'action' => 'view', $orbsOrbcat['Orbcat']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orbsOrbcat['OrbsOrbcat']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orbsOrbcat['OrbsOrbcat']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orbsOrbcat['OrbsOrbcat']['id']), array(), __('Are you sure you want to delete # %s?', $orbsOrbcat['OrbsOrbcat']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orbs Orbcat'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
	</ul>
</div>
