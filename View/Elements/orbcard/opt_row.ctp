<?php
	$list_classes = ["orb-opt", "xtreme-select-list"];
if ($opt):
	array_push($list_classes, $opt['default'] ? 'active default' : 'inactive');

	$optflags =  $this->get('optflags_list');

	$icons = ['right-side' => "R", 'full' => "F", 'left-side' => "L", 'double' => "D"];
	$data = ["route" => sprintf("orb_opt/toggle/%s", $opt['id']), 'optflags' => [], 'id' => $opt['id'], 'title' => $opt['title']];
	if ( array_key_exists("Optflag", $opt) ):
		foreach ($opt['Optflag'] as $flag):
			if ( $flag[ 'title' ] ):
				array_push( $list_classes, $flag[ 'title' ] );
				array_push( $data[ 'optflags' ], $flag[ 'id' ] );
			endif;
		endforeach;
	endif;?>
	<?=sprintf("<li id='orb-opt-%s' %s %s>", $opt['id'], ___cD($list_classes), ___dA($data)); ?>
	<?='<ul class="stretch inline">';?>
	<?php foreach ($icons as $icon => $value):
				$classes = array("orb-opt-coverage", $icon, "icon-$icon", "inactive", "disabled");
				if ($icon == "full") $classes[3] = "active";
				if ($opt['default']) $classes[4] = "enabled";
//				$id = sprintf("orb-opt-%s-weight-%s", $opt['id'], $value);
				$data = ['route' => implode(DS, ["orb_opt_weight", $opt['id'], $value])];
				echo sprintf("<li %s %s></li>", ___cD($classes), ___dA($data));
			endforeach;?>
	<?=sprintf('<li><a href="#">%s</a></li>', strtoupper($opt['title']));?>
	<?='</ul>';?>
	<?='</li>';?>
<?php else:
	echo sprintf('<li %s></li>', ___cD($list_classes));
endif;