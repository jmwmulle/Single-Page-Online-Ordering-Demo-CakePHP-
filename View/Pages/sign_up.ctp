<?php
/**
 * J. Mulle, for app, 1/3/15 5:54 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>

<div class="row">
	<div class="large-4 columns">
		<?php echo $this->element('modal_masthead');?>
	</div>
	<div class="large-8 columns">
		<h1>Xtreme Membership!</h1>
		<h3>We will never share your infos. Ever.</h3>
	</div>
</div>
<div class="row">
	<div class="large-12 text-center columns">
		<h2>Sign Up with:</h2>
	</div>
</div>
<div id="registration-method-bar" class="row text-center">
	<div class="large-3 columns">
		<a href="#" class="register-link email" data-route="register/email"><span class="icon-original-pizzas"></span></a>
	</div>
	<div class="large-3 columns">
		<a href="#" class="register-link twitter" data-route="register/twitter"><span class="icon-twitter"></span></a>
	</div>
	<div class="large-3 columns">
		<a href="#" class="register-link facebook" data-route="register/facebook"><span class="icon-facebook"></span></a>
	</div>
	<div class="large-3 columns">
		<a href="#" class="register-link gplus" data-route="register/gplus"><span class="icon-gplus"></span></a>
	</div>
</div>
<div class="deferred-content slide-left">
	<?php echo $this->Form->create('Users', array('action' => false, 'inputDefaults' => array("div" => false)));?>
	<div id="register-by-email-form" class="row">
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'firstname', array("label" => "First Name")); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'lastname', array('label' => "Last Name")); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'phone', array('label' => "Phone Number")); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $this->Form->input( 'email', array('label' => "E-Mail Address"));?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'address', array('label' => "Street Address Line 1")); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->input( 'address_2', array('label' => "Street Address Line 2 (optional)")); ?>
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
				<?php echo $this->Form->input( 'postal_code', array('label' => 'Postal Code',)); ?>
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
						<?php echo $this->Form->input( 'confirmation', array( 'type' => 'select',
					                                                      'label' => "I'd Prefer to Receive Order Confirmation By",
					                                                      'options' => array("E-mail", "SMS")) ); ?>

					</div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<a href="#" id="submit-order-address" class="box downward rel modal-link modal-submit" data-route="register/email/submit">
							<?php echo strtoupper( "OK! To the food!" ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>