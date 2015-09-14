<?php
/**
 * J. Mulle, for app, 8/2/15 7:57 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<div class="row">
	<div class="large-12 columns">
		<div class="panel">
			<h1>Add Option Pricing</h1>
			<div id='orbopt-pricelist-add' class="orbopts form">
				<form id="orbopt-pricelist-add-edit-form">
					<input type="hidden" name="Pricelist[id]" value="<?=$pricelist['Pricelist']['id'];?>">
					<label>Label  (ie. a handy way to re-use this price scheme later)</label>
					<input type="text" name="Pricelist[label]" value="<?=$pricelist['Pricelist']['label'];?>">
				<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
					<div class="price-rank-edit">
						<div class="price-value">
							<label>Price <?=$i;?></label>
							<span>$&nbsp;</span>
							<input class='pricelist' type='text' name='Pricelist[p<?=$i;?>]' value='<?=$pricelist['Pricelist']["p$i"];?>'>
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
		<a href="#" class="modal-button bisecting right confirm" data-route="orbopt_pricelist/edit/save/<?=$pricelist['Pricelist']['id'];?>">
			<span class="text">Save</span>
			<span class="icon-circle-arrow-r"></span>
		</a>
		<a href="#" class="modal-button bisecting cancel left" data-route="orbopt_pricelist/cancel-add">
			<span class="icon-circle-arrow-l"></span>
			<span class="text">Cancel</span>
		</a>
	</div>
</div>