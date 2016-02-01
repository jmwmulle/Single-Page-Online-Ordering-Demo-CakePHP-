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
		<h3 class="text-center">Configure Rules for Ordering</h3>
	</div>
	<div class="row">

		<!-- ORB SELECTOR --->
		<div class="large-9 columns">
			<div id="orb-selector" class="breakout modal fade-out hidden">
				<h3>Item Selector</h3>
				<div class="input select">
					<div id="orb-selector-from" class="large-5 columns">
						<label for="special-orbs-list-select">Choose From Here</label>
						<select id="orb-selector-from-select" multiple class="multiselect">
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
					<div class="large-2 columns">
						</br></br>
						<a href="#" class="modal-button sml full-width">
							<span class="icon-circle-arrow-l"></span><span>Remove</span>
						</a> </br></br></br>
						<a href="#" class="modal-button sml full-width">
							<span>Add</span><span class="icon-circle-arrow-r"></span>
						</a>
					</div>
					<div id="orb-selector-to" class="large-5 columns">
						<label for="special-orbs-list-select">Add To Here</label>
						<select id="orb-selector-to-select" multiple class="multiselect">
						</select>
					</div>
					<div class="large-12 columns">
						<a href="#" class="modal-button cancel bisecting left" data-route="specials_add_close_breakout/0/false/orb_selector">
							<span class="icon-circle-arrow-l"></span><span>Cancel</span>
						</a>
						<a href="#" class="modal-button confirm bisecting right" data-route="specials_add_close_breakout/orbs">
							<span>OK</span><span class="icon-circle-arrow-r"></span>
						</a>
					</div>
				</div>
			</div>
		</div>

		<!-- ORBCAT SELECTOR -->
		<div class="large-9 columns">
			<div id="orbcat-selector" class="breakout modal fade-out hidden">
				<h3>Category Selector</h3>
				<div class="input select">
					<div id="orb-selector-from" class="large-5 columns">
						<label for="special-orbs-list-select">Choose From Here</label>
						<select id="orb-selector-from-select" multiple class="multiselect">
							<option>--</option>
							<?php foreach ($orbcats as $id => $oc):?>
								<option value="<?=$id;?>"><?=$oc;?></option>
							<?php endforeach;?>
						</select>
					</div>
					<div class="large-2 columns">
						</br></br>
						<a href="#" class="modal-button sml full-width">
							<span class="icon-circle-arrow-l"></span><span>Remove</span>
						</a> </br></br></br>
						<a href="#" class="modal-button sml full-width">
							<span>Add</span><span class="icon-circle-arrow-r"></span>
						</a>
					</div>
					<div id="orb-selector-to" class="large-5 columns">
						<label for="special-orbs-list-select">Add To Here</label>
						<select id="orb-selector-to-select" multiple class="multiselect">
						</select>
					</div>
					<div class="large-12 columns">
						<a href="#" class="modal-button cancel bisecting left" data-route="specials_add_close_breakout/orbs">
							<span class="icon-circle-arrow-l"></span><span>Cancel</span>
						</a>
						<a href="#" class="modal-button confirm bisecting right" data-route="specials_add_close_breakout/orbs">
							<span>OK</span><span class="icon-circle-arrow-r"></span>
						</a>
					</div>
				</div>
			</div>
		</div>


		<!--- SPECIALS SET-METHOD--->
		<div id="add-special-method-config-label" class="large-3 columns">
			<div class="input select">
				<label>&nbsp</label>
				<span class="config-label">Customers will:</span>
			</div>
		</div>
		<div id="add-special-method" class="large-9 columns">
			<div id="add-special-method-wrapper" class="input select">
				<label for="add-special-method-select">&nbsp;</label>
				<select id="add-special-method-select" data-changeroute="specials_criteria/0/method/choose">
					<option>--</option>
					<option value="choose" data-breakout="0">Choose</option>
					<option value="receive" data-breakout="orb-selector">Receive</option>
				</select>
			</div>
			<div id="add-special-method-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/0/method/restore"></span>
			</div>
		</div>

		<!--- SPECIALS SET-CHOICECOUNT--->
		<div id="add-special-choicecount-config-label" class="large-3 columns">
			<div class="input select">
				<label>&nbsp</label>
				<span class="config-label disabled">From this many lists:</span>
			</div>
		</div>
		<div id="add-special-choicecount" class="large-9 columns">
			<div id="add-special-choicecount-wrapper" class="input select">
				<label for="add-special-choicecount-select">&nbsp;</label>
				<select id="add-special-choicecount-select" disabled data-changeroute="specials_criteria/0/choicecount/choose">
					<option>--</option>
					<?php for ($i=1; $i<11; $i++):?>
					<?="<option value='$i' data-breakout='0'>$i</option>";?>
					<?php endfor; ?>
				</select>
			</div>
			<div id="add-special-choicecount-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/choicecount/restore"></span>
			</div>
		</div>

		<!--- SPECIALS SET-QUANTITY--->
		<div id="add-special-quantity-config-label" class="large-3 columns">
			<label>&nbsp</label>
			<span class="config-label">This many: </span>
		</div>
		<div id="add-special-quantity" class="large-9 columns">
			<div id="add-special-quantity-wrapper" class="input select">
				<label for="add-special-quantity-select">&nbsp;</label>
				<select id="add-special-quantity-select" data-changeroute="specials_criteria/0/quantity/choose">
					<option>--</option>
					<?php for ($i=1; $i<11; $i++):?>
					<?="<option value='$i' data-breakout='0'>$i</option>";?>
					<?php endfor; ?>
				</select>
			</div>
			<div id="add-special-quantity-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/0/quantity/restore"></span>
			</div>
		</div>


		<!--- SPECIALS SET-CRITERIA --->
		<div id="add-special-criteria-config-label" class="large-3 columns">
			<label>&nbsp</label>
			<span class="config-label">Items from: </span>
		</div>
		<div id="add-special-criteria" class="large-9 columns">
			<div id="add-special-criteria-wrapper" class="input select">
				<label for="add-special-criteria-select">&nbsp;</label>
				<select id="add-special-criteria-select" data-changeroute="specials_criteria/0/criteria/choose">
					<option>--</option>
					<option value="orbcats" data-breakout="add-special-criteria-orbcats">A category...</option>
					<option value="orbs" data-breakout="0">A custom list...</option>
				</select>
			</div>
			<div id="add-special-criteria-choice" class="hidden fade-out">
				<span class="select-choice" data-route="specials_criteria/0/criteria/restore"></span>
				<input class="choice-text" type="hidden" value="" />
			</div>
			<div id="add-special-criteria-orbcats" class="breakout pricing hidden fade-out">
				<div clas="input select">
					<label for="add-special-criteria-orbcats-select">Select a Category</label>
					<select id="add-special-criteria-orbcats-select" data-changeroute="specials_add_orbcat_filter/reveal">
						<option>--</option>
					<?php foreach ($orbcats as $id => $oc):?>
						<option value="<?=$id;?>"><?=$oc;?></option>
					<?php endforeach;?>
					</select>
					<div>
						<a href="#" class="modal-button full-width" data-route="specials_add_close_breakout/0/criteria/orbcats">
							<span>OK</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!--- SPECIALS CONDITIONS --->
	<div class="row">
		<!--- SPECIALS CONDITIONS BUTTON--->
		<div class="large-12 columns">
			<label>&nbsp;</label>
			<a href="#" id="specials-add-conditions-button" class="modal-button cancel full-width inactive" data-route="specials_criteria/1/false/toggle">
				<span>Add Conditions</span>
			</a>
		</div>

		<!--- SPECIALS CONDITIONS CONTENT--->
		<div id="add-special-conditions-content-config-label" class="large-3 columns">
			<label>&nbsp</label>
			<span class="config-label disabled">Order must include:</span>
		</div>
		<div id="add-special-conditions-content" class="large-9 columns">
			<div id="add-special-conditions-content-wrapper" class="input select">
				<label for="add-special-conditions-content-select">&nbsp;</label>
				<select id="add-special-conditions-content-select"  class="specials-add-condition" disabled data-changeroute="specials_criteria/1/content/choose">
					<option>--</option>
					<option value="order_content/orb" data-breakout="orb-selector">Specific items</option>
					<option value="order_content/orbcat" data-breakout="0">Items from category...</option>
					<option value="order_content/list" data-breakout="0">Items from list...</option>
				</select>
			</div>
			<div id="add-special-conditions-content-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/1/content/restore"></span>
			</div>
		</div>

		<!--- SPECIALS CONDITIONS PRICE--->
		<div id="add-special-conditions-price-config-label" class="large-3 columns">
			<label>&nbsp</label>
			<span class="config-label disabled">Order must cost:</span>
		</div>
		<div id="add-special-conditions-price" class="large-9 columns">
			<div id="add-special-conditions-price-wrapper" class="input select">
				<label for="add-special-conditions-price-select">&nbsp;</label>
				<select id="add-special-conditions-price-select"  class="specials-add-condition" disabled data-changeroute="specials_criteria/1/price/choose">
					<option>--</option>
					<option value="price/max" data-breakout="0">Less than</option>
					<option value="price/min" data-breakout="0">At least</option>
				</select>
			</div>
			<div id="add-special-conditions-price-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/1/price/restore"></span>
			</div>
		</div>

		<!--- SPECIALS CONDITIONS ORDERMETHOD--->
		<div id="add-special-conditions-ordermethod-config-label" class="large-3 columns">
			<label>&nbsp</label>
			<span class="config-label disabled">Order must be for:</span>
		</div>
		<div id="add-special-conditions-ordermethod" class="large-9 columns">
			<div id="add-special-conditions-ordermethod-wrapper" class="input select">
				<label for="add-special-conditions-ordermethod-select">&nbsp;</label>
				<select id="add-special-conditions-ordermethod-select" class="specials-add-condition" disabled data-changeroute="specials_criteria/1/ordermethod/choose">
					<option>--</option>
					<option value="price/max" data-breakout="0">Delivery</option>
					<option value="price/min" data-breakout="0">Pick-up</option>
				</select>
			</div>
			<div id="add-special-conditions-ordermethod-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_criteria/1/ordermethod/restore"></span>
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


