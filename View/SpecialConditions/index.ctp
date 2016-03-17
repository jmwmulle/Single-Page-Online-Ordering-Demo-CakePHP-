<div class="specialConditions index">
	<h2><?php echo __('Special Conditions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('special_id'); ?></th>
			<th><?php echo $this->Paginator->sort('content'); ?></th>
			<th><?php echo $this->Paginator->sort('orblist_id'); ?></th>
			<th><?php echo $this->Paginator->sort('orbcat_id'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('price_above'); ?></th>
			<th><?php echo $this->Paginator->sort('order_method'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($specialConditions as $specialCondition): ?>
	<tr>
		<td><?php echo h($specialCondition['SpecialCondition']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($specialCondition['Special']['title'], array('controller' => 'specials', 'action' => 'view', $specialCondition['Special']['id'])); ?>
		</td>
		<td><?php echo h($specialCondition['SpecialCondition']['content']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($specialCondition['Orblist']['name'], array('controller' => 'orblists', 'action' => 'view', $specialCondition['Orblist']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($specialCondition['Orbcat']['title'], array('controller' => 'orbcats', 'action' => 'view', $specialCondition['Orbcat']['id'])); ?>
		</td>
		<td><?php echo h($specialCondition['SpecialCondition']['price']); ?>&nbsp;</td>
		<td><?php echo h($specialCondition['SpecialCondition']['price_above']); ?>&nbsp;</td>
		<td><?php echo h($specialCondition['SpecialCondition']['order_method']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $specialCondition['SpecialCondition']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $specialCondition['SpecialCondition']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $specialCondition['SpecialCondition']['id']), array(), __('Are you sure you want to delete # %s?', $specialCondition['SpecialCondition']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Special Condition'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Specials'), array('controller' => 'specials', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Special'), array('controller' => 'specials', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orblists'), array('controller' => 'orblists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orblist'), array('controller' => 'orblists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
