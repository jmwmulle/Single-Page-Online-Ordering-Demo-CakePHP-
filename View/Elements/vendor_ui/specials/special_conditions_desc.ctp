<?php
/**
 * J. Mulle, for xtreme, 3/17/16 7:15 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<div class="special-feature-condition">
	<div class="row">
		<div class="large-12 columns">
			<? if ( count($conditions) > 1 ) {?>
			<h4>Conditions</h4>
			<ul class="special-feature-condition">
				<?php foreach($conditions as $i => $c) {
					if ($i == "Orb") continue;?>
					<li>
					<?php
						$c_str;
						$print_orbs = false;
						if ( $c['orbcat_id'] ) {
							$c_str = "Order must include an item from ".$c['Orbcat']['title']." (category).";
						} else if ( $c['orblist_id'] ) {
							$c_str = "Order must include an item from ".$c['Orblist']['name']." (custom list).";
						} else if ( $c['price_max'] or $c['price_min'] ) {
							$price = $c['price_max'] ? money_format( "%#3.2n",$c['price_max']) : money_format( "%#3.2n",$c['price_min']);
							$c_str = "Order must cost ".($c['price_max'] ? "at most " : "at least ")."$price.";
						} else if ( $c['delivery'] or $c['pickup'] ) {
							$order_for = $c['delivery'] ? "delivery" : "pick-up";
							$c_str = "Order must be for $order_for";
						} else {
							$print_orbs = true;
							$include == count($conditions['Orb']) > 1 ? "any of the following items:" : ":";
							$c_str = "Order must include $include";?>
						<?php } ;?>
						<?=$c_str;?>
						<?php if ($print_orbs) { ?>
							<ul>
							<?php foreach($conditions['Orb'] as $o) {?>
								<li>
									<?=sprintf(" %s (%s)", $o['title'], implode(array_slice($o,6), ", "));?>
								</li>
							<?php };?>
							</ul>
						<?php }?>
					</li>
				<?php };?>
			</ul>
			<?php };?>

		</div>
	</div>
</div>
