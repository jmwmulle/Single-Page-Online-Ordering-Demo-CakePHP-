<?php
	/**
	 * J. Mulle, for app, 9/5/14 1:03 AM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */

//db($orbcard);
//	db($menu);
if ( $refreshing ) { // ie. replacing contents of #orb-card-stage-menu-wrapper (line 49 below)
	echo $this->Element( 'orbcard_menu', array( 'content'     => $menu,
	                                            'active'      => $orbcard[ 'Orb' ],
	                                            'optflags'    => $orbcard[ 'Orb' ][ 'Optflag' ])
//	                                            'portionable' => $orbcard[ 'Orb' ][ 'allow_half_portions' ] )
	);
}
else {
	?>

	<?= $this->Element( "top_bar" ); ?>
	<div class="row">
		<main id="menu" class="large-12 columns<?= $this->get( "is_splash" ) ? " fade-out" : null; ?>">
			<div class="row">
				<div class="large-9 small-5 columns">
					<div class="row">
						<div class="large-1 columns show-for-large-up">
							<?= $this->Element( 'user_activity_panel', [ 'order' => $order ] ); ?>
						</div>
						<div class="large-11 small-12 columns">
							<?= $this->Element( 'orbcat_menu', array( 'active'  => $menu[ 'Orbcat' ],
							                                          'orbcats' => $orbcats )
							); ?>
						</div>
					</div>
				</div>
				<div class="large-3 columns show-for-large-up">
					<?= $this->Element( 'specials' ); ?>
				</div>
			</div>
			<div class="row">
				<div class="large-12  columns show-for-large-up">
					<?= '<div id="orb-card-wrapper" class="float-pane box rel xtreme-blue-bg">' ?>
					<?= $this->Element( 'orbcard_modal' ); ?>
					<?= $this->Element( 'orbcard', array( 'content' => $orbcard, 'menu' => $menu ) ); ?>
					<?= '<div id="orb-card-stage-menu-wrapper" class="box rightward xtreme-blue-bg">'; ?>
					<?=
						$this->Element( 'orbcard_menu', array( 'content'     => $menu,
						                                       'active'      => $orbcard[ 'Orb' ],
						                                       'optflags'    => $orbcard[ 'Orb' ][ 'Optflag' ])
//						                                       'portionable' => $orbcard[ 'Orb' ][ 'allow_half_portions' ] )
						);?>
				</div>
			</div>
	</div>
	</div>
	</main>

<?php } ?>