<?php
/**
 * J. Mulle, for app, 10/21/15 6:05 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<div id="specials-tab">
	<!-- Menu Options tab proper -->
	<div class="row">
		<div class="large-12 columns">
			<br />
			<a href="#" class="modal-button full-width med" data-route="specials/launch">
				<span class="icon-add"></span>
				<span>Add A New Special</span>
			</a>
			<br />
			<br />
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<table role="grid" id="specials-table">
				<thead>
					<tr>
						<th><a href="#">Vendor Title</a></th>
						<th><a href="#">Menu Title</a></th>
						<th><a href="#">Decription</a></th>
						<th>Condtions</th>
						<th>Features</th>
						<th>Price</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $specials as $special ):
					$sid = $special['Special']['id'];
					$sp = $special['Special'];
				?>
				<tr data-special="<?=$sid;?>">
					<!-- SPECIALS VENDOR TITLE -->
					<td id='specials-<?=$sid;?>-vendor-title' datar-route="specials_edit/<?=$sid;?>/edit/vendor-title">
						<div class="specials-attr display">
							<?=$sp[ 'vendor_title' ] ?  $sp[ 'vendor_title' ] : "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"?>
						</div>
						<div class="specials-attr edit hidden">
							<form>
								<input type="text" name="Special[vendor_title]" value="<?=$sp['vendor_title'];?>" >
							</form>
							<div class="button-box">
								<a href="#" class="modal-button sml bisecting cancel right" data-route="specials_edit/<?=$sid;?>/cancel/vendor-title">
									<span class="icon-cancel textless"></span>
								</a>
								<a href="#" class="modal-button sml bisecting confirm left" data-route="specials_edit/<?=$sid;?>/save/vendor-title">
									<span class="text">Save</span>
								</a>
							</div>
						</div>
					</td>

					<!-- SPECIALS TITLE -->
					<td id='specials-<?=$sid;?>-title' datar-route="specials_edit/<?=$sid;?>/edit/title">
						<div class="specials-attr display"><?=$sp[ 'title' ] ?></div>
						<div class="specials-attr edit hidden">
							<form>
								<input type="text" name="Special[title]" value="<?=$sp['title'];?>" >
							</form>
							<div class="button-box">
								<a href="#" class="modal-button sml bisecting cancel right" data-route="specials_edit/<?=$sid;?>/cancel/title">
									<span class="icon-cancel textless"></span>
								</a>
								<a href="#" class="modal-button sml bisecting confirm left" data-route="specials_edit/<?=$sid;?>/save/title">
									<span class="text">Save</span>
								</a>
							</div>
						</div>
					</td>

					<!-- SPECIALS DESCRIPTION -->
					<td id='specials-<?=$sid;?>-description' datar-route="specials_edit/<?=$sid;?>/edit/title">
						<div class="specials-attr display"><?=$sp[ 'description' ] ?></div>
						<div class="specials-attr edit hidden">
							<form>
								<input type="text" name="Special[description]" value="<?=$sp['description'];?>" >
							</form>
							<div class="button-box">
								<a href="#" class="modal-button sml bisecting cancel right" data-route="specials_edit/<?=$sid;?>/cancel/decription">
									<span class="icon-cancel textless"></span>
								</a>
								<a href="#" class="modal-button sml bisecting confirm left" data-route="specials_edit/<?=$sid;?>/save/decription">
									<span class="text">Save</span>
								</a>
							</div>
						</div>
					</td>

					<!-- SPECIALS CONDITIONS-->
					<td class="top-aligned">
						<?=$this->Element("vendor_ui/specials/special_conditions_desc", ['conditions' => $special['SpecialCondition']]);?>
					</td>
					<!-- SPECIALS FEATURES -->
					<td class="top-aligned">
						<?=$this->Element("vendor_ui/specials/special_features_desc", ['features' => $special['SpecialFeature']]);?>
					</td>
					<!-- SPECIALS PRICE -->
					<td id="specials-<?=$sid;?>-pricing" datar-route="specials_pricelist/launch/false/<?=$sid;?>">
						<div class="specials-attr display"><?=money_format( "%#3.2n",$sp['price']);?>
					</td>

					<!-- SPECIALS BUTTONS -->
					<td>
						<a href="#" class="modal-button lrg delete full-width text-center" data-route="specials_delete/confirm_delete_special/<?=$sid;?>">
							<span class="icon-cancel textless"></span>
						</a>

						<div id="specials-delete-<?=$sid; ?>" class="breakout fade-out hidden">
							<h4>Are you sure you want to delete "<?=$sp[ 'title' ]; ?>"?</h4>
							<a href="#" class="modal-button bisecting cancel"
							   data-route="specials_delete/cancel_delete_special/<?=$sid;?>">
								<span class="icon-cancel"></span><span class="text">Cancel</span>
							</a
							><a href="#" class="modal-button bisecting confirm"
							   data-route="specials_delete/delete_special/<?=$sid;?>">
								<span class="text">Confirm</span><span class="icon-circle-arrow-r"></span>
							</a>
						</div>
						<div id="delete-specials-<?=$sid; ?>" class="breakout hidden">
					</td>

				</tr>
			<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>