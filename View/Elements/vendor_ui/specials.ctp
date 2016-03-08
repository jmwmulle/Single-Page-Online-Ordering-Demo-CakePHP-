<?php
/**
 * J. Mulle, for app, 10/21/15 6:05 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$ready_to_list_specials = true;
//db($specials);
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
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<table role="grid" id="specials-table">
				<thead>
					<tr>
						<th><a href="#">Vendor Title</th>
						<th><a href="#">Menu Title</th>
						<th>Orbs</th>
						<th>Price</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
				<?php if ($ready_to_list_specials) {?>
				<?php foreach ( $specials as $special ):
					$sid = $special['Special']['id'];
					$sp = $special['Special'];
					$spo = $special['Orb'];
				?>
				<tr data-special="<?=$sid;?>">
					<td id='specials-<?=$sid;?>-vendor-title' data-route="specials_edit/<?=$sid;?>/edit/vendor-title">
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
					<td id='specials-<?=$sid;?>-title' data-route="specials_edit/<?=$sid;?>/edit/title">
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
					<td data-route="specials_orbs_config/<?=$sid;?>/launch">
					<?php   foreach($spo as $orb):
						        continue;
							endforeach;?>
					</td>
					<td id="specials-<?=$sid;?>-pricing" data-route="specials_pricelist/launch/false/<?=$sid;?>">
						<div class="specials-attr display"><?=money_format( "%#3.2n",$sp['price']);?>
					<td>
						<a href="#" class="modal-button lrg delete full-width text-center" data-route="specials_edit/<?=$sid;?>/delete/confirm">
							<span class="icon-cancel textless"></span>
						</a>

						<div id="delete-specials-<?=$sid; ?>" class="breakout hidden">
							<h4>Are you sure you want to delete "<?=$sp[ 'title' ]; ?>"?</h4>
							<a href="#" class="modal-button bisecting confirm right"
							   data-route="specials_config/<?=$sid; ?>/delete/delete">
								<span class="text">Confirm</span><span class="icon-circle-arrow-r"></span>
							</a>
							<a href="#" class="modal-button bisecting cancel left"
							   data-route="specials_config/<?=$sid; ?>/delete/cancel">
								<span class="text">Cancel</span>
							</a>
						</div>
						<div id="delete-specials-<?=$sid; ?>" class="breakout hidden">
					</td>

				</tr>
			<?php endforeach; } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>