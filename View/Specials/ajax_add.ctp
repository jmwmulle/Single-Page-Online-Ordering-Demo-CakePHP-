<?php
/**
 * J. Mulle, for app, 10/30/15 5:33 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<div class="row">

	<div class="large-12 columns">
		<h1>Create a new Special</h1>
	</div>
	<!--- LEFT-SIDE DEETS --->
	<?=$this->Form->create('Special');?>
	<div class="large-6 columns">
		<div class="row">
			<div class="large-12 columns">
				<?=$this->Form->input('title', ['data-changeroute' => 'specials_fields/title/update']);?>
			</div>
			<div class="large-12 columns">
				<?=$this->Form->input('vendortitle', ['data-changeroute' => 'specials_fields/vendortitle/update']);?>
			</div>
			<div class="large-12 columns">
				<?=$this->Form->input('price', ['data-changeroute' => 'specials_fields/price/update']);?>
			</div>
		</div>
	</div>

	<!--- RIGHT-SIDE DEETS --->
	<div class="large-6 columns">
		<div class="row">
			<div class="large-12 columns">
				<?=$this->Form->input('description', ['type' => 'textarea', 'data-changeroute' => 'specials_fields/description/update']);?>
			</div>
			<div class="large-12 columns">
				<label>Menu Status</label>
				<ul class="activizing inline">
					<li id="SpecialsActive" class="default active bisecting discreet">
						<a href="#" class="modal-button full-width left sml" data-route="specials/active/1">
							<span>Active</span>
						</a>
					</li>
					<li id="SpecialsInactive" class="inactive bisecting discreet">
						<a href="#"  class="modal-button full-width right sml" data-route="specials/active/0">
							<span>Inactive</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="large-12 columns">
		<h3 class="text-center">Configure Rules for Ordering</h3>
	</div>


	<!--- SAVED FEATURES TABLE--->
	<div class="large-12 columns">
		<table id="specials-features-table">
			<thead>
				<tr>
					<th>
						<a href="#" class="create-button" data-route="specials_features/create">
							<span>Features</span>
							<span class="icon-add icon"></span>
						</a>
					</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

	<!--- SAVED CONDITIONS TABLE--->
	<div class="large-12 columns">
		<table id="specials-conditions-table">
			<thead>
				<tr>
					<th>
						<a href="#" class="create-button" data-route="specials_conditions/create">
							<span>Conditions</span>
							<span class="icon-add icon"></span>
						</a>
					</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

	<!--- FEATURES --->
	<div class="large-12 columns">
		<fieldset id="specials-features" class="fade-out hidden">
			<legend>Feature Creator</legend>
			<?php foreach ($features as $section => $data) echo $this->Element("vendor_ui/specials/section", $data); ?>
			<div class="row">
				<div class="large-12 columns section-buttons">
					<a href="#"  class="modal-button bisecting cancel" data-route="specials_features/cancel">
						<span>Cancel</span>
					</a
					><a href="#"  class="modal-button bisecting confirm" data-route="specials_features/save">
						<span>Save Feature</span>
					</a>
				</div>
			</div>
		</fieldset>
	</div>


	<!--- CONDITIONS --->
	<div class="large-12 columns">
		<fieldset id="specials-conditions" class="fade-out hidden">
			<legend>Condition Creator</legend>
			<?php foreach ($conditions as $section => $data) echo $this->Element("vendor_ui/specials/section", $data);?>
			<div class="row">
				<div class="large-12 columns section-buttons">
					<a href="#"  class="modal-button bisecting cancel" data-route="specials_conditions/cancel">
						<span>Cancel</span>
					</a
					><a href="#"  class="modal-button bisecting confirm" data-route="specials_conditions/save">
						<span>Save Condition</span>
					</a>
				</div>
			</div>
		</fieldset>
	</div>

	<div class="row">
		<div class="large-12 columns section-buttons">
			<a href="#"  id='specials-cancel-button' class="modal-button bisecting cancel" data-route="close_modal/primary">
				<span>Cancel</span>
			</a
			><a href="#"  id='specials-save-button' class="modal-button bisecting confirm disabled" data-route="specials/save">
				<span>Save Special</span>
			</a>
		</div>
	</div>

	<!-- ORB SELECTOR --->
	<?= $this->Element("vendor_ui/specials/orb_selector", ['index' => $grouped_orbs, 'collection' => $grouped_orbs]);?>
	<!-- ORBCAT SELECTOR -->
	<?= $this->Element("vendor_ui/specials/orbcat_selector", ['data' => $orbcats]);?>
	<!-- PRICE SELECTOR -->
	<?= $this->Element("vendor_ui/specials/price_selector");?>
	<!-- ORBLIST MANAGER -->
	<?= $this->Element("vendor_ui/specials/orblist_selector",['orblists' => $orblists,
															 'index' => $grouped_orbs,
	                                                         'collection' => $grouped_orbs]);?>

</div>


