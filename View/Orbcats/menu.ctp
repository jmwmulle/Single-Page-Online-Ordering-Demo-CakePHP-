<?php
	/**
	 * J. Mulle, for app, 9/5/14 1:03 AM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
$store_status = null;
$delivery = null;
if ( !is_array($system) ) {
	$system = json_decode($system, true)['data']['system'];
	$store_status = $system['store_open'];
	$delivery = $system['delivery_available'];
	$online_ordering = $system['online_ordering'];
} else {
	$store_status = $system[STORE_OPEN - 1]['Sysvar']['status'];
	$delivery = $system[DELIVERY_AVAILABLE - 1]['Sysvar']['status'];
	$online_ordering = $system[ONLINE_ORDERING - 1]['Sysvar']['status'];
}
?>

<?= $this->Element( "menu_ui/top_bar", ['render_transparent' => $render_transparent,
                                        'online_ordering' => $online_ordering,
                                        'store_open' => $store_status,
                                        'delivering' => $delivery
] ); ?>
<div class="row">
	<main id="menu" class="large-12 columns<?= $this->get( "render_transparent" ) ? " fade-out" : null; ?>">
		<div class="row">
			<div class="large-12 columns">
				<?= $this->Element( 'menu_ui/user_activity_panel', compact( 'order' ) ); ?>
				<?=$this->Element( 'menu_ui/orbcat_menu', [ 'active'  => $menu[ 'Orbcat' ],
					                                         'orbcats' => $orbcats ]); ?>
				<?= $this->Element( 'menu_ui/specials' ); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12  columns show-for-large-up">
				<div id="orb-card-wrapper" class="float-pane box rel xtreme-blue-bg">
					<div id="orb-card-overlay" class="box rel l-2-2 hidden fade-out"></div>
					<?= $this->Element( 'orbcard/modal' ); ?>
					<?= $this->Element( 'orbcard/orbcard', ['content' => $orbcard,
					                                        'online_ordering' => $online_ordering,
					                                        'ajax' => false] ); ?>
					<?= '<div id="orb-card-stage-menu-wrapper" class="box rightward xtreme-blue-bg">'; ?>
					<?=
						$this->Element( 'orbcard/menu', [ 'ajax'     => false,
						                                  'content'  => $menu,
						                                  'active'   => $orbcard[ 'Orb' ],
						                                  'optflags' => $orbcard[ 'Orb' ][ 'Optflag' ] ]
						);?>
				</div>
			</div>
		</div>
	</main>
</div>
