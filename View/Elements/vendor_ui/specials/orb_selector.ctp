<!-- array_filter(array_slice(array_values($orb['price_dict']), 1)); -->
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
								$data = ['orbcat' => $oc['id'],
								         'label' => ucwords($orb['title']),
								         'sizes' => [],
								]; ?>
							<option id='orb-selector-collection-<?=$orb['id'];?>' class="hidden" <?=___dA($data);?> value="<?=$orb['id'];?>">
								<?=ucwords($orb['title']);?>
							</option>
							<?php endforeach;?>
						</optgroup>
					<?php endforeach;?>
				</select>
			</div>
			<div id='orb-sizes-wrapper'class="large-12 columns">
				<h3>Check All Eligible Sizes</h3>
				<table id='orb-sizes-wrapper-content'>
					<thead>
						<tr>
							<th>Item</th>
							<th>Size 1</th>
							<th>Size 2</th>
							<th>Size 3</th>
							<th>Size 4</th>
							<th>Size 5</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($collection as $oc):
							foreach ($oc['Orb'] as $orb):
							$data = ['orbcat' => $oc['id'],
							         'label' => ucwords($orb['title']),
							         'sizes' => [],
							         'sizedict' => $orb['price_dict']
							]; ?>
							<tr id="orb-selector-sizes-<?=$orb['id'];?>" class="orb-size-row fade-out hidden">
								<td><?=ucwords($orb['title']);?></td>
								<?php for ($i=0; $i<5; $i++): ?>
								<td>
									<?php if ( array_key_exists($i, $orb['price_dict']) ):?>
									<label for='orb-<?=$orb['id'];?>-sizes-<?=$i;?>'><?=$orb['price_dict'][$i];?></label>
									<input class='orb-size' type="checkbox" id='orb-<?=$orb['id'];?>-sizes-<?=$i;?>' name="orb-<?=$orb['id'];?>-sizes" value="<?=$i+1;?>" checked />
									<?php endif;?>
								</td>
								<?php endfor;?>
							</tr>
						<?php endforeach;?>
					<?php endforeach;?>
					</tbody>
				</table>
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