<?php
	/**
	 * J. Mulle, for app, 5/21/15 3:47 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	function orb_attribute_routes( $orb_id, $orb_attr ) {
		return array( "edit"   => array( 'route' => "orb_config/$orb_id/edit/$orb_attr" ),
		              "cancel" => array( 'route' => "orb_config/$orb_id/cancel/$orb_attr" ),
		              "save"   => array( 'route' => "update_menu/$orb_id/$orb_attr" ) );
	}

	function orb_attribute_buttons( $routes) {
		$str = "";
		$str .="<div class='button-box'>";
		$str .=sprintf( '<a href="#" class="modal-button bisecting cancel left" %s>', ___dA( $routes[ 'cancel' ] ) );
		$str .='<span class="icon-cancel"></span>';
		$str .=sprintf( '<a href="#" class="modal-button bisecting confirm right" %s>', ___dA( $routes[ 'save' ] ) );
		$str .='<span class="text">Save</span>';
		$str .='<span class="icon-circle-arrow-r"></span></a>';
		$str .='</div>';
		return $str;
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
			<a href="#" class="modal-button full-width" data-route="orb_edit/-1/add/create">
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
					foreach ( $orbs as $index => $orb ) {
						if ( $_SERVER['HTTP_HOST'] == "localhost" and $index > 10) break;
						if ( $orb[ 'Orb' ][ 'deprecated' ] ) continue;

						$oid    = $orb[ 'Orb' ][ 'id' ];
						$o      = $orb[ 'Orb' ];
						$data   = [ 'orb-id'          => $oid,
						                 'orb-opts'        => Hash::extract( $orb[ 'Orbopt' ], "{n}.id" ),
						                 'orbopts-orbcats' => [] ];
						$routes = [ "title"       => orb_attribute_routes( $oid, 'title' ),
					                 "description" => orb_attribute_routes( $oid, 'description' ),
					                 "orbcat"      => orb_attribute_routes( $oid, 'orbcat' ),
					                 "prices"      => orb_attribute_routes( $oid, 'prices' ) ];
						$buttons = ["title" => orb_attribute_buttons( $routes[ 'title' ] ),
					                "description" => orb_attribute_buttons($routes['description']),
					                "orbcat" => orb_attribute_buttons($routes['orbcat']),
					                "prices" => orb_attribute_buttons($routes['prices'])];
						?>
						<tr id="<?= $oid; ?>" <?= ___dA( $data ); ?>>

							<!----------------------------------  TITLE  ------------------------------------------>
							<?php if ( $printing[ 'title' ] ) { ?>
								<td id='orb-<?=$oid;?>-title' <?=___dA( $routes[ 'title' ][ 'edit' ] );?>>
									<div class="orb-attr display"><?= $o[ 'title' ]; ?></div>
									<div class="orb-attr edit hidden">
										<form>
											<?= sprintf( '<input type="text" name="Orb[title]" value="%s"/>', $o[ 'title' ] ); ?>
										</form>
										<?=$buttons['title'];?>
									</div>
								</td>
							<?php } ?>

							<!----------------------------------  DESCRIPTION  ------------------------------------->
							<?php if ( $printing[ 'description' ] ) { ?>
								<td id='orb-<?=$oid;?>-description' <?= ___dA( $routes[ 'description' ][ 'edit' ] ); ?>>
									<div class="orb-attr display">
										<?= $o[ 'description' ] ? $o[ 'description' ] : "<span class='subtitle'>Click to Add</span>" ?>
									</div>
									<div class="orb-attr edit hidden">
										<form>
											<textarea name="Orb[description]"><?= $o[ 'description' ]; ?></textarea>
										</form>
										<?=$buttons['description' ];?>
									</div>
								</td>
							<?php } ?>

							<!------------------------------------  ORBCAT  --------------------------------------->
							<?php if ( $printing[ 'orbcat' ] ) { ?>
								<td id='orb-<?=$oid;?>-orbcat' <?=___dA( $routes[ 'orbcat' ][ 'edit' ] ); ?>>
									<div class="orb-attr display">
										<?= $orb[ 'Orbcat' ][ 'full_title' ]; ?>
									</div>
									<div class="orb-attr edit hidden">
										<form>
											<select name="Orb[orbcat]">
												<?php foreach ( $orbcats as $id => $oc ) { ?>
													<?= sprintf( "<option value='$id'%s>%s</option>", $o[ 'orbcat_id' ] == $id ? " selected" : null, $oc); ?>
												<?php } ?>
											</select>
										</form>
										<?=$buttons['orbcat' ];?>
									</div>
								</td>
							<?php } ?>

							<!------------------------------------  PRICES  --------------------------------------->
							<?php if ( $printing[ 'price' ] ) {
								$price_buttons = orb_attribute_buttons($routes['prices'], false);
								?>
								<td id="<?="orb-$oid-prices";?>" <?=___dA( $routes[ "prices" ][ 'edit' ] ); ?>>
									<?=$this->element( 'vendor_ui/prices', compact('orb', 'pricedicts', 'price_buttons')); ?>
								</td>
							<?php } ?>
							<!------------------------------------  ORBOPTS  -------------------------------------->
							<?php
								if ( $printing[ 'orbopt' ] ) {
									$data = [ 'name'    => 'orbopts',
								              'orbopts' => Hash::extract( $orb[ 'Orbopt' ], "{n}.id" ),
								              "route" => "orbopt_config/$oid/launch" ];?>
									<td id="orb-<?=$oid;?>-orbopts-cell" <?= ___dA( $data ) ?> <?= ___cD( "invisiform" ); ?> >
										<a href="#">Configure Options</a>
									</td>
								<?php } ?>
							<!------------------------------------  DELETION  -------------------------------------->
							<td>
								<a href="#" class="modal-button lrg delete full-width text-center" data-route="orb_edit/<?=$oid;?>/delete/confirm">
									<span class="icon-cancel textless"></span>
								</a>

								<div id="delete-orb-<?=$oid;?>" class="breakout hidden">
									<h4>Are you sure you want to delete "<?=$o[ 'title' ];?>"?</h4>
									<a href="#" class="modal-button bisecting confirm right"
									   data-route="orb_edit/<?= $oid; ?>/delete/delete">
										<span class="text">Confirm</span><span class="icon-circle-arrow-r"></span>
									</a>
									<a href="#" class="modal-button bisecting cancel left"
									   data-route="orb_edit/<?= $oid; ?>/delete/cancel">
										<span class="icon-circle-arrow-l"></span>
										<span class="text">Cancel</span>
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