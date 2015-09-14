<div class="orboptsOrbcats index">
	<h2><?php echo __('Orbopts Orbcats'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('orbopt_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orbcat_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orboptsOrbcats as $orboptsOrbcat): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($orboptsOrbcat['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $orboptsOrbcat['Orbopt']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($orboptsOrbcat['Orbcat']['title'], array('controller' => 'orbcats', 'action' => 'view', $orboptsOrbcat['Orbcat']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orboptsOrbcat['OrboptsOrbcat']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orboptsOrbcat['OrboptsOrbcat']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orboptsOrbcat['OrboptsOrbcat']['id']), array(), __('Are you sure you want to delete # %s?', $orboptsOrbcat['OrboptsOrbcat']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orbopts Orbcat'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
	</ul>
</div>
