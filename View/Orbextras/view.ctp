<div class="orbextras view">
<h2><?php echo __('Orbextra'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orbextra['Orbextra']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($orbextra['Orbextra']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subtitle'); ?></dt>
		<dd>
			<?php echo h($orbextra['Orbextra']['subtitle']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($orbextra['Orbextra']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($orbextra['Orbextra']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($orbextra['Orbextra']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbextra'), array('action' => 'edit', $orbextra['Orbextra']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbextra'), array('action' => 'delete', $orbextra['Orbextra']['id']), array(), __('Are you sure you want to delete # %s?', $orbextra['Orbextra']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbs'); ?></h3>
	<?php if (!empty($orbextra['Orb'])): ?>
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
	<?php foreach ($orbextra['Orb'] as $orb): ?>
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
