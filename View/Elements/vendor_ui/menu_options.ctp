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
	<div class="row">
		<div class="large-12 columns">
			<div class="row panel">
				<div class="large-12 columns">
					<h1>Add A New Menu Option</h1>
					<div class="orbopts form">
						<?php echo $this->Form->create('Orbopt'); ?>
							<div class="row">
								<div class="large-12 columns">
									<?php echo $this->Form->input('title'); ?>
								</div>
							</div>
						<a href="#" class="modal-button full-width med" data-route="orbopt_config/-1/add/save">
							<span class="text">Save & Reload</span>
						</a>
					<?php echo $this->Form->end(); ?>
					</div>
				</div>
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
						<th><a href="#">Option</th>
					<?php foreach($optflags as $of) { echo sprintf("<th><a href='#'>%s</a></th>", strtoupper($of)); }?>
						<th>MENU CATEGORIES</th>
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
						<tr data-opt="<?php echo $oid;?>">
							<td><?php echo $opt[ 'Orbopt' ][ 'title' ] ?></td>
							<?php foreach($optflags as $id => $of) {
								$flag_active = in_array($id, $opt['Orbopt']['flags']);
								$data = array("route" => "orbflag_config/$oid/$id");
								$id = "orbopt-$oid-optflag-$id";
								echo sprintf("<td id='%s' class='optflag' %s>%s</td>", $id, ___dA($data), $flag_active ? $active_check : $inactive_check);
							}?>
							<td>
								<a href="#" data-route="orbopt_optgroup_config/<?php echo $oid;?>/launch">Click to Choose Categories</a>
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