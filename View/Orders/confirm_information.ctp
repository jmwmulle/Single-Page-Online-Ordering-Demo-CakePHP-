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


?>


<?php echo $this->element("primary_modal/masthead", compact("header", "subheader") );?>
<div class="row">
<?php if ( !$auth_user && SOCIAL_ACTIVE):?>
	<div class="large-2 columns modal-header">
		<span>Have an  account? <a href="#" data-route="login/confirm-address">Sign In</a>
			 and skip the form!
		</span>
	<div class="large-10 columns">
		<a href="#" data-route="login/confirm-address/twitter"><span class="icon-twitter"></span></a
		><a href="#" data-route="login/confirm-address/facebook"><span class="icon-facebook"></span></a
		><a href="#" data-route="login/confirm-address/gplus"><span class="icon-gplus"></span></a
		><a href="#" data-route="login/confirm-address/email"><span class="icon-topbar-email"></span></a
		></div>
	</div>
<?php else: ?>
	<div class="large-12 columns modal-header">Just in case we need to contact you about your order before you get here, let's get your name and a contact number.</div>
<?php endif; ?>
</div>
<hr />
<!----- ADDRESS FORM ------->
<div class="row">
	<div id="user-address-form" class="">
	<?=$this->Form->create( 'orderInformation', [ 'action' => false, 'inputDefaults' => ["div" => false] ] );?>
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
							'value' => $address ? $address['phone'] : null,
					)); ?>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<label> &nbsp; </label>
					<div id="submit-order-button-wrapper">
						<a href="#" class="modal-button lrg bisecting cancel left" data-route="set_delivery_address/cancel/<?=$restore_route;?>">
							<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
						</a
						><a href="#" id="submit-order-address" class="modal-button lrg bisecting confirm right" data-route="validate_form/pickup_information/<?=$restore_route;?>">
							<span class="text">OK!</span><span class="icon-circle-arrow-r"></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	  <?=$this->Form->end(); ?>
	</div>
</div>
<div id="on-close" class="true-hidden" data-action="reset-user-activity"></div>