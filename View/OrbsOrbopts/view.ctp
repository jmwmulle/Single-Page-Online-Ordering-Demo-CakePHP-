<div class="orbsOrbopts view">
<h2><?php echo __('Orbs Orbopt'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($orbsOrbopt['OrbsOrbopt']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orb'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrbopt['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $orbsOrbopt['Orb']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orbopt'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrbopt['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $orbsOrbopt['Orbopt']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Default'); ?></dt>
		<dd>
			<?php echo h($orbsOrbopt['OrbsOrbopt']['default']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Deprecated'); ?></dt>
		<dd>
			<?php echo h($orbsOrbopt['OrbsOrbopt']['deprecated']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbs Orbopt'), array('action' => 'edit', $orbsOrbopt['OrbsOrbopt']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbs Orbopt'), array('action' => 'delete', $orbsOrbopt['OrbsOrbopt']['id']), array(), __('Are you sure you want to delete # %s?', $orbsOrbopt['OrbsOrbopt']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs Orbopts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbs Orbopt'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
	</ul>
</div>
