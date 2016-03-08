<?php
/**
 * J. Mulle, for xtreme, 3/3/16 10:49 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<div class="large-12 columns">
	<div id="orblist-selector" class="breakout modal fade-out hidden">
		<div class="row">
			<div class="large-12 columns">
				<h3>Custom Item List Manager</h3>
				<div class="input select">
					<div id="orblist-selector-index" class="large-6 columns">
						<label for="orblist-selector-meta-select">Choose Active List To Manage</label>
						<select id="orblist-selector-meta-select" multiple class="multiselect" data-changeroute="specials_orblists/select">
						<?php foreach ($orblists as $id => $ol):?>
							<option id='orblist-selector-meta-<?=$id;?>' value="<?=$id;?>" >
								<?=ucwords($ol);?>
							</option>
						<?php endforeach;?>
						</select>
					</div>
					<div class="large-6 columns">
						<div class="row">
							<div class="large-12 columns">
								<label>Active List</label>
								<div id="orblist-active-list-wrapper">
									<span id="orblist-selector-active">None</span>
								</div>
							</div>
							<div class="large-12 columns">
								<a href="#" class="modal-button bisecting left disabled delete-button" data-route="specials_orblists/delete">
									<span>Delete List</span>
								</a>
								<a href="#" class="modal-button bisecting right disabled update-button" data-route="specials_orblists/update">
									<span>Update List</span>
								</a>
							</div>
						</div>
						<div class="row">
							<div class="large-8 columns">
								<label for="orblist-selector-input">Create A New List</label>
								<input id="orblist-selector-input" />
							</div>
							<div class="large-4 columns">
								<label>&nbsp;</label>
								<a href="#" class="modal-button sml create-button" data-route="specials_orblists/create">
									<span>Create List</span>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<div class="input select">
					<div id="orblist-selector-index" class="large-5 columns">
						<label for="orblist-selector-index-select">Choose From Here</label>
						<select id="orblist-selector-index-select" multiple class="multiselect">
						<?php foreach ($index as $oc):?>
							<optgroup id='orblist-selector-orbcat-<?=$oc['id'];?>' label="<?=$oc['title'];?>" >
								<?php foreach ($oc['Orb'] as $orb):
									$data = ['orbcat' => $oc['id']]; ?>
								<option id='orblist-selector-index-<?=$orb['id'];?>' <?=___dA($data);?> value="<?=$orb['id'];?>" >
									<?=ucwords($orb['title']);?>
								</option>
								<?php endforeach;?>
							</optgroup>
						<?php endforeach;?>
						</select>
					</div>
					<div class="large-2 columns">
						</br></br>
						<a href="#" class="add-button modal-button sml full-width" data-route="specials_breakouts/add/orblist/*">
							<span>Add</span><span class="icon-circle-arrow-r"></span>
						</a>
						</br></br></br>
						<a href="#" class="remove-button modal-button sml full-width" data-route="specials_breakouts/remove/orblist/*">
							<span class="icon-circle-arrow-l"></span><span>Remove</span>
						</a>
					</div>
					<div id="orblist-selector-collection" class="large-5 columns">
						<label for="orblist-selector-collection-select">Add To Here</label>
						<select id="orblist-selector-collection-select" multiple class="multiselect">
							<?php foreach ($collection as $oc):?>
								<optgroup id='orblist-selector-orbcat-<?=$oc['id'];?>' label="<?=$oc['title'];?>" >
									<?php foreach ($oc['Orb'] as $orb):
										$data = ['orbcat' => $oc['id'], 'label' => ucwords($orb['title'])]; ?>
									<option id='orblist-selector-collection-<?=$orb['id'];?>' class="hidden" <?=___dA($data);?> value="<?=$orb['id'];?>">
										<?=ucwords($orb['title']);?>
									</option>
									<?php endforeach;?>
								</optgroup>
							<?php endforeach;?>
						</select>
					</div>
					<div class="large-12 columns">
						<a href="#" class="cancel-button modal-button cancel bisecting left" data-route="specials_breakouts/cancel/orblist">
							<span class="icon-circle-arrow-l"></span><span>Cancel</span>
						</a>
						<a href="#" class="save-button modal-button confirm bisecting right" data-route="specials_breakouts/save/orblist">
							<span>Select</span><span class="icon-circle-arrow-r"></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>