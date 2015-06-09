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
					<span><em>Automatically select all toppings that are assigned to the menu category:</em></span>
					<select id="orbgroup" name="orbgroup"  data-changeroute="orbopt_config/-1/toggle_group">
					<?php
						foreach($orbopts_groups as $id => $name) { echo sprintf("<option value='%s'>%s</option>", $id, $name); }?>
						<option value="-1" selected="selected">---</option>
					</select>
				</div>
				<div id="individual-orbopts" class="content">
					<dl class="sub-nav large-8 large-centered columns">
						<dt>Filter:</dt>
						<?php foreach($optflags as $id => $name) { ?>
							<dd id="<?php echo sprintf("orbopt-flag-%s", $id);?>" data-id="<?php echo $id;?>" class="orbopt-flag active"
								><a href="#" data-route="orbopt_config/<?php echo $id;?>/filter"><?php echo $name;?></a
							></dd>
						<?php } ?>
					</dl>
					<ul class="large-block-grid-6">
					<?php
						foreach($orbopts as $opt) {
							$group_ids = array();
							foreach ($opt['Orbcat'] as $orbcat) { array_push($group_ids, $orbcat['id']); }
							$data = array('orbopt' => $opt['Orbopt']['id'],
							              'groups' => sprintf("[%s]", implode(",",$group_ids)),
							              'route' => sprintf("orbopt_config/toggle/%s", $opt['Orbopt']['id']),
							              'flags' => sprintf("[%s]", implode(",", $opt['Orbopt']['flags'] ))
							);
							$classes = array('orbopt');
							$span_classes = array('label', 'secondary');
							$active = in_array($opt['Orbopt']['id'], $active_orbopts);
							if ($active) {
								array_push($classes, "active");
								$span_classes[1] = "success";
							}
							echo sprintf("<li id='orbopt-%s-label' %s %s>", $opt['Orbopt']['id'], ___dA($data), ___cD($classes) );?>
								<span <?php echo ___cD($span_classes);?>><?php echo $opt['Orbopt']['title']; ?></span>
								<input type="hidden" name="Orbopt[<?php echo $opt['Orbopt']['id'];?>]" value="<?php echo $active ? 1 : 0;?>" />
							</li>
					<?php } ?>
					</ul>
				</div>
			</form>
		</div>
	</div>
</div>