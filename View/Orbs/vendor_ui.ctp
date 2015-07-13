<?php
	/**
	 * J. Mulle, for app, 2/2/15 5:49 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */


//db($orbs);
	//pr($optflags);
//		db($orbopts);
?>

<div id="vendor-ui" class="row">
	<div class="large-12 columns">
		<div id="ui-tabs">
			<div class="row">
				<div class="large-12 columns">
					<ul>
						<li><a href="#vendor-home-tab">Home</a></li>
						<li><a href="#menu-tab">Menu</a></li>
						<li><a href="#menu-options-tab">Menu Options</a></li>
					</ul>
				</div>
			</div>

			<!------------------------------------- TAB 1: VENDOR UI ------------------------------->
			<?php //echo $this->Element('vendor_ui/vendor_home', array('store' => $this->get('store_status'), 'delivery' => $this->get('delivery_status')));?>
			<?php echo $this->Element( 'vendor_ui/vendor_home', array( 'store'   => true, 'delivery' => false,
			                                                           'closing' => "11PM" )
			); ?>

			<!------------------------------------- TAB 2: MENU ------------------------------------>

			<?php echo $this->Element( 'vendor_ui/menu_table', array( 'orbs'       => $orbs,
			                                                          'pricedicts' => $pricedicts,
				)
			);?>

			<!------------------------------------- TAB 3: MENU OPTIONS ---------------------------->
			<?php echo $this->Element( 'vendor_ui/menu_options', array( 'optflags' => $optflags,
			                                                            'orbopts'  => $orbopts,
			                                                            'orbopts_groups' => $orbopts_groups,
			                                                            'opt_pricelists' => $opt_pricelists
				)
			);?>

		</div>
	</div>
</div>

<?php
	$this->Html->scriptStart( array( 'inline' => false, 'block' => 'vendor' ) );
	echo sprintf( "var orbcats = %s", json_encode( $orbcats ) );
	$this->Html->scriptEnd();
?>
