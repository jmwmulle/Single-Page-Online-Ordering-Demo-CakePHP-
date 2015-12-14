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

		<span class="note">This is still <emphasis>very</emphasis> in development; saving is not yet enabled. Basically this is the UI you'll eventually use.</span><br />
	</div>
	<?=$this->Form->create('Special');?>
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
				<label>Menu Status</label>
				<?=$this->Form->input('menu_status', ['type' => 'hidden', 'value' => true] );?>
				<ul class="activizing inline">
					<li id="menu-active" class="default active bisecting discreet"><a href="#" class="modal-button full-width left sml"><span>Active</span></a></li>
					<li id="menu-inactive" class="inactive bisecting discreet"><a href="#" class="modal-button full-width right sml"><span>Inactive</span></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="large-12 columns">
		<h3>Add Menu Items</h3>
	</div>
	<div id="add-special-orbcats-list" class="large-3 columns">
		<div clas="input select">
			<label for="special-orbcats-list-select">Menu Category</label>
			<select id="special-orbcats-list-select" data-changeroute="specials_add_orbcat_filter/reveal">
			<?php foreach ($orbcats as $id => $oc):?>
				<option value="<?=$id;?>"><?=$oc;?></option>
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
				$data = ['orbcat' => $orb['Orb']['orbcat_id']];
				?>
				<option <?=___cD($class)?> <?=___dA($data);?> value="<?=$orb['Orb']['id'];?>" >
					<?=ucwords($orb['Orb']['title']);?>
				</option>
			<?php endforeach;?>
			</select>
		</div>
	</div>
	<div id="add-special-orbs-quantity" class="large-3 columns">
		<div clas="input select">
			<label for="special-orbs-quantity-select">Quantity</label>
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
		<a href="#" class="modal-button full-width" data-route="specials_add/add_orb">
			<span>Add</span>
		</a>
	</div>
	<div class="large-12 columns">
		<table id="specials-orbs" data-orb-count="0">
			<thead>
				<tr>
					<th>Item</th>
					<th>Menu Category</th>
					<th>Quantity</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<div class="large-12 columns">
		<a href="#" class="modal-button full-width" data-route="specials_add/save"><span>Save & Close</span></a>
	</div>
</div>


