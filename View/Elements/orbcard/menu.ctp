<?php
/**
 * J. Mulle, for app, 11/29/14 3:09 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */


$filter = $this->Element('orbcard/optflag_filter_header', compact('optflags'));
$orb_list = $this->Element('orbcard/menu_list', ['ajax' => $ajax,
                                             'type' => 'orb',
                                             'portionable' => $menu['Orbcat']['portionable'],
                                             'active' => $active,
                                             'content' => $content]);
$opt_list = $this->Element('orbcard/menu_list', ['ajax' => $ajax,
                                             'type' => 'opt',
                                             'portionable' => $menu['Orbcat']['portionable'],
                                             'active' => false,
                                             'content' => $active['Orbopt']]);

if ($ajax):
	echo json_encode(['success' => true,
	                  'error' => false,
	                  'data' => ['route' => "load_orb/".$orbcard['Orb']['id'],
	                             'orbcat' => $content['Orbcat']['menu_title'],
					             'view_data' => compact('filter', 'opt_list', 'orb_list')]]);
else:
	?>

<?='<div id="orb-stage-menu-header-container">';?>
	<?=$this->Element("orbcard/menu_header", ['title' => $content['Orbcat']['menu_title']]);?>
	<?=$filter;?>
<?='</div>';?>
<?=$orb_list;?>
<?=$opt_list;?>
<?//=implode("\n", compact('header','orb_list', 'opt_list', 'filter'));?>
<?php endif;