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
	<div class="row">
		<!--- SPECIALS SET-CRITERIA --->
		<div id="add-special-criteria" class="large-4 columns">
			<div id="add-special-criteria-wrapper" class="input select">
				<label for="add-special-criteria-select">From</label>
				<select id="add-special-criteria-select" data-changeroute="specials_criteria/criteria/choose">
					<option>--</option>
					<option value="orbcats">A category...</option>
					<option value="orbs">A custom list...</option>
				</select>
			</div>
			<div id="add-special-criteria-choice" class="hidden fade-out">
				<label>From</label>
				<span class="select-choice" data-route="specials_criteria/criteria/restore"></span>
			</div>
			<div id="add-special-criteria-orbcats" class="breakout pricing hidden fade-out">
				<div clas="input select">
					<label for="add-special-criteria-orbcats-select">Select a Category</label>
					<select id="add-special-criteria-orbcats-select" data-changeroute="specials_add_orbcat_filter/reveal">
					<?php foreach ($orbcats as $id => $oc):?>
						<option value="<?=$id;?>"><?=$oc;?></option>
					<?php endforeach;?>
					</select>
					<div>
						<a href="#" class="modal-button full-width" data-route="specials_add_close_breakout/criteria/orbcats">
							<span>OK</span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<!--- SPECIALS SET-METHOD--->
		<div id="add-special-method" class="large-4 columns">
			<div id="add-special-method-wrapper" class="input select">
				<label for="add-special-method-select">&nbsp;</label>
				<select id="add-special-method-select" data-changeroute="specials_criteria/method/choose">
					<option>--</option>
					<option value="choose">Choose</option>
					<option value="receive">Receive</option>
				</select>
			</div>
			<div id="add-special-method-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/method/restore"></span>
			</div>
		</div>

		<div id="add-special-quantity" class="large-4 columns">
			<div id="add-special-quantity-wrapper" class="input select">
				<label for="add-special-quantity-select">&nbsp;</label>
				<select id="add-special-quantity-select">
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
	</div>
	<div class="row">
		<div class="large-3 columns">
			<label>&nbsp;</label>
			<a href="#" id="specials-add-conditions-button" class="modal-button cancel full-width inactive" data-route="specials_add_conditions/toggle">
				<span>Add Conditions</span>
			</a>
		</div>
		<div id="add-special-conditions-order-content" class="large-3 columns">
			<div class="input select">
				<label for="add-special-conditions-order-content-select">Order Includes</label>
				<select id="add-special-conditions-order-content-select"  class="specials-add-condition" disabled>
					<option value="order_content/false">--</option>
					<option value="order_content/max">Specific item</option>
					<option value="order_content/min">From category</option>
				</select>
			</div>
		</div>
		<div id="add-special-conditions-price" class="large-3 columns">
			<div class="input select">
				<label for="add-special-conditions-price-select">Order Costs</label>
				<select id="add-special-conditions-price-select"  class="specials-add-condition" disabled>
					<option value="price/false">--</option>
					<option value="price/max">Less than</option>
					<option value="price/min">At least</option>
				</select>
			</div>
		</div>
		<div id="add-special-conditions-order-method" class="large-3 columns">
			<div class="input select">
				<label for="add-special-conditions-order-method-select">Orders For</label>
				<select id="add-special-conditions-order-method-select" class="specials-add-condition" disabled>
					<option value="price/false">--</option>
					<option value="price/max">Delivery</option>
					<option value="price/min">Pick-up</option>
				</select>
			</div>
		</div>
		<div id="add-special-conditions-order-content-breakout" class="breakout pricing hidden fade-out">
			<div clas="input select">
				<p>order conditions</p>
			</div>
		</div>
		<div id="add-special-conditions-order-price-breakout" class="breakout pricing hidden fade-out">
			<div clas="input select">
				<p>order conditions</p>
			</div>
		</div>
	</div>


	<div id="add-special-orbs-list" class="breakout hidden fade-out">
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
			<div>
				<a href="#" class="modal-button full-width" data-route="specials_add_close_breakout/orbs">
					<span>OK</span>
				</a>
			</div>
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


