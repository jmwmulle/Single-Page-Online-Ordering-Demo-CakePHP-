<div class="orbsOrbcats view">
<h2><?php echo __('Orbs Orbcat'); ?></h2>
	<dl>
		<dt><?php echo __('Orb'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrbcat['Orb']['title'], array('controller' => 'orbs', 'action' => 'view', $orbsOrbcat['Orb']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orbcat'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orbsOrbcat['Orbcat']['title'], array('controller' => 'orbcats', 'action' => 'view', $orbsOrbcat['Orbcat']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbs Orbcat'), array('action' => 'edit', $orbsOrbcat['OrbsOrbcat']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbs Orbcat'), array('action' => 'delete', $orbsOrbcat['OrbsOrbcat']['id']), array(), __('Are you sure you want to delete # %s?', $orbsOrbcat['OrbsOrbcat']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs Orbcats'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbs Orbcat'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbs'), array('controller' => 'orbs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orb'), array('controller' => 'orbs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
	</ul>
</div>
