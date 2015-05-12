<div class="optflags view">
<h2><?php echo __('Optflag'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($optflag['Optflag']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($optflag['Optflag']['title']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Optflag'), array('action' => 'edit', $optflag['Optflag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Optflag'), array('action' => 'delete', $optflag['Optflag']['id']), array(), __('Are you sure you want to delete # %s?', $optflag['Optflag']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbopts'); ?></h3>
	<?php if (!empty($optflag['Orbopt'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Pricelist Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Meat'); ?></th>
		<th><?php echo __('Veggie'); ?></th>
		<th><?php echo __('Sauce'); ?></th>
		<th><?php echo __('Cheese'); ?></th>
		<th><?php echo __('Condiment'); ?></th>
		<th><?php echo __('Burger'); ?></th>
		<th><?php echo __('Salad'); ?></th>
		<th><?php echo __('Pizza'); ?></th>
		<th><?php echo __('Premium'); ?></th>
		<th><?php echo __('Pita'); ?></th>
		<th><?php echo __('Subs'); ?></th>
		<th><?php echo __('Donair'); ?></th>
		<th><?php echo __('Nacho'); ?></th>
		<th><?php echo __('Poutines'); ?></th>
		<th><?php echo __('Fingers'); ?></th>
		<th><?php echo __('Exception Products'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($optflag['Orbopt'] as $orbopt): ?>
		<tr>
			<td><?php echo $orbopt['id']; ?></td>
			<td><?php echo $orbopt['pricelist_id']; ?></td>
			<td><?php echo $orbopt['title']; ?></td>
			<td><?php echo $orbopt['meat']; ?></td>
			<td><?php echo $orbopt['veggie']; ?></td>
			<td><?php echo $orbopt['sauce']; ?></td>
			<td><?php echo $orbopt['cheese']; ?></td>
			<td><?php echo $orbopt['condiment']; ?></td>
			<td><?php echo $orbopt['burger']; ?></td>
			<td><?php echo $orbopt['salad']; ?></td>
			<td><?php echo $orbopt['pizza']; ?></td>
			<td><?php echo $orbopt['premium']; ?></td>
			<td><?php echo $orbopt['pita']; ?></td>
			<td><?php echo $orbopt['subs']; ?></td>
			<td><?php echo $orbopt['donair']; ?></td>
			<td><?php echo $orbopt['nacho']; ?></td>
			<td><?php echo $orbopt['poutines']; ?></td>
			<td><?php echo $orbopt['fingers']; ?></td>
			<td><?php echo $orbopt['exception_products']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'orbopts', 'action' => 'view', $orbopt['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'orbopts', 'action' => 'edit', $orbopt['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'orbopts', 'action' => 'delete', $orbopt['id']), array(), __('Are you sure you want to delete # %s?', $orbopt['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
