<?php
/**
 * J. Mulle, for app, 5/4/15 6:03 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
//	db($orbopts);
?>
<div id="orbopt-selection-template" class="orbopt-config-pane slide-up">
	<div class="row">
		<div class="large-12 columns">
			<h2 class="text-center">Set Options For <span class='subheader'>"<?php echo $orb['Orb']['title'];?>"</span></h2>
			<a href="#" class="modal-button lrg bisecting cancel left" data-route="close_modal/primary">
				<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
			</a
			><a href="#" class="modal-button lrg bisecting confirm right" data-route="update_menu/<?php echo $orb['Orb']['id'];?>/orbopts">
				<span class="text">Save</span><span class="icon-circle-arrow-r"></span>
			</a>
			<form class="orbopt-config-form">
				<div id="orboopt-groups" class="content">
					<p>Toppings in green will be available to customers. Toppings in yellow will be selected by default <em>and</em> included in the price of the item.</p>
					<p>Customers will be able to swap any default toppings  of equal or less price from the same general category (ie. toppings, cheeses and sauces).<span class="true-hidden" title="Choosing 'marinara sauce' here sets marinara by default in the customer's menu. Customers will be able to change this to another sauce, but they will not be able to remove their sauce and add a cheese or normal topping">Example</span></p>
					<span><em>Choosing from this list automatically selects all toppings normally available to items in the chosen menu category:</em></span>
					<select id="orbgroup" name="orbgroup"  data-changeroute="orbopt_config/-1/toggle_group">
					<?php
						foreach($orbopts_groups as $id => $name) { echo sprintf("<option value='%s'>%s</option>", $id, $name); }?>
						<option value="-1" selected="selected">---</option>
					</select>
				</div>
				<div class="row">
					<?php foreach (['opt', 'premium', 'cheese', 'sidesauce'] as $count) {?>
					<div class="large-3 columns">
						<label><?=ucfirst($count);?> Count</label>
						<select name="Orb[<?=$count;?>_count]">
							<?php for ($i=0; $i<11; $i++) {
								if ($i == $orb['Orb'][$count."_count"]) {
									echo "<option selected value='$i'>$i</option>";
								} else {
									echo "<option value='$i'>$i</option>";
								}
							}?>
						</select>
					</div>
					<?php }?>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<div id="individual-orbopts" class="content">
						<?php if (false) {?>
						<dl class="sub-nav large-8 large-centered columns">
							<dt>Filter:</dt>
							<?php foreach($optflags as $id => $name) { ?>
								<dd id="<?php echo sprintf("orbopt-flag-%s", $id);?>" data-id="<?php echo $id;?>" class="orbopt-flag active"
									><a href="#" data-route="orbopt_config/<?php echo $id;?>/filter"><?php echo $name;?></a
								></dd>
							<?php } ?>
						</dl>
						<?php } ?>
						<ul class="large-block-grid-6">
						<?php
							foreach($orbopts as $opt) {
								//if ($opt['deprecated']) continue;
								$group_ids = array();
								foreach ($opt['Orbcat'] as $orbcat) { array_push($group_ids, $orbcat['id']); }
								$data = array('orbopt' => $opt['Orbopt']['id'],
								              'groups' => sprintf("[%s]", implode(",",$group_ids)),
								              'route' => sprintf("orbopt_config/%s/toggle", $opt['Orbopt']['id']),
								              'flags' => sprintf("[%s]", implode(",", $opt['Orbopt']['flags'] ))
								);
								$classes = array('orbopt');
								$val = false;
								if ( in_array($opt['Orbopt']['id'], $active_orbopts) ) {
									if (in_array($opt['Orbopt']['id'], $default_opts) ) {
										$val = 2;
										array_push($classes, 'active-plus');
									} else {
										$val = 1;
										array_push($classes, 'active');
									}
								} else {
									$val = 0;
									array_push($classes, 'inactive');
								}
								echo sprintf("<li id='orbopt-%s-label' %s %s>", $opt['Orbopt']['id'], ___dA($data), ___cD($classes) );?>
									<span><?=ucwords($opt['Orbopt']['vendor_title']); ?></span>
									<input type="hidden" name="Orbopt[<?=$opt['Orbopt']['id'];?>]" value="<?=$val;?>" />
								</li>
						<?php } ?>
						</ul>
					</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>