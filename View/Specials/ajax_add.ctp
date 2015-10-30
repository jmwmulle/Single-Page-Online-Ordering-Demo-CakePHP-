<?php
/**
 * J. Mulle, for app, 10/30/15 5:33 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$default_oc_id = array_keys($orbcats)[0];
?>
<div class="row">
	<div class="large-12 columns">
		<h1>Create a new Special</h1>
	</div>
	<?=$this->Form->create('special');?>
	<div class="large-6 columns">
		<div class="row">
			<div class="large-12 columns">
				<?=$this->Form->input('title');?>
			</div>
			<div class="large-12 columns">
				<?=$this->Form->input('vendor_title');?>
			</div>
			<div class="large-12 columns">
				<?=$this->Form->input('price');?>
			</div>
		</div>
	</div>
	<div class="large-6 columns">
		<div class="row">
			<div class="large-12 columns">
				<?=$this->Form->input('description', ['type' => 'textarea']);?>
			</div>
			<div class="large-12 columns">
				<label>&nbsp</label>
				<a href="#" class="modal-button full-width active"><span>Active</span></a>
			</div>
		</div>
	</div>
	<div class="large-12 columns">
		<h3>Add Menu Items</h3>
	</div>
	<div id="add-special-orbcats-list" class="large-3 columns">
		<div clas="input select">
			<label for="special-orbcats-list-select">Menu Category</label>
			<select id="special-orbcats-list-select" data-changeroute="special-add-orb/reveal">
			<?php foreach ($orbcats as $id => $oc):?>
				<option><?=$oc;?></option>
			<?php endforeach;?>
			</select>
		</div>
	</div>
	<div id="add-special-orbs-list" class="large-3 columns">
		<div clas="input select">
			<label for="special-orbs-list-select">Menu Item</label>
			<select id="special-orbs-list-select">
			<?php foreach ($orbs as $orb):
				$class = [$orb['Orb']['orbcat_id'] == $default_oc_id ? "" : "hidden"];
				$data = ['orbcat' => $orb['Orb']['orbcat_id'],
				         'orb' => $orb['Orb']['id']];
				?>
				<option <?=___cD($class)?> <?=___dA($data);?> >
					<?=ucwords($orb['Orb']['title']);?>
				</option>
			<?php endforeach;?>
			</select>
		</div>
	</div>
	<div id="add-special-orbs-quantity" class="large-3 columns">
		<div clas="input select">
			<label for="special-orbs-quantity-select">Menu Item</label>
			<select id="special-orbs-quantity-select">
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
				<option>9</option>
				<option>10</option>
			</select>
		</div>
	</div>
	<div class="large-3 columns">
		<label>&nbsp;</label>
		<a href="#" class="modal-button full-width" data-route="special-add/add-orb">
			<span>Add & Configure</span>
		</a>
	</div>
	<div class="large-12 columns">
		<table id="specials-orbs">
			<tr>
				<td>OrbTitle</td>
				<td><input type="hidden" name="specials[Orbs][id][quantity]"></td>
			</tr>
		</table>
	</div>
	<div class="large-12 columns">
		<a href="#" class="modal-button full-width"><span>Save & Close</span></a>
	</div>
</div>


