<?php
/**
 * J. Mulle, for app, 5/15/15 5:00 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 *
 *
 * JONO: You didn't give a fuck about doing this right so you copied and pasted the relevant code from Orbs/vendor_ui,
 * where the functions below and that dumbass $routes array actually make sense.
 */
if (!function_exists("orb_attribute_routes") ) {
	function orb_attribute_routes($orb_id, $orb_attr) {
		return array( "edit"    => array( 'route' => "orb_config/$orb_id/edit/$orb_attr" ),
		              "cancel" => array( 'route' => "orb_config/$orb_id/cancel/$orb_attr" ),
		              "save"    => array( 'route' => "update_menu/$orb_id/$orb_attr" ) );
	}
}

if (!function_exists("orb_attribute_buttons") ) {
	function orb_attribute_buttons($routes) {
		echo "<div class='button-box'>";
		echo sprintf( '<a href="#" class="modal-button bisecting cancel left" %s>', ___dA( $routes[ 'cancel' ] ) );
		echo '<span class="icon-circle-arrow-l"></span>';
		echo '<span class="text">Cancel</span></a>';
		echo sprintf( '<a href="#" class="modal-button bisecting confirm right" %s>', ___dA( $routes[ 'save' ] ) );
		echo '<span class="text">Save</span>';
		echo '<span class="icon-circle-arrow-r"></span></a>';
		echo '</div>';
	}
}

$oid = $orb['Orb']['id'];
$routes = array("prices"     => orb_attribute_routes( $oid, 'prices' ) );
?>


	<div class="orb-attr display" <?php echo ___dA( $routes[ "prices" ][ 'edit' ] ); ?>>
		<div class="price-rank-display">
	<?php for ( $i = 1; $i <= 5; $i++ ) {
			$d = $orb[ 'Pricedict' ][ "l$i" ];
			$p = $orb[ 'Pricelist' ][ "p$i" ];
			$d_data = array('rank' => $i, 'value' => $d ? $d : "");
			$p_data = array('rank' => $i, 'value' => $p ? $p : "");
			?>
			<div class="price-rank">
				<span <?php echo ___dA($d_data);?> class='pricedict'>
					<?php echo $d ? $d : "&nbsp;"; ?>
				</span>
				<br/>
				<span <?php echo ___dA($p_data); ?> class='pricelist'>
					<?php echo $p ? money_format( "%#3.2n", $orb[ 'Pricelist' ][ "p$i" ] ): "&nbsp;"; ?>
				</span>
			</div>
	<?php }?>
		</div>
	</div>
	<div class="orb-attr edit hidden breakout pricing">
		<form>
			<input type="hidden" name="Orb[id]" value="<?php echo $oid;?>">
		<?php for ( $i = 1; $i <= 5; $i++ ) {
			$p = $orb[ 'Pricelist' ][ "p$i" ];
			$d = $orb[ 'Pricedict' ][ "l$i" ];
			$data = array('rank' => $i);
			?>
			<div class="price-rank-edit">
				<div class="price-value">
					<label>Price <?php echo $i;?></label>
					<span>$&nbsp;</span>
					<?php echo sprintf("<input class='pricelist' type='text' %s name='Pricelist[p$i]' value='$p'>", ___dA($data));?>
				</div>
				<div class="price-label">
					<label>Size Label</label>
					<?php echo sprintf("<select class='pricedict' %s name='Pricedict[l$i]'>", ___dA($data)); ?>
						<option value=''>N/A</option>
					<?php foreach ($pricedicts as $j => $el) {
							if ($d == $el) {
								echo "<option value='$el' selected='selected'>$el</option>";
							} else {
								echo "<option value='$el'>$el</option>";
							}
						}?>
					</select>
				</div>
			</div>
		<?php } ?>
			<div class="price-rank-edit">
				<a href="#" class="modal-button full-height grey-on-white" data-route="update_menu/pricedicts/fetch">
					<span class="icon-add"></span><span class="text">Add More Size Labels</span>
				</a>
			</div>
		</form>
		<?php echo orb_attribute_buttons( $routes[ "prices" ] ); ?>
	</div>