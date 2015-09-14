<?php
/**
 * J. Mulle, for app, 7/15/15 10:14 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 *
 *  $uid
 *  $oi = [
 *           orb => [Orb, Orbcat, Pricelist, Pricedict],
 *           orbopts => {n}[Orbopt, PriceList, Optflag =>
 *                                           {n}[ id, title, price_factor, OrboptsOptflag =>
 *                                                                                  {n}[ id, orbopt_id, optflag_id] ],
 *          pricing => [rank, label, quantity, unit_base_price, opt_price, configured_unit_price, net]
 *        ]
 */
$size = $oi['orb']['Pricedict']["l".$oi['pricing']['rank']];
?>
<div class="row">
	<div class="large-8 columns"><?=sprintf("%s %s", $size, $oi['orb']['Orb']['title'])?></div>
	<div class="large-1 columns"><?=$oi['pricing']['quantity']?></div>
	<div class="large-3 columns"><?=money_format( "%#3.2n", $oi['pricing']['configured_unit_price'])?></div>
</div>
