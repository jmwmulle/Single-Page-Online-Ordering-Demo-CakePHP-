<?php
	/**
	 * J. Mulle, for app, 2/2/15 5:49 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */

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
						<li><a href="#specials-tab">Specials</a></li>
					</ul>
				</div>
			</div>

			<!------------------------------------- TAB 1: VENDOR UI ------------------------------->
			<?=$this->Element( 'vendor_ui/vendor_home', [ 'system'   => $system, 'closing' => "11PM"]); ?>

			<!------------------------------------- TAB 2: MENU ------------------------------------>

			<?=$this->Element( 'vendor_ui/menu_table', ['orbs' => $orbs, 'pricedicts' => $pricedicts]);?>

			<!------------------------------------- TAB 3: MENU OPTIONS ---------------------------->
			<?=$this->Element( 'vendor_ui/menu_options', [  'optflags' => $optflags,
	                                                        'orbopts'  => $orbopts,
	                                                        'orbopts_groups' => $orbopts_groups,
	                                                        'opt_pricelists' => $opt_pricelists
				]
			);?>

			<!------------------------------------- TAB 4: SPECIALS -------------------------------->
			<?=$this->Element( 'vendor_ui/specials', compact('specials'));?>

		</div>
	</div>
</div>

<?php
	$this->Html->scriptStart( array( 'inline' => false, 'block' => 'vendor' ) );
	echo sprintf( "var orbcats = %s", json_encode( $orbcats ) );
	$this->Html->scriptEnd();
?>
