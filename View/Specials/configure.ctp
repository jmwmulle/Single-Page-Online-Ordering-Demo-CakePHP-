<?php
/**
 * J. Mulle, for app, 12/8/15 9:42 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$special= ["title" => "Every Day Deal"];
$orbs = [1      =>['title' => 'Big Pizza', 'opt_count' => 5],
         12     =>['title' => 'Medium Fingers', 'opt_count' => 1],
         42     =>['title' => '12 Wings', 'opt_count' => 1],
         118    =>['title' => '2L Pop', 'opt_count' => 0]];
?>
<div class="row">
	<main id="specials-config">
		<div class="large-12 columns">
			<div id="specials-config-box" class="float-pane">
				<h1>Xtreme Deal: <?=$special['title'];?></h1>
				<ul id="specials-content">
					<?php foreach ($orbs as $id => $o):?>
					<li class="specials-item">
						<a href="#" class='specials-item' data-route="specials_config/<?=$id;?>>
							<h3><?=$o['title'];?><span class="icon-unchecked></span></h3
						</a>
					</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
	</main>
</div>