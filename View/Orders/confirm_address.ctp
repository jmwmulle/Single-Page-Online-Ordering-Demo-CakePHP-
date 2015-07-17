<?php
	/**
	 * J. Mulle, for app, 12/29/14 2:44 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	$__address = array('firstname'=> null,
	                   'lastname' => null,
	                   'address' => null,
	                   'address_2' => null,
	                   'phone' => null,
	                   'email' => null,
	                   'building_type' => null,
	                   'delivery_instructions' => null,
	                   'postal_code' => null,
	                   'delivery_time' => null,
	                   'city' => null,
	                   'province' => null);
	$cart = $this->Session->read('Cart');
	$logged_in = $this->Session->read('Auth.User') ? true : false;
	$user = $logged_in ? $this->Session->read('Auth.User.User') : array();
	$email = $this->Session->read('Cart.Order.email');
	$address = $this->Session->read('Cart.Order.address');
	if ( is_array($address) ) array_merge($__address, $address);
	if (!is_array($address)) $address = [];
	if (array_key_exists("first_name", $address) ) {
	    $address['firstname'] = $address['first_name'];
		unset($address['first_name']);
	}
	if (array_key_exists("last_name", $address) ) {
		    $address['lastname'] = $address['last_name'];
			unset($address['last_name']);
		}
	$update_command = "session";
?>

<div class="row">
	<div class="large-12 columns">
	<?php echo $this->element("primary_modal/masthead", compact("header", "subheader") );?>
		<div class="row">
			<div class="large-12 columns">
				<div class="row">
					<div class="large-10 columns modal-header">
					<?php if (!$logged_in) {?>
							<span>Already have an address on file? <a href="#" data-route="login/confirm-address">Sign In</a>
								 and load it up!
							</span>
					<?php } else {?>
						<h3 class="inline"> <?php if (array_key_exists('firstname', $user) ) echo $user['firstname']; ?></h3>
						<h3 class="inline"> <?php if (array_key_exists('lastname', $user) ) echo $user['lastname']; ?></h3>
					<?php } ?>
					</div>
					<div class="large-2 columns modal-header">

					</div>
				</div>
				<div id="confirm-address-login-panel" class="row true-hidden">
					<div class="large-12 columns">
						<a href="#" data-route="login/confirm-address/twitter"><span class="icon-twitter"></span></a
						><a href="#" data-route="login/confirm-address/facebook"><span class="icon-facebook"></span></a
						><a href="#" data-route="login/confirm-address/gplus"><span class="icon-gplus"></span></a
						><a href="#" data-route="login/confirm-address/email"><span class="icon-topbar-email"></span></a
						></div>
					</div>
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
				<?php echo $this->Form->input( 'email', array( 'label' => 'E-mail Address',
				                                               'value' => $email)); ?>
			</div>
			<?php if ( array_key_exists('Addresses', $user) ) {?>
			<div class="large-6 columns">
				<?php echo $this->Form->input('User.Address', array('options' => $user['Addresses']));?>
			</div>
			<?php }?>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'address', array(
						'label' => "Street Address Line 1",
						'value' => $address ? $address['address'] : null)); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'address_2', array(
						'label' => "Street Address Line 2 (optional)",
						'value' => $address ? $address['address_2'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php
					$buildings =  array( 'House', 'Apartment', 'Office/Other');
					$selected = $address ? $address['building_type'] : 0;
					echo $this->Form->input( 'building_type', array( 'type'    => 'select',
				                                                       'options' => $buildings,
				                                                       'selected' => $selected )
				);
				?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'postal_code', array(
						'label' => 'Postal Code',
						'value' => $address ? $address['postal_code'] : null)); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'delivery_instructions', array('type'  => 'textarea',
				                                                    'class' =>  array('note'),
					                                                'label' => 'Delivery Instructions',
					                                                'value' => $address ? $address['delivery_instructions'] : null,
				                                                    'placeholder' => "Let our driver know about those hard-to-find stairs, or that pesky pet leopard in your back yard..."
					) );?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns true-hidden">
			<?php echo $this->Form->input( 'delivery_time', array('type' => 'hidden', 'value' => "asap" )); ?>
			<?php echo $this->Form->input( 'confirmation', array('type' => 'hidden', 'value' =>"email" )); ?>
			<?php echo $this->Form->input( 'city', array( 'type' => 'hidden',
		                                              'value' => $address ? $address['city'] : 'Halifax' ) );?>
			<?php echo $this->Form->input( 'province', array( 'type' => 'hidden',
		                                                  'value' => 'Nova Scotia'  ));
			  echo $this->Form->end(); ?>
			</div>
			<div class="large-12 columns">
				<label> &nbsp; </label>
				<div id="submit-order-button-wrapper">
					<a href="#" class="modal-button lrg bisecting cancel left" data-route="confirm_address/cancel/menu">
						<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
					</a
					><a href="#" id="submit-order-address" class="modal-button lrg bisecting confirm right" data-route="confirm_address/submit/menu">
						<span class="text">OK!</span><span class="icon-circle-arrow-r"></span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div id="on-close" class="true-hidden" data-action="reset-user-activity"></div>
</div>
