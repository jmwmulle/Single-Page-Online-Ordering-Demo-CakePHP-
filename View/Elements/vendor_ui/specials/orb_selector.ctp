<div class="large-12 columns">
	<div id="orb-selector" class="breakout modal fade-out hidden">
		<h3>Item Selector</h3>
		<div class="input select">
			<div id="orb-selector-index" class="large-5 columns">
				<label for="orb-selector-index-select">Choose From Here</label>
				<select id="orb-selector-index-select" multiple class="multiselect">
				<?php foreach ($index as $oc):?>
					<optgroup id='orb-selector-orbcat-<?=$oc['id'];?>' label="<?=$oc['title'];?>" >
						<?php foreach ($oc['Orb'] as $orb):
							$data = ['orbcat' => $oc['id']]; ?>
						<option id='orb-selector-index-<?=$orb['id'];?>' <?=___dA($data);?> value="<?=$orb['id'];?>" >
							<?=ucwords($orb['title']);?>
						</option>
						<?php endforeach;?>
					</optgroup>
				<?php endforeach;?>
				</select>
			</div>
			<div class="large-2 columns">
				</br></br>
				<a href="#" class="add-button modal-button sml full-width" data-route="specials_breakouts/add/orb">
					<span>Add</span><span class="icon-circle-arrow-r"></span>
				</a>
				</br></br></br>
				<a href="#" class="remove-button modal-button sml full-width" data-route="specials_breakouts/remove/orb">
					<span class="icon-circle-arrow-l"></span><span>Remove</span>
				</a>
			</div>
			<div id="orb-selector-collection" class="large-5 columns">
				<label for="orb-selector-collection-select">Add To Here</label>
				<select id="orb-selector-collection-select" multiple class="multiselect">
					<?php foreach ($collection as $oc):?>
						<optgroup id='orb-selector-orbcat-<?=$oc['id'];?>' label="<?=$oc['title'];?>" >
							<?php foreach ($oc['Orb'] as $orb):
								$data = ['orbcat' => $oc['id'], 'label' => ucwords($orb['title'])]; ?>
							<option id='orb-selector-collection-<?=$orb['id'];?>' class="hidden" <?=___dA($data);?> value="<?=$orb['id'];?>">
								<?=ucwords($orb['title']);?>
							</option>
							<?php endforeach;?>
						</optgroup>
					<?php endforeach;?>
				</select>
			</div>
			<div class="large-12 columns">
				<a href="#" class="cancel-button modal-button cancel bisecting left" data-route="specials_breakouts/cancel/orb/multiselect">
					<span class="icon-circle-arrow-l"></span><span>Cancel</span>
				</a>
				<a href="#" class="save-button modal-button confirm bisecting right" data-route="specials_breakouts/save/orb/multiselect">
					<span>Save</span><span class="icon-circle-arrow-r"></span>
				</a>
			</div>
		</div>
	</div>
</div>