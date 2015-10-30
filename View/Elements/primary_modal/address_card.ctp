<?php
/**
 * J. Mulle, for app, 7/24/15 6:36 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	$area_code = substr($address['phone'], 0, 3);
	$prefix = substr($address['phone'], 3, 3);
	$line_no = substr($address['phone'], 6, 4);
	$pcode_block_1 = strtoupper(substr($address['postal_code'], 0, 3));
	$pcode_block_2 = strtoupper(substr($address['postal_code'], 3, 3));
?>
<div class="large-4 columns">
	<div class="row">
		<div class="large-12 columns">
			<div class="address-row" data-route="set_user_address/<?=$address['id'];?>/set">
				<div class="row">
					<div class="large-12 columns">
						<ul id="user-address-<?=$address['id'];?>" class="address">
							<li class="building-type"><em><?=$address['building_type'];?></em></li>
							<li class="address-1"><?=$address['address'];?></li>
							<li class="address-2"><?=$address['address_2'];?></li>
							<li class="city-province"><?=sprintf("%s, %s", $address['city'], $address['province']);?></li>
							<li class="postal-code"><?=sprintf("%s %s", $pcode_block_1, $pcode_block_2);?></li>
							<li class="phone-number"><?=sprintf("%s.%s.%s", $area_code, $prefix, $line_no);?></li>
						</ul>
						<p class="note"><strong>Delivery Instructions</strong><br/><?=$address['note'];?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>