<?php
	/**
	 * J. Mulle, for app, 12/29/14 2:44 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	$user_addresses = $this->Session->read('Cart.User.Address');
//	if ( empty($user_addresses) ) $user_addresses = false;
	$address = $this->Session->read('Cart.Service.address');
	$address_valid = $this->Session->read('Cart.Service.address_valid');
	$address_set = $this->Session->read('Cart.Service.address_set');
	$auth_user = $this->Session->read('Auth.User');
	$user = $this->Session->read('Cart.User');

	// TEST DATA ONLY
	$auth_user = false;
	$show_user_addresses = true;
	if (!$auth_user or $address_set == "add") $show_user_addresses = false;


?>


	<div class="large-12 columns">
	<?php echo $this->element("primary_modal/masthead", compact("header", "subheader") );?>
		<div class="row">
			<div class="large-12 columns">
				<div class="row">
					<div class="large-10 columns modal-header">
					<?php if ( !$auth_user && SOCIAL_ACTIVE) {?>
							<span>Already have an address on file? <a href="#" data-route="login/confirm-address">Sign In</a>
								 and load it up!
							</span>
					<?php } else {?>
						<h3 class="inline"> <?php if (array_key_exists('firstname', $user) ) echo $user['firstname']; ?></h3>
						<h3 class="inline"> <?php if (array_key_exists('lastname', $user) ) echo $user['lastname']; ?></h3>
						<?php  if ( !empty($user_addresses) ) {?>
						<?=$show_user_addresses ? "" : "|";?>
						<a id="switch-user-address" class="<?=$show_user_addresses ? "fade-out hidden" : "";?>" href="#" data-route="set_user_address/-1/reveal">
							<span class="text">Use An Address On File</span>
						</a>
						<?php }
					} ?>
					</div>
					<div class="large-2 columns modal-header">

					</div>
				</div>
				<?php if ( !$auth_user && SOCIAL_ACTIVE) {?>
				<div id="confirm-address-login-panel" class="row">
					<div class="large-12 columns">
						<a href="#" data-route="login/confirm-address/twitter"><span class="icon-twitter"></span></a
						><a href="#" data-route="login/confirm-address/facebook"><span class="icon-facebook"></span></a
						><a href="#" data-route="login/confirm-address/gplus"><span class="icon-gplus"></span></a
						><a href="#" data-route="login/confirm-address/email"><span class="icon-topbar-email"></span></a
						></div>
					</div>
				</div>
			<?php } ?>
		</div>
		<hr />
		<!-----      USER ADDRESS CARDS ------->
		<div id="user-address-select" class="row <?php if ( !$show_user_addresses ) echo "hidden fade-out";?>">
			<div class="large-12 columns">
			<?php
				foreach( array_chunk($user_addresses, 3) as $user_address_row) {
					echo '<div class="row">';
					echo "<h3>Use An Address On File</h3>";
						foreach ($user_address_row as $address) {
							echo $this->Element('primary_modal/address_card', ['address' => $address] );
						}
					echo '</div>';
				}
			?>
				<div class="row">
					<div class="large-12 columns">
						<label>&nbsp</label>
						<div id="submit-order-button-wrapper">
							<a href="#" class="modal-button lrg bisecting cancel left" data-route="set_delivery_address/cancel/<?=$restore;?>">
								<span class="icon-circle-arrow-l"></span>
								<span class="text">Cancel</span>
							</a>
							<a href="#" id="submit-order-address" class="modal-button lrg bisecting confirm right" data-route="set_user_address/-1/set">
								<span class="icon-add"></span>
								<span class="text">Add New Address</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?=$this->Form->create( 'orderAddress', [ 'action' => false, 'inputDefaults' => ["div" => false] ] );?>
		<?=$this->Form->input( 'id', [ 'type' => 'hidden']);?>

		<!----- ADDRESS FORM ------->
		<div id="user-address-form" class="row <?php if ( $show_user_addresses ) echo "fade-out hidden";?>">
			<div class="large-12 columns">
				<div class="row">
					<div class="large-6 columns">
						<?php echo $this->Form->input( 'firstname', array('value' => $address ? $address['firstname'] : null));?>
					</div>
					<div class="large-6 columns">
						<?php echo $this->Form->input( 'lastname', array('value' => $address ? $address['lastname'] : null));?>
					</div>
				</div>
				<div class="row">
					<div class="large-<?php echo array_key_exists('Addresses', $user) ? "3" : "6";?>  columns">
						<?php echo $this->Form->input( 'phone', array(
								'label' => "Phone Number",
								'value' => $address ? $address['phone'] : null)); ?>
					</div>
					<div class="large-<?php echo array_key_exists('Address', $user) ? "3" : "6";?>  columns">
						<?php echo $this->Form->input( 'email', array( 'label' => 'E-mail Address',
						                                               'value' => $user['email'],
						                                               "class" => $user['email_verified'] ? "verified" : "unverified"
						)); ?>
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
						<?php echo $this->Form->input( 'note', array('type'  => 'textarea',
						                                                    'class' =>  array('note'),
							                                                'label' => 'Delivery Instructions',
							                                                'value' => $address ? $address['note'] : null,
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
							<a href="#" class="modal-button lrg bisecting cancel left" data-route="set_delivery_address/cancel/<?=$restore;?>">
								<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
							</a
							><a href="#" id="submit-order-address" class="modal-button lrg bisecting confirm right" data-route="validate_form/address/<?=$restore;?>">
								<span class="text">OK!</span><span class="icon-circle-arrow-r"></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="on-close" class="true-hidden" data-action="reset-user-activity"></div>