<?php
	/**
	 * J. Mulle, for app, 12/29/14 2:44 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	$cart = $this->Session->read('Cart');
	$logged_in = $this->Session->read('Auth.User') ? true : false;
	$user = $logged_in ? $this->Session->read('Auth.User.User') : array();
	$address = $this->Session->read('Cart.Order.address');
	$update_command = "session";
?>
<div class="row">
	<div class="large-12 columns">
	<?php echo $this->element("modal_masthead", array(
								"header" => "Delivery! Yay for sitting!",
								"subheader" => "But let's confirm your address, yeah?"));?>
		<div class="row">
			<div class="large-10 columns modal-header">
			<?php if (!$logged_in) {?>
					<span>Already have an address on file? <a href="#" class="modal-link" data-route="login-from-modal">Sign In</a>
						 and load it up!
					</span>
			<?php } else {?>
				<h3 class="inline"> <?php echo $user['firstname']; ?></h3>
				<h3 class="inline"> <?php echo $user['lastname']; ?></h3>
			<?php } ?>
			</div>
			<div class="large-2 columns modal-header">
				<a href="#" class="reset-form" data-form="testForm">Reset Form</a>
			</div>
		</div>
		<hr />
		<?php echo $this->Form->create( 'orderAddress', array( 'action' => false, 'inputDefaults' => array("div" => false) ) );?>
		<?php if (!$logged_in) {?>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'firstname', array('value' => $address ? $address['firstname'] : null));?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'lastname', array('value' => $address ? $address['lastname'] : null));?>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="large-<?php echo array_key_exists('Addresses', $user) ? "3" : "6";?>  columns">
				<?php echo $this->Form->input( 'phone', array(
						'label' => "Phone Number",
						'value' => $address ? $address['phone'] : null)); ?>
			</div>
			<div class="large-<?php echo array_key_exists('Addresses', $user) ? "3" : "6";?>  columns">
				<?php echo $this->Form->input( 'confirmation', array( 'type' => 'select',
				                                                      'label' => "Order Confirmation From",
				                                                      'options' => array("E-mail", "SMS")) ); ?>
			</div>
			<?php if ( array_key_exists('Addresses', $user) ) {?>
			<div class="large-6 columns">
				<?php echo $this->Form->input('User.Address', array('options' => $user['Addresses']));?>
			</div>
			<?php }?>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'address', array(
						'label' => "Street Address Line 1",
						'value' => array_key_exists("address", $address) ? $address['address'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'address_2', array(
						'label' => "Street Address Line 2 (optional)",
						'value' => array_key_exists("address_2", $address) ? $address['address_2'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php
					$buildings =  array( 'House', 'Apartment', 'Office/Other');
					$selected = array_key_exists("building_type", $address) ? $address['building_type'] : 0;
					echo $this->Form->input( 'building_type', array( 'type'    => 'select',
				                                                       'options' => $buildings,
				                                                       'selected' => $selected )
				);
				?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'postal_code', array(
						'label' => 'Postal Code',
						'value' => array_key_exists("postal_code", $address) ? $address['postal_code'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'delivery_instructions', array('type'  => 'textarea',
					                                                'label' => 'Delivery Instructions',
					                                                'value' => $addres ? $address['delivery_instructions'] : null,
				                                                    'placeholder' => "Let our driver know about those hard-to-find stairs, or that pesky pet leopard in your back yard..."
					) );?>
			</div>
			<div class="large-6 columns">
				<div class="row">
					<div class="large-12 columns">
					<?php echo $this->Form->input( 'delivery_time' ); ?>
					<?php echo $this->Form->input( 'city', array( 'type' => 'hidden',
				                                              'value' => array_key_exists('city', $address) ? $address['city'] : 'Halifax' ) );?>
					<?php echo $this->Form->input( 'province', array( 'type' => 'hidden',
				                                                  'value' => 'Nova Scotia'  ));
					  echo $this->Form->end(); ?>
					</div>
					<div class="large-12 columns">
						<a href="#" id="submit-order-address" class="box downward rel modal-submit modal-button" data-route="confirm_address/submit/menu">
							<?php echo strtoupper( "OK! To the food!" ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="on-close" class="true-hidden" data-action="reset-user-activity"></div>
</div>
