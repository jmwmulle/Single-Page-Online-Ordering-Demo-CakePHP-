<?php

	echo $this->Form->create('User', array('action' => 'login'));
	echo $this->Form->inputs(array(
		    'legend' => __('Login'),
			'email',
			'password'
		    ));
	echo $this->Form->end('Login');

?>
<div id="on-close" class="true-hidden" data-action=""></div>
