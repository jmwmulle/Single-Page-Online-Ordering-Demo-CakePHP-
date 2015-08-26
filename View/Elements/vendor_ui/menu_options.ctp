<?php
/**
 * J. Mulle, for app, 5/21/15 4:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
//	db($orbopts);
?>
<div id="menu-options-tab">
	<!-- Menu Options tab proper -->
	<div class="row">
		<div class="large-12 columns">
			<br />
			<a href="#" class="modal-button full-width med" data-route="orbopt_edit/-1/breakout/add_opt">
				<span class="icon-add"></span>
				<span>Add A New Menu Option</span>
			</a>
			<div id="orbopt-add-breakout" class="panel breakout hidden">
				<h1>Add A New Menu Option</h1>
				<div class="orbopts form">
					<?=$this->Form->create('Orbopt'); ?>
					<?=$this->Form->input('title', ['label' => 'Menu Title (ie. what *customers* see)']); ?>
					<?=$this->Form->input('vendor_title', ['label' => 'Vendor Title (ie. what *you* see)']); ?>
					<a href="#" class="modal-button bisecting confirm right" data-route="orbopt_config/-1/add/save">
						<span class="text">Save & Reload</span>
						<span class="icon-circle-arrow-r"></span>
					</a>
					<a href="#" class="modal-button bisecting cancel left" data-route="orbopt_edit/-1/breakout/add_opt">
						<span class="icon-circle-arrow-l"></span>
						<span class="text">Cancel</span>
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
				<?php
					$opt_count = 0;
					foreach ( $orbopts as $opt ) {
						$opt_count++;
//						if ($opt_count == 10) break;
						if ($opt["Orbopt"]["deprecated"]) continue;
						$oid = $opt['Orbopt']['id'];
						$active_check = "<span class='icon-check-mark active'></span>";
						$inactive_check = "<span class='icon-check-mark inactive'></span>";
						?>
						<tr data-opt="<?=$oid;?>">
							<td id='orbopt-<?=$oid;?>-vendor-title' data-route="orbopt_edit/<?=$oid;?>/edit/vendor-title">
								<div class="orbopt-attr display">
									<?=$opt[ 'Orbopt' ][ 'vendor_title' ] ?  $opt[ 'Orbopt' ][ 'vendor_title' ] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"?>
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
							<td id='orbopt-<?=$oid;?>-title' data-route="orbopt_edit/<?=$oid;?>/edit/title">
								<div class="orbopt-attr display" >
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
							<td data-route="orbopt_optgroup_config/<?php echo $oid;?>/launch">
								<a href="#">Choose...</a>
							</td>
							<td id="orbopt-<?=$oid;?>-pricing" data-route="orbopt_pricelist/launch/false/<?=$oid;?>">
								<a href="#">Choose...</a>
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