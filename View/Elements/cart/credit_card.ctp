<?php
/**
 * J. Mulle, for app, 7/15/15 10:25 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 *
 * THIS IS STILL ROSS'S TEMPLATE (July 14/15)
 */
?>
<div id="ccbox"> Credit Card Type </div>

<div class="row">
	<div class="col col-sm-3">
		<?php echo $this->Form->input( 'creditcard_number', array( 'class'     => 'form-control ccinput',
		                                                           'maxLength' => 16, 'autocomplete' => 'off' )
		); ?>
	</div>
</div>

<br/>

<div class="row">
	<div class="large-4 columns">
		<?php echo $this->Form->input( 'creditcard_month', array(
				'label'   => 'Expiration Month',
				'class'   => 'form-control',
				'options' => array(
					'01' => '01 - January',
					'02' => '02 - February',
					'03' => '03 - March',
					'04' => '04 - April',
					'05' => '05 - May',
					'06' => '06 - June',
					'07' => '07 - July',
					'08' => '08 - August',
					'09' => '09 - September',
					'10' => '10 - October',
					'11' => '11 - November',
					'12' => '12 - December'
				)
			)
		); ?>
	</div>
	<div class="col col-sm-2">
		<?php echo $this->Form->input( 'creditcard_year', array(
				'label'   => 'Expiration Year',
				'class'   => 'form-control',
				'options' => array_combine( range( date( 'y' ), date( 'y' ) + 10 ), range( date( 'Y' ),
						date( 'Y' ) + 10
					)
				)
			)
		);?>
	</div>
</div>

<div class="row">
	<div class="large-6 columns">
		<?php echo $this->Form->input( 'creditcard_code', array( 'label' => 'Card Security Code',
		                                                         'class' => 'form-control', 'maxLength' => 4 )
		); ?>
	</div>
</div>