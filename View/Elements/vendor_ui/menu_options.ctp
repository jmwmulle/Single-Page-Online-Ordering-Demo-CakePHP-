<?php
/**
 * J. Mulle, for app, 5/21/15 4:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<div id="menu-options-tab">
	<!-- New Orbopt Price List form (hidden, breakout) -->
	<div class="row">
		<div class="large-12 columns">
			<div class="panel">
				<h1>Add Option Pricing</h1>
				<div id='orbopt-pricelist-add' class="orbopts form">
					<form>
						<label>Label  (ie. a handy way to re-use this price scheme later)</label>
						<input type="text" name="Pricelist[label]">
					<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
						<div class="price-rank-edit">
							<div class="price-value">
								<label>Price <?=$i;?></label>
								<span>$&nbsp;</span>
								<input class='pricelist' type='text' name='Pricelist[p<?=$i;?>]' value=''>
							</div>
						</div>
					<?php } ?>
					<a href="#" class="modal-button med full-width" data-route="orbopt_pricelist/save">
						<span class="text">Save</span>
					</a>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Menu Options tab proper -->
	<div class="row">
		<div class="large-12 columns">
			<div class="panel">
				<h1>Add A New Menu Option</h1>
				<div class="orbopts form">
					<?=$this->Form->create('Orbopt'); ?>
					<?=$this->Form->input('title', ['label' => 'Menu Title (ie. what *customers* see)']); ?>
					<?=$this->Form->input('vendor_title', ['label' => 'Vendor Title (ie. what *you* see)']); ?>
					<a href="#" class="modal-button full-width med" data-route="orbopt_config/-1/add/save">
						<span class="text">Save & Reload</span>
					</a>
				</div>
			<?=$this->Form->end(); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<?php
				$opt_cats = array( "meat", "veggie", "cheese", "sauce", "condiment" );
				$opt_select = "<select>";
				foreach ( $opt_cats as $oc ) {
					$opt_select .= sprintf( "<option>%s</option>", $oc );
				}
				$opt_select .= "</select>";?>
			<table role="grid" id="menu-options-table">
				<thead>
					<tr>
						<th><a href="#">Vendor Title</th>
						<th><a href="#">Menu Title</th>
					<?php foreach($optflags as $of) { echo sprintf("<th><a href='#'>%s</a></th>", strtoupper($of)); }?>
						<th>MENU CATEGORIES</th>
						<th>Pricing</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $orbopts as $opt ) {
						if ($opt["Orbopt"]["deprecated"]) continue;
						$oid = $opt['Orbopt']['id'];
						$active_check = "<span class='icon-check-mark active'></span>";
						$inactive_check = "<span class='icon-check-mark inactive'></span>";
						?>
						<tr data-opt="<?=$oid;?>">
							<td id='orbopt-<?=$oid;?>-vendor-title'>
								<div class="orbopt-attr display" data-route="orbopt_edit/<?=$oid;?>/edit/vendor-title">
									<?=$opt[ 'Orbopt' ][ 'vendor_title' ] ?>
								</div>
								<div class="orbopt-attr edit fade-out hidden">
									<form>
										<input type="text" name="Orbopt[vendor_title]" value="<?=$opt['Orbopt']['vendor_title'];?>" >
									</form>
									<div class="button-box">
										<a href="#" class="modal-button sml bisecting cancel right" data-route="orbopt_edit/<?=$oid;?>/cancel/vendor-title">
											<span class="icon-cancel textless"></span>
										</a>
										<a href="#" class="modal-button sml bisecting confirm left" data-route="orbopt_edit/<?=$oid;?>/save/vendor-title">
											<span class="text">Save</span>
										</a>
									</div>
								</div>
							</td>
							<td id='orbopt-<?=$oid;?>-title'>
								<div class="orbopt-attr display" data-route="orbopt_edit/<?=$oid;?>/edit/title">
									<?=$opt[ 'Orbopt' ][ 'title' ] ?>
								</div>
								<div class="orbopt-attr edit fade-out hidden">
									<form>
										<input type="text" name="Orbopt[title]" value="<?=$opt['Orbopt']['title'];?>" >
									</form>
									<div class="button-box">
										<a href="#" class="modal-button sml bisecting cancel right" data-route="orbopt_edit/<?=$oid;?>/cancel/title">
											<span class="icon-cancel textless"></span>
										</a>
										<a href="#" class="modal-button sml bisecting confirm left" data-route="orbopt_edit/<?=$oid;?>/save/title">
											<span class="text">Save</span>
										</a>
									</div>
								</div>
							</td>
							<?php foreach($optflags as $id => $of) {
								$flag_active = in_array($id, $opt['Orbopt']['flags']);
								$data = array("route" => "orbflag_config/$oid/$id");
								$id = "orbopt-$oid-optflag-$id";
								$pl_id = $opt['Pricelist']['id'];
								echo sprintf("<td id='%s' class='optflag' %s>%s</td>", $id, ___dA($data), $flag_active ? $active_check : $inactive_check);
							}?>
							<td>
								<a href="#" data-route="orbopt_optgroup_config/<?php echo $oid;?>/launch">Click to Choose Categories</a>
							</td>
							<td id="orbopt-<?=$oid;?>-pricing">
								<form>
									<select name="Orbopt[pricelist_id]" data-changeroute="orbopt_edit/<?=$oid;?>/edit/pricing">
										<?php foreach ($opt_pricelists as $pl) {
											$prices = array_slice($pl, 1, -1);
											foreach ($prices as $rank => $p) {
												$prices[$rank] = $p ? money_format( "%#3.2n", $p ) : null;
											}
											$p_string = implode(", ", array_filter($prices));
											if ($pl_id == $pl['id']) {
												echo sprintf("<option selected='selected' data-default value='%s'>%s: %s</option>", $pl['id'], $pl['label'], $p_string);
											} else {
												echo sprintf("<option value='%s'>%s: %s</option>", $pl['id'], $pl['label'], $p_string);
											}
										}?>
									</select>
								</form>
							<td>
								<a href="#" class="modal-button lrg delete full-width text-center" data-route="orbopt_config/<?php echo $oid; ?>/delete/confirm">
									<span class="icon-cancel textless"></span>
								</a>

								<div id="delete-orbopt-<?php echo $oid; ?>" class="breakout hidden">
									<h4>Are you sure you want to delete "<?php echo $opt['Orbopt'][ 'title' ]; ?>"?</h4>
									<a href="#" class="modal-button bisecting confirm right"
									   data-route="orbopt_config/<?php echo $oid; ?>/delete/delete">
										<span class="text">Confirm</span><span class="icon-circle-arrow-r"></span>
									</a>
									<a href="#" class="modal-button bisecting cancel left"
									   data-route="orbopt_config/<?php echo $oid; ?>/delete/cancel">
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