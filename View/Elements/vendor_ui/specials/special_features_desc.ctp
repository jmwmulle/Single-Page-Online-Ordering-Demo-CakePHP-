<?php
/**
 * J. Mulle, for xtreme, 3/17/16 7:15 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
?>
<div class="special-feature-condition">
	<div class="row">
		<div class="large-12 columns">
			<? if ( count($features) > 1 ) {?>
			<h4>Features</h4>
			<ul class="special-feature-condition">
				<?php foreach($features as $i => $f) {
						if ($i == "Orb") continue;?>
						<li>
						<?php
							$f_str = $f['choose'] ? "Choose " : "Receive ".$f['quantity'];
							if ( $f['choose'] ) {
								$f_str .= $f['quantity'] > 1 ? ' items' : ' item'." from ";
								$f_str .= $f['orblist_id'] ? $f['Orblist']['name']." (custom list)" : $f['Orbcat']['title']." (category)";
							} else {
								$f_str .= count($features['Orb']) > 1 ? " each:" : ":";
							};?>
						<?=$f_str;?>
						<?php if ($f['receive'] ) {?>
							<ul>
							<?php foreach($features['Orb'] as $o) { echo "<li>".$o['title']." (".implode(array_slice($o,7), ", ").")"."</li>";}?>
							</ul>
							<?php };?>
						</li>
				<?php };?>
			</ul>
			<?php };?>
 		</div>
	</div>
</div>
