<?php
/**
 * J. Mulle, for app, 8/2/15 4:06 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
//pr($pricelists);
//db($orbopt);
$oid = $orbopt['Orbopt']['id'];
$o_pl_id = $orbopt['Pricelist']['id'];
$buttons_enabled = false;
?>
<div class="row">
	<div class="large-12 columns">
		<h1>Configure Pricing Structure for "<?=ucfirst($orbopt['Orbopt']['title']);?>"</h1>
<div class="row">
	<div class="large-6 columns">
		<form id="orbopt-pricelist-select-form" data-opt="<?=$orbopt['Orbopt']['id'];?>">
			<select id="orbopt-pricelist-select" name="Orbopt[pricelist_id]" data-changeroute="orbopt_pricelist/set">
				<option> &nbsp; </option>
				<?php foreach ($pricelists as $index => $pl) {
					$pl = $pl['Pricelist'];
					$prices = array_slice($pl, 1, -1);
					foreach ($prices as $rank => $p) $prices[$rank] = $p ? money_format( "%#3.2n", $p ) : null;
					$p_string = implode(", ", array_filter($prices));
					$attrs = $pl['id'] == $o_pl_id ? "selected='selected' data-default" : null;
					echo sprintf("<option %s value='%s'>%s: %s</option>", $attrs, $pl['id'], $pl['label'], $p_string);
				}?>
			</select>
		</form>
	</div>
	<div class="large-6 columns">
		<div id="orbopt-pricelist-buttons">
			<a href="#" class="modal-button med <?=$o_pl_id ? 'enabled' : 'disabled';?>" data-route="orbopt_pricelist/save/opt/<?=$orbopt['Orbopt']['id'];?>">
				<span class="text">Select</span>
			</a>
			<a href="#" class="modal-button med <?=$o_pl_id ? 'enabled' : 'disabled';?>" data-route="orbopt_pricelist/delete/warn">
				<span class="text">Delete</span>
			</a>
			<a href="#" class="modal-button med <?=$o_pl_id ? 'enabled' : 'disabled';?>" data-route="orbopt_pricelist/edit">
				<span class="text">Edit...</span>
			</a>
			<a href="#" class="modal-button med" data-route="orbopt_pricelist/add">
				<span>Add...</span>
			</a>
		</div>
	</div>
	<div id="delete-orbopt-pricelist-confirmation" class="breakout hidden fade-out">
		<div class="row">
			<div class="large-12 columns">
				<h2>Warning: Are you sure you want to permanently delete this pricing option?</h2>
				<p> Once confirmed, all options that use this price will not be
					available on the menu until they have been assigned a new pricing structure.</p>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<a href="#" class="modal-button cancel bisecting left" data-route="orbopt_pricelist/delete/cancel">
					<span class="icon-circle-arrow-l"></span>
					<span class="text">Cancel</span>
				</a>
				<a href="#" class="modal-button confirm bisecting right" data-route="orbopt_pricelist/delete/confirm">
					<span class="text">Confirm</span>
					<span class="icon-circle-arrow-r"></span>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div id="orbopt-pricelist-add-container" class="large-12 columns hidden fade-out">
		<div class="row">
			<div class="large-12 columns">
				<div class="panel">
					<h1>Add Option Pricing</h1>
					<div id='orbopt-pricelist-add' class="orbopts form">
						<form id="orbopt-pricelist-add-edit-form">
							<label>Label  (ie. a handy way to re-use this price scheme later)</label>
							<input type="hidden" name="Pricelist[id]" />
							<input type="text" name="Pricelist[label]">
						<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
							<div class="price-rank-edit">
								<div class="price-value">
									<label>Price <?=$i;?></label>
									<span>$&nbsp;</span>
									<input class='pricelist' type='text' name='Pricelist[p<?=$i;?>]' value=''>
								</div>
							</div>
						<?php } ?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<a href="#" class="modal-button bisecting right confirm" data-route="orbopt_pricelist/save/pricelist">
					<span class="text">Save</span>
					<span class="icon-circle-arrow-r"></span>
				</a>
				<a href="#" class="modal-button bisecting cancel left" data-route="orbopt_pricelist/cancel-add">
					<span class="icon-circle-arrow-l"></span>
					<span class="text">Cancel</span>
				</a>
			</div>
		</div>
	</div>
</div>
