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
$oid = $orb['Orb']['id'];
?>
<!---    DISPLAY   ----->
<div class="orb-attr display">
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

<!---    EDIT    ----->
<div class="orb-attr edit hidden breakout pricing">
	<div class="row">
		<div class="large-12 columns">
			<div class="row">
				<div class="large-12 columns">
					<a href="#" class="modal-button full-width" data-route="update_menu/pricedicts/fetch">
						<span class="icon-add"></span><span class="text">Add More Size Labels</span>
					</a>
				</div>
			</div>

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
			</form>
			<?=$price_buttons;?>
		</div>
	</div>
</div>