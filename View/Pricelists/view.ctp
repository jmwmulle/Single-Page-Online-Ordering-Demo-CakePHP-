<div class="pricelists view">
<h2><?php echo __('Pricelist'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('P1'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['p1']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('P2'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['p2']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('P3'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['p3']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('P4'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['p4']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('P5'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['p5']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('P6'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['p6']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Label'); ?></dt>
		<dd>
			<?php echo h($pricelist['Pricelist']['label']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Pricelist'), array('action' => 'edit', $pricelist['Pricelist']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Pricelist'), array('action' => 'delete', $pricelist['Pricelist']['id']), array(), __('Are you sure you want to delete # %s?', $pricelist['Pricelist']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Pricelists'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pricelist'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbopts'); ?></h3>
	<?php if (!empty($pricelist['Orbopt'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Pricelist Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Vendor Title'); ?></th>
		<th><?php echo __('Deprecated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($pricelist['Orbopt'] as $orbopt): ?>
		<tr>
			<td><?php echo $orbopt['id']; ?></td>
			<td><?php echo $orbopt['pricelist_id']; ?></td>
			<td><?php echo $orbopt['title']; ?></td>
			<td><?php echo $orbopt['vendor_title']; ?></td>
			<td><?php echo $orbopt['deprecated']; ?></td>
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
<div class="related">
	<h3><?php echo __('Related Orbs'); ?></h3>
	<?php if (!empty($pricelist['Orb'])): ?>
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
		<th><?php echo __('Config'); ?></th>
		<th><?php echo __('Deprecated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($pricelist['Orb'] as $orb): ?>
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
