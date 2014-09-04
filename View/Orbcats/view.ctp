<div class="orbcats view">
<h2><?php echo __('Orbcat'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orbcat['Orbcat']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($orbcat['Orbcat']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtitle'); ?></dt>
		<dd>
			<?php echo h($orbcat['Orbcat']['subtitle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($orbcat['Orbcat']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($orbcat['Orbcat']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($orbcat['Orbcat']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbcat'), array('action' => 'edit', $orbcat['Orbcat']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbcat'), array('action' => 'delete', $orbcat['Orbcat']['id']), array(), __('Are you sure you want to delete # %s?', $orbcat['Orbcat']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbs'); ?></h3>
	<?php if (!empty($orbcat['Orb'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Subtitle'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Price Matrix'); ?></th>
		<th><?php echo __('Config'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orbcat['Orb'] as $orb): ?>
		<tr>
			<td><?php echo $orb['id']; ?></td>
			<td><?php echo $orb['title']; ?></td>
			<td><?php echo $orb['subtitle']; ?></td>
			<td><?php echo $orb['description']; ?></td>
			<td><?php echo $orb['price_matrix']; ?></td>
			<td><?php echo $orb['config']; ?></td>
			<td><?php echo $orb['created']; ?></td>
			<td><?php echo $orb['modified']; ?></td>
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
