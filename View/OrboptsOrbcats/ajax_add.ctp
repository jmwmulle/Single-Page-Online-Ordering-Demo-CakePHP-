<?php
/**
 * J. Mulle, for app, 6/7/15 6:30 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

//db($orbopt);
$oid =  $orbopt['Orbopt']['id'];
?>
	<div id="orbopt-optgroup-selection-template" class="orbopt-optgroup-config-pane slide-up">
		<div class="row">
			<div class="large-12 columns">
				<h2 class="text-center">Set Menu Categories For <span class='subheader'>"<?= $orbopt['Orbopt']['title'];?>"</span></h2>
				<a href="#" class="modal-button lrg bisecting cancel left" data-route="close_modal/primary">
					<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
				</a
				><a href="#" class="modal-button lrg bisecting confirm right" data-route="orbopt_optgroup_config/<?php echo $oid ?>/save">
					<span class="text">Save</span><span class="icon-circle-arrow-r"></span>
				</a>
				<p>Click a menu category to add it to this menu option. Once added "<?= $orbopt['Orbopt']['title'];?>" will be
				available by default to any menu item of the chosen category.</p>

				<p>
					<em>Example:</em><br />
				By clicking on "Burgers" below and then saving, all of the burgers in the menu (and new ones you might create)
				will now show <?= $orbopt['Orbopt']['title'];?> as an option when ordering.
				</p>

				<form id="orbopt-optgroup-config-form">
					<div id="individual-optgroups" class="content">
						<ul class="large-block-grid-6">
						<?php
							foreach($orbopt_optgroups as $og_id => $og_name) {
								$data = array('route' => "orbopt_optgroup_config/$og_id/toggle");
								$classes = array('orbopt-optgroup');
								if (in_array($og_id, $orbopt['Orbcat']) ) $classes[] = 'active';
								echo sprintf("<li id='optgroup-%s-label' %s %s>", $og_id, ___dA($data), ___cD($classes) );?>
									<span class="label <?= in_array('active', $classes) ? 'success' : 'secondary';?> "><?= $og_name; ?></span>
									<input type="hidden" name="OrboptOrbcat[<?= $og_id;?>]" value="<?= in_array('active', $classes);?>" />
								</li>
						<?php } ?>
						</ul>
					</div>
				</form>
			</div>
		</div>
	</div>