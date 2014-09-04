<div class="orbs view">
<h2><?php echo __('Orb'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtitle'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['subtitle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price Matrix'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['price_matrix']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Config'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['config']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($orb['Orb']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orb'), array('action' => 'edit', $orb['Orb']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orb'), array('action' => 'delete', $orb['Orb']['id']), array(), __('Are you sure you want to delete # %s?', $orb['Orb']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('controller' => 'orbextras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbcats'); ?></h3>
	<?php if (!empty($orb['Orbcat'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Subtitle'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orb['Orbcat'] as $orbcat): ?>
		<tr>
			<td><?php echo $orbcat['id']; ?></td>
			<td><?php echo $orbcat['title']; ?></td>
			<td><?php echo $orbcat['subtitle']; ?></td>
			<td><?php echo $orbcat['description']; ?></td>
			<td><?php echo $orbcat['created']; ?></td>
			<td><?php echo $orbcat['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'orbcats', 'action' => 'view', $orbcat['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'orbcats', 'action' => 'edit', $orbcat['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'orbcats', 'action' => 'delete', $orbcat['id']), array(), __('Are you sure you want to delete # %s?', $orbcat['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Orbextras'); ?></h3>
	<?php if (!empty($orb['Orbextra'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Subtitle'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orb['Orbextra'] as $orbextra): ?>
		<tr>
			<td><?php echo $orbextra['id']; ?></td>
			<td><?php echo $orbextra['title']; ?></td>
			<td><?php echo $orbextra['subtitle']; ?></td>
			<td><?php echo $orbextra['description']; ?></td>
			<td><?php echo $orbextra['created']; ?></td>
			<td><?php echo $orbextra['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'orbextras', 'action' => 'view', $orbextra['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'orbextras', 'action' => 'edit', $orbextra['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'orbextras', 'action' => 'delete', $orbextra['id']), array(), __('Are you sure you want to delete # %s?', $orbextra['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
