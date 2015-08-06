<?php
	/**
	 * J. Mulle, for app, 7/15/15 7:55 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 *  $uid,
	 *  $oi = [
	 *           orb => [Orb, Orbcat, Pricelist, Pricedict],
	 *           orbopts => {n}[Orbopt, PriceList, Optflag =>
	 *                                           {n}[ id, title, price_factor, OrboptsOptflag =>
	 *                                                                                  {n}[ id, orbopt_id, optflag_id] ],
	 *          pricing => [rank, label, quantity, unit_base_price, opt_price, configured_unit_price, net]
	 *        ]
	 */
	$orb = $oi[ 'orb' ][ 'Orb' ];
	$opts = $oi[ 'orbopts' ];
	$pricing = $oi[ 'pricing' ];
?>
<div class="row view-cart-row" data-uid="<?=$uid;?>">
	<div class="large-12 columns">
		<div class="row-wrapper">

			<!-- Item Review Header -->
			<div id="order-item-<?=$uid;?>" class="row primary-row">
				<div class="large-7 columns"><span class="cart-row-item-title"><?= $orb[ 'title' ]; ?></span></div>
				<div class="large-2 columns text-center"><span><?= $pricing[ 'quantity' ]; ?></span></div>
				<div class="large-2 columns "><span><?=money_format( "%#3.2n", $pricing[ 'net' ] ); ?></span></div>
				<div class="large-1 columns text-center data-route="cart_edit/delete/<?=$uid;?>"><span class="icon-cancel"></div>
			</div>

			<!-- Item Opts-->
			<div class="row<?php if ( empty( $opts ) ) echo " true-hidden";?>">
				<div class="large-12 columns secondary-row orbopts">
					<?php foreach ( $opts as $i => $opt ) { ?>
						<a href="#" data-route="edit_orbopt_in_cart<?= DS . $uid . DS . $opt[ 'Orbopt' ][ 'id' ]; ?>">
								<span class="opt-label"><?= $opt[ 'Orbopt' ][ 'title' ]; ?>
									<?php switch ( $opt[ 'Orbopt' ][ 'coverage' ] ) {
											case "R":
												echo '<span class="icon-right-side"></span>';
												break;
											case "L":
												echo '<span class="icon-left-side"></span>';
												break;
											case "D":
												echo '<span class="icon-double"></span>';
												break;
										}?>
								</span></a>
					<?php } ?>
				</div>
			</div>

			<!-- Orb Note -->
			<div class="row<?php if ( !$orb[ 'note' ] ) echo " true-hidden"; ?>">
				<div class="large-12 columns secondary-row orb-note">
					<span class="preparation-instructions"><?php echo $orb[ 'note' ]; ?></span>
				</div>
			</div>

			<div class="true-hidden">
				<?= $this->Form->create( null, array( 'url' => 'update-cart' ) ); ?>
				<?= $this->Form->input( 'uid', array( 'hiddenField' => true, 'value' => $uid ) ); ?>
				<?= $this->Form->input( 'orbopts', array( 'hiddenField' => true, 'value' => json_encode( $opts ) ) ); ?>
				<?=
					$this->Form->input( 'orb_note', array( 'div'     => false,
					                                       'class'   => 'text form-control input-small',
					                                       'label'   => false,
					                                       'data-id' => $uid,
					                                       'value'   => $orb[ 'note' ] )
					);?>
				<?=
					$this->Form->input( 'price_rank', array( 'div'     => false,
					                                         'class'   => 'numeric form-control input-small',
					                                         'label'   => false,
					                                         'data-id' => $uid,
					                                         'value'   => $oi[ 'price_rank' ] )
					);?>
				<?=
					$this->Form->input( 'quantity', array( 'div'     => false,
					                                       'class'   => 'numeric form-control input-small',
					                                       'label'   => false,
					                                       'data-id' => $uid,
					                                       'value'   => $pricing[ 'quantity' ] )
					);?>
				<?= $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>