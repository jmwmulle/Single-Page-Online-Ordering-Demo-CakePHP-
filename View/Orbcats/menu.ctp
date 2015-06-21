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
?>

<?= $this->Element( "top_bar" ); ?>
<div class="row">
	<main id="menu" class="large-12 columns<?=$this->get( "is_splash" ) ? " fade-out" : null;?>">
		<div class="row">
			<div class="large-9 small-5 columns">
				<div class="row">
					<div class="large-1 columns show-for-large-up">
						<?=$this->Element('user_activity_panel', ['order' => $order]);?>
					</div>
					<div class="large-11 small-12 columns">
						<?=$this->Element('orbcat_menu', array('active' => $menu['Orbcat'], 'orbcats' => $orbcats));?>
					</div>
				</div>
			</div>
			<div class="large-3 columns show-for-large-up">
				<?=$this->Element('specials');?>
			</div>
		</div>
		<div class="row">
			<div class="large-12  columns show-for-large-up">
			<?='<div id="orb-card-wrapper" class="float-pane box rel xtreme-blue-bg">'?>
					<?=$this->Element('orbcard_modal');?>
					<?=$this->Element('orbcard', array('content' => $orbcard,  'menu' => $menu));?>
				</div>
			</div>
		</div>
	</main>
</div>
