<div class="orboptsOrbcats view">
<h2><?php echo __('Orbopts Orbcat'); ?></h2>
	<dl>
		<dt><?php echo __('Orbopt'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orboptsOrbcat['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $orboptsOrbcat['Orbopt']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Orbcat'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orboptsOrbcat['Orbcat']['title'], array('controller' => 'orbcats', 'action' => 'view', $orboptsOrbcat['Orbcat']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbopts Orbcat'), array('action' => 'edit', $orboptsOrbcat['OrboptsOrbcat']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbopts Orbcat'), array('action' => 'delete', $orboptsOrbcat['OrboptsOrbcat']['id']), array(), __('Are you sure you want to delete # %s?', $orboptsOrbcat['OrboptsOrbcat']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts Orbcats'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopts Orbcat'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbcats'), array('controller' => 'orbcats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbcat'), array('controller' => 'orbcats', 'action' => 'add')); ?> </li>
	</ul>
</div>
