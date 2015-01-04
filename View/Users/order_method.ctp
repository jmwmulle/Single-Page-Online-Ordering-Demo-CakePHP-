<?php
	/**
	 * J. Mulle, for app, 12/29/14 2:44 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */

	$user = $this->Session->read( 'User' );
	$logged_in = false;
	db($this->Session->read());
?>
<div class="row">
	<div class="large-12 columns">
			<?php if ($method == 'delivery') { ?>
		<div class="row">
			<div class="large-4 columns">
				<?php echo $this->element("modal_masthead"); ?>
			</div>
			<div class="large-8 columns">
				<h1>Delivery! Yay for sitting!</h1>
				<h3>But let's confirm your address, yeah?</h3>
			</div>
		</div>
		<div class="row">
			<div class="large-10 columns modal-header">
			<?php if ($logged_in) {?>
					<span>Already have an address on file? <a href="#" class="modal-link" data-route="login-from-modal">Sign In</a>
						 and load it up!
					</span>
			<?php } else { ?>

				<ul class=" disabled inline">
					<li class="inline active activizing">This is my address—update it please!</li>
					<li class="inline activizing">I'm just using this address  for today's order.</li>
				</ul>
			<?php } ?>
			</div>
			<div class="large-2 columns modal-header">
				<a href="#" class="reset-form" data-form="testForm">Reset Form</a> |
			</div>

		</div>
		<hr />
		<?php echo $this->Form->create( 'orderAddress', array( 'action' => false, 'inputDefaults' => array("div" => false) ) ); ?>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'firstname', array(
						'value' => array_key_exists("first_name", $user) ? $user['first_name'] : null)); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'lastname', array(
						'value' => array_key_exists("last_name", $user) ? $user['last_name'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'phone', array(
						'label' => "Phone Number",
						'value' => array_key_exists("phone_number", $user) ? $user['phone_number'] : null)); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'confirmation', array( 'type' => 'select',
				                                                      'label' => "Receive Order Confirmation By",
				                                                      'options' => array("E-mail", "SMS")) ); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'address', array(
						'label' => "Street Address Line 1",
						'value' => array_key_exists("address", $user) ? $user['address'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'address_2', array(
						'label' => "Street Address Line 2 (optional)",
						'value' => array_key_exists("address_2", $user) ? $user['address_2'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'building_type', array( 'type'    => 'select',
				                                                       'options' => array( 'House',
				                                                                           'Apartment',
				                                                                           'Office/Other' ) )
				);
				?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'postal_code', array(
						'label' => 'Postal Code',
						'value' => array_key_exists("postal_code", $user) ? $user['postal_code'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'instructions', array('type'  => 'textarea',
					                                                'label' => 'Delivery Instructions',
				                                                    'placeholder' => "Let our driver know about those hard-to-find stairs, or that pesky pet leopard in your back yard..."
					) );?>
			</div>
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns">
					<?php echo $this->Form->input( 'delivery_time' ); ?>
				<?php echo $this->Form->input( 'city', array( 'type' => 'hidden', 'value' => 'Halifax' ) );?>
				<?php echo $this->Form->input( 'province', array( 'type' => 'hidden', 'value' => 'Nova Scotia' ));
					  echo $this->Form->end(); ?>
					</div>
					<div class="large-12 columns">
						<a href="#" id="submit-order-address" class="box downward rel modal-link modal-submit" data-route="submit_order_address/true">
							<?php echo strtoupper( "OK! To the food!" ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div id="on-close" class="true-hidden" data-action="reset-user-activity"></div>
</div>