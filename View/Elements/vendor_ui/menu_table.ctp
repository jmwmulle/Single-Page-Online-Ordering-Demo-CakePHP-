<?php
	/**
	 * J. Mulle, for app, 5/21/15 3:47 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
if (!function_exists("orb_attribute_routes") ) {
	function orb_attribute_routes($orb_id, $orb_attr) {
		return array( "edit"   => array( 'route' => "orb_config/$orb_id/edit/$orb_attr" ),
		              "cancel" => array( 'route' => "orb_config/$orb_id/cancel/$orb_attr" ),
		              "save"   => array( 'route' => "update_menu/$orb_id/$orb_attr" ) );
	}
}
if (!function_exists("orb_attribute_buttons") ) {
	function orb_attribute_buttons($routes) {
		echo "<div class='button-box'>";
		echo sprintf( '<a href="#" class="modal-button sml bisecting cancel right" %s>', ___dA( $routes[ 'cancel' ] ) );
		echo '<span class="icon-cancel textless"></span></a>';
		echo sprintf( '<a href="#" class="modal-button sml bisecting confirm left" %s>', ___dA( $routes[ 'save' ] ) );
		echo '<span class="text">Save</span></a></div>';
	}
}
	$printing = array( 'title'       => true,
	                   'description' => true,
	                   'orbcat'      => true,
	                   'price'       => true,
	                   'orbopt'      => true );
?>
<div id="menu-tab">
	<div class="row">
		<div class="large-12 columns">
			<a href="#" class="modal-button full-width" data-route="orb/-1/add/create">
				<span class="icon-add"></span><span class="text">Add New Menu Item</span>
			</a>
			<br/>
			<br/>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<table role="grid" id="menu-table">
				<thead>
				<tr>
					<th><a href="#">Name</a></th>
					<th><a href="#">Description</a></th>
					<th><a href="#">Category</a></th>
					<th><a href="#">Sizes & Prices</a></th>
					<th><a href="#">Configuration</a></th>
					<th>&nbsp;</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$orb_count = 0;
					foreach ( $orbs as $orb ) {
						$orb_count++;
						if ($orb_count > 10) break;
						if ( $orb[ 'Orb' ][ 'deprecated' ] ) {
							continue;
						}
						$oid    = $orb[ 'Orb' ][ 'id' ];
						$o      = $orb[ 'Orb' ];
						$data   = array( 'orb-id'          => $oid,
						                 'orb-opts'        => Hash::extract( $orb[ 'Orbopt' ], "{n}.id" ),
						                 'orbopts-orbcats' => array() );
						$routes = array( "title"       => orb_attribute_routes( $oid, 'title' ),
						                 "description" => orb_attribute_routes( $oid, 'description' ),
						                 "orbcat"      => orb_attribute_routes( $oid, 'orbcat' ),
						                 "prices"      => orb_attribute_routes( $oid, 'prices' ) );
						?>
						<tr id="<?php echo $oid; ?>" <?php echo ___dA( $data ); ?>>

							<!----------------------------------  TITLE  ------------------------------------------>
							<?php if ( $printing[ 'title' ] ) { ?>
								<td <?php echo sprintf( "id='orb-$oid-title' data-initial='%s'", $o[ 'title' ] ); ?>>
									<div
										class="orb-attr display" <?php echo ___dA( $routes[ 'title' ][ 'edit' ] ); ?>>
										<?php echo $o[ 'title' ]; ?>
									</div>
									<div class="orb-attr edit hidden">
										<form>
											<?php echo sprintf( '<input type="text" name="Orb[title]" value="%s"/>', $o[ 'title' ] ); ?>
										</form>
										<?php echo orb_attribute_buttons( $routes[ 'title' ] ); ?>
									</div>
								</td>
							<?php } ?>

							<!----------------------------------  DESCRIPTION  ------------------------------------->
							<?php if ( $printing[ 'description' ] ) { ?>
								<td <?php echo sprintf( "id='orb-$oid-description' data-initial='%s'", $o[ 'description' ] ); ?>>
									<div
										class="orb-attr display" <?php echo ___dA( $routes[ 'description' ][ 'edit' ] ); ?>>
										<?php echo $o[ 'description' ] ? $o[ 'description' ] : "<span class='subtitle'>Click to Add</span>" ?>
									</div>
									<div class="orb-attr edit hidden">
										<form>
											<textarea
												name="Orb[description]"><?php echo $o[ 'description' ]; ?></textarea>
										</form>
										<?php echo orb_attribute_buttons( $routes[ 'description' ] ); ?>
									</div>
								</td>
							<?php } ?>

							<!------------------------------------  ORBCAT  --------------------------------------->
							<?php if ( $printing[ 'orbcat' ] ) { ?>
								<td <?php echo sprintf( "id='orb-$oid-orbcat' data-initial='%s'", $o[ 'orbcat_id' ] ); ?>>
									<div
										class="orb-attr display" <?php echo ___dA( $routes[ 'orbcat' ][ 'edit' ] ); ?>>
										<?php echo $orb[ 'Orbcat' ][ 'full_title' ]; ?>
									</div>
									<div class="orb-attr edit hidden">
										<form>
											<select name="Orb[orbcat]">
												<?php foreach ( $orbcats as $id => $oc ) {
													if ( $o[ 'orbcat_id' ] == $id ) {
														echo "<option value='$id' selected='selected'>$oc</option>";
													}
													else {
														echo "<option value='$id'>$oc</option>";
													}
												}?>
											</select>
										</form>
										<?php echo orb_attribute_buttons( $routes[ 'orbcat' ] ); ?>
									</div>
								</td>
							<?php } ?>

							<!------------------------------------  PRICES  --------------------------------------->
							<?php if ( $printing[ 'price' ] ) { ?>
								<td id="<?php echo "orb-$oid-prices"; ?>">
									<?php echo $this->element( 'vendor_ui/prices', array( 'orb'        => $orb,
									                                                      'pricedicts' => $pricedicts )
									); ?>
								</td>
							<?php } ?>
							<!------------------------------------  ORBOPTS  -------------------------------------->
							<?php
								if ( $printing[ 'orbopt' ] ) {
									$data = array( 'name'    => 'orbopts',
									               'orbopts' => Hash::extract( $orb[ 'Orbopt' ], "{n}.id" ) );?>
									<td id="orb-<?php echo $oid; ?>-orbopts-cell" <?php echo ___dA( $data ) ?> <?php echo ___cD( "invisiform" ); ?> >
										<a href="#" data-route="<?php echo "orbopt_config/$oid/launch"; ?>">Configure
											Options</a>
									</td>
								<?php } ?>
							<!------------------------------------  DELETION  -------------------------------------->
							<td>
								<a href="#" class="modal-button lrg delete full-width text-center"
								   data-route="orb/<?php echo $oid; ?>/delete/confirm">
									<span class="icon-cancel textless"></span>
								</a>

								<div id="delete-orb-<?php echo $oid; ?>" class="breakout hidden">
									<h4>Are you sure you want to delete "<?php echo $o[ 'title' ]; ?>"?</h4>
									<a href="#" class="modal-button bisecting confirm right"
									   data-route="orb/<?php echo $oid; ?>/delete/delete">
										<span class="text">Confirm</span><span class="icon-circle-arrow-r"></span>
									</a>
									<a href="#" class="modal-button bisecting cancel left"
									   data-route="orb/<?php echo $oid; ?>/delete/cancel">
										<span class="icon-circle-arrow-l"></span><span
											class="text">Cancel</span>
									</a>
								</div>
							</td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>