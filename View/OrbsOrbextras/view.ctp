<div class="orbsOrbextras view">
<h2><?php echo __('Orbs Orbextra'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orbsOrbextra['OrbsOrbextra']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orb'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrbextra['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $orbsOrbextra['Orb']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orbextra'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrbextra['Orbextra']['title'], array('controller' => 'orbextras', 'action' => 'view', $orbsOrbextra['Orbextra']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pricing Matrix'); ?></dt>
		<dd>
			<?php echo h($orbsOrbextra['OrbsOrbextra']['pricing_matrix']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbs Orbextra'), array('action' => 'edit', $orbsOrbextra['OrbsOrbextra']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbs Orbextra'), array('action' => 'delete', $orbsOrbextra['OrbsOrbextra']['id']), array(), __('Are you sure you want to delete # %s?', $orbsOrbextra['OrbsOrbextra']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs Orbextras'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbs Orbextra'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbextras'), array('controller' => 'orbextras', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbextra'), array('controller' => 'orbextras', 'action' => 'add')); ?> </li>
	</ul>
</div>
