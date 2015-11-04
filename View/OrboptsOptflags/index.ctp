<div class="orboptsOptflags index">
	<h2><?php echo __('Orbopts Optflags'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('orbopt_id'); ?></th>
			<th><?php echo $this->Paginator->sort('optflag_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($orboptsOptflags as $orboptsOptflag): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($orboptsOptflag['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $orboptsOptflag['Orbopt']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($orboptsOptflag['Optflag']['title'], array('controller' => 'optflags', 'action' => 'view', $orboptsOptflag['Optflag']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $orboptsOptflag['OrboptsOptflag']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $orboptsOptflag['OrboptsOptflag']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $orboptsOptflag['OrboptsOptflag']['id']), array(), __('Are you sure you want to delete # %s?', $orboptsOptflag['OrboptsOptflag']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Orbopts Optflag'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('controller' => 'optflags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
	</ul>
</div>
