<div class="orbopts view">
<h2><?php echo __('Orbopt'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pricelist'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbopt['Pricelist']['id'], array('controller' => 'pricelists', 'action' => 'view', $orbopt['Pricelist']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Meat'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['meat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Veggie'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['veggie']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sauce'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['sauce']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cheese'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['cheese']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Condiment'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['condiment']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Burger'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['burger']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Salad'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['salad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pizza'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['pizza']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Premium'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['premium']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pita'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['pita']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subs'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['subs']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Donair'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['donair']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nacho'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['nacho']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Poutines'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['poutines']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Fingers'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['fingers']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Exception Products'); ?></dt>
		<dd>
			<?php echo h($orbopt['Orbopt']['exception_products']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbopt'), array('action' => 'edit', $orbopt['Orbopt']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbopt'), array('action' => 'delete', $orbopt['Orbopt']['id']), array(), __('Are you sure you want to delete # %s?', $orbopt['Orbopt']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Pricelists'), array('controller' => 'pricelists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pricelist'), array('controller' => 'pricelists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('controller' => 'optflags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Orbs'); ?></h3>
	<?php if (!empty($orbopt['Orb'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Pricedict Id'); ?></th>
		<th><?php echo __('Pricelist Id'); ?></th>
		<th><?php echo __('Opt Count'); ?></th>
		<th><?php echo __('Premium Count'); ?></th>
		<th><?php echo __('Config'); ?></th>
		<th><?php echo __('Removed'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orbopt['Orb'] as $orb): ?>
		<tr>
			<td><?php echo $orb['id']; ?></td>
			<td><?php echo $orb['title']; ?></td>
			<td><?php echo $orb['description']; ?></td>
			<td><?php echo $orb['pricedict_id']; ?></td>
			<td><?php echo $orb['pricelist_id']; ?></td>
			<td><?php echo $orb['opt_count']; ?></td>
			<td><?php echo $orb['premium_count']; ?></td>
			<td><?php echo $orb['config']; ?></td>
			<td><?php echo $orb['removed']; ?></td>
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
<div class="related">
	<h3><?php echo __('Related Orbcats'); ?></h3>
	<?php if (!empty($orbopt['Orbcat'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Primary Menu'); ?></th>
		<th><?php echo __('Orbopt Group'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Subtitle'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orbopt['Orbcat'] as $orbcat): ?>
		<tr>
			<td><?php echo $orbcat['id']; ?></td>
			<td><?php echo $orbcat['primary_menu']; ?></td>
			<td><?php echo $orbcat['orbopt_group']; ?></td>
			<td><?php echo $orbcat['title']; ?></td>
			<td><?php echo $orbcat['subtitle']; ?></td>
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
	<h3><?php echo __('Related Optflags'); ?></h3>
	<?php if (!empty($orbopt['Optflag'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($orbopt['Optflag'] as $optflag): ?>
		<tr>
			<td><?php echo $optflag['id']; ?></td>
			<td><?php echo $optflag['title']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'optflags', 'action' => 'view', $optflag['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'optflags', 'action' => 'edit', $optflag['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'optflags', 'action' => 'delete', $optflag['id']), array(), __('Are you sure you want to delete # %s?', $optflag['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
