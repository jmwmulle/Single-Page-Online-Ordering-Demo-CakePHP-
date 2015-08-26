<?='<div id="optflag-filter-header" class="slide-right hidden box rel orb-card-stage-menu-header">';?>
<?php if (!empty($optflags) ): ?>
	<?='<ul id="orb-opt-filters" class="multiactivizing">';?>
	<?php foreach( $optflags  as $id => $optflag):
		$data = ["optflag-id" => $id, "route" => "optflag/$id/filter"];
		$classes = ["orb-opt-filter", "active"];?>
	<?= sprintf("<li id='optflag-$id' %s %s><span class='icon-checked'></span>%s</li>", ___cD($classes), ___dA($data),strtoupper($optflag));?>
	<?php endforeach;?>
		<?='<li id="orb-opt-filters-all" class="box rightward">';?>
			<?='<span class="icon-tab-arrow-l"></span>';?>
			<?='<ul class="flush">';?>
				<?='<li><a href="#" class="orb-opt-filter-all" data-route="optflag/-*/filter">UNCHECK ALL</a></li>';?>
				<?='<li><a href="#" class="orb-opt-filter-all" data-route="optflag/*/filter" >CHECK ALL</a></li>';?>
			<?='</ul>';?>
		<?='</li>';?>
	<?='</ul>';?>
<?php else:?>
	<?='<h2>Nothing to see here!</h2>';?>
<?php endif;?>
<?='</div>';?>