<div class="orblists view">
<h2><?php echo __('Orblist'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orblist['Orblist']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($orblist['Orblist']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deprecated'); ?></dt>
		<dd>
			<?php echo h($orblist['Orblist']['deprecated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orblist'), array('action' => 'edit', $orblist['Orblist']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orblist'), array('action' => 'delete', $orblist['Orblist']['id']), array(), __('Are you sure you want to delete # %s?', $orblist['Orblist']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orblists'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orblist'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbs'); ?></h3>
	<?php if (!empty($orblist['Orb'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Orbcat Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Pricedict Id'); ?></th>
		<th><?php echo __('Pricelist Id'); ?></th>
		<th><?php echo __('Opt Count'); ?></th>
		<th><?php echo __('Premium Count'); ?></th>
		<th><?php echo __('Cheese Count'); ?></th>
		<th><?php echo __('Sidesauce Count'); ?></th>
		<th><?php echo __('Config'); ?></th>
		<th><?php echo __('Deprecated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orblist['Orb'] as $orb): ?>
		<tr>
			<td><?php echo $orb['id']; ?></td>
			<td><?php echo $orb['orbcat_id']; ?></td>
			<td><?php echo $orb['title']; ?></td>
			<td><?php echo $orb['description']; ?></td>
			<td><?php echo $orb['pricedict_id']; ?></td>
			<td><?php echo $orb['pricelist_id']; ?></td>
			<td><?php echo $orb['opt_count']; ?></td>
			<td><?php echo $orb['premium_count']; ?></td>
			<td><?php echo $orb['cheese_count']; ?></td>
			<td><?php echo $orb['sidesauce_count']; ?></td>
			<td><?php echo $orb['config']; ?></td>
			<td><?php echo $orb['deprecated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'orbs', 'action' => 'view', $orb['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'orbs', 'action' => 'edit', $orb['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'orbs', 'action' => 'delete', $orb['id']), array(), __('Are you sure you want to delete # %s?', $orb['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
