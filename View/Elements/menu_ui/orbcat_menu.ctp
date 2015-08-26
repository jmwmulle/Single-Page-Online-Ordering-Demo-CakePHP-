<?="<ul id='orbcat-menu' class='large-block-grid-6 small-block-grid-3 float-pane activizing left box rel'>";?>
<?php foreach ($orbcats as $id => $orbcat):
	$uc_orbcat = ucfirst($orbcat);?>
<?=sprintf("<li %s data-route='orbcat/$id/$uc_orbcat'>", ___cD(["orbcat", $id == $active['id'] ? "active" : "inactive" ]));?>
<?=sprintf("<a class='text-center'><span class='orbcat-icon icon-%s'></span>$uc_orbcat</a></li>", ___strToSel(str_replace("&nbsp;", "_", $orbcat)));?>
<?php endforeach;?>
<?='<li id="orbcat-menu-title" class="stretch box rel downward">';?>
<?=sprintf("<h1>MENU/<span>%s</span></h1></li></ul>", $active['menu_title']);?>