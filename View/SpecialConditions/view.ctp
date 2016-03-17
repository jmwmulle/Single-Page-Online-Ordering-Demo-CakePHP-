<div class="specialConditions view">
<h2><?php echo __('Special Condition'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($specialCondition['SpecialCondition']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Special'); ?></dt>
		<dd>
			<?php echo $this->Html->link($specialCondition['Special']['title'], array('controller' => 'specials', 'action' => 'view', $specialCondition['Special']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($specialCondition['SpecialCondition']['content']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orblist'); ?></dt>
		<dd>
			<?php echo $this->Html->link($specialCondition['Orblist']['name'], array('controller' => 'orblists', 'action' => 'view', $specialCondition['Orblist']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orbcat'); ?></dt>
		<dd>
			<?php echo $this->Html->link($specialCondition['Orbcat']['title'], array('controller' => 'orbcats', 'action' => 'view', $specialCondition['Orbcat']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($specialCondition['SpecialCondition']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price Above'); ?></dt>
		<dd>
			<?php echo h($specialCondition['SpecialCondition']['price_above']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order Method'); ?></dt>
		<dd>
			<?php echo h($specialCondition['SpecialCondition']['order_method']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Special Condition'), array('action' => 'edit', $specialCondition['SpecialCondition']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Special Condition'), array('action' => 'delete', $specialCondition['SpecialCondition']['id']), array(), __('Are you sure you want to delete # %s?', $specialCondition['SpecialCondition']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Special Conditions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Special Condition'), array('action' => 'add')); ?> </li>
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
<div class="related">
	<h3><?php echo __('Related Orbs'); ?></h3>
	<?php if (!empty($specialCondition['Orb'])): ?>
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
	<?php foreach ($specialCondition['Orb'] as $orb): ?>
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
