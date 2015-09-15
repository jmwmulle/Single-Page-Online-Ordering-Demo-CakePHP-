<?php
	/**
	 * J. Mulle, for app, 9/5/14 1:03 AM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
?>

<?= $this->Element( "menu_ui/top_bar", compact('render_transparent') ); ?>
<div class="row">
	<main id="menu" class="large-12 columns<?= $this->get( "render_transparent" ) ? " fade-out" : null; ?>">
		<div class="row">
			<div class="large-9 small-5 columns">
				<div class="row">
					<div class="large-1 columns show-for-large-up">
						<?= $this->Element( 'menu_ui/user_activity_panel', compact( 'order' ) ); ?>
					</div>
					<div class="large-11 small-12 columns">
						<?=$this->Element( 'menu_ui/orbcat_menu', [ 'active'  => $menu[ 'Orbcat' ],
							                                         'orbcats' => $orbcats ]); ?>
					</div>
				</div>
			</div>
			<div class="large-3 columns show-for-large-up">
				<?= $this->Element( 'menu_ui/specials' ); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12  columns show-for-large-up">
				<div id="orb-card-wrapper" class="float-pane box rel xtreme-blue-bg">
					<div id="orb-card-overlay" class="box rel l-2-2 hidden fade-out"></div>
					<?= $this->Element( 'orbcard/modal' ); ?>
					<?= $this->Element( 'orbcard/orbcard', ['content' => $orbcard, 'menu'=> $menu, 'ajax' => false] ); ?>
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
