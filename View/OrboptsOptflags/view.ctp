<div class="orboptsOptflags view">
<h2><?php echo __('Orbopts Optflag'); ?></h2>
	<dl>
		<dt><?php echo __('Orbopt'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orboptsOptflag['Orbopt']['title'], array('controller' => 'orbopts', 'action' => 'view', $orboptsOptflag['Orbopt']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Optflag'); ?></dt>
		<dd>
			<?php echo $this->Html->link($orboptsOptflag['Optflag']['title'], array('controller' => 'optflags', 'action' => 'view', $orboptsOptflag['Optflag']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Orbopts Optflag'), array('action' => 'edit', $orboptsOptflag['OrboptsOptflag']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Orbopts Optflag'), array('action' => 'delete', $orboptsOptflag['OrboptsOptflag']['id']), array(), __('Are you sure you want to delete # %s?', $orboptsOptflag['OrboptsOptflag']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts Optflags'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopts Optflag'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orbopts'), array('controller' => 'orbopts', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Orbopt'), array('controller' => 'orbopts', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Optflags'), array('controller' => 'optflags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Optflag'), array('controller' => 'optflags', 'action' => 'add')); ?> </li>
	</ul>
</div>
