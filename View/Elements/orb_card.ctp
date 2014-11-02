<?php
	/**
	 * J. Mulle, for app, 9/3/14 9:39 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
?>

<div class="orb-card">
	<ul class="orb-card-buttons">
		<li id="favorite"></li>
		<li id="order"></li>
		<li id="share"></li>
	</ul>
	<ul class="orb-card-content tight">
		<li id="description"><?php echo $orb[ 'description' ]; ?></li>
		<li id="price-grid">
			<ul class="large-block-grid-<?php echo count($orb['price_matrix']);?> price-list">
				<?php foreach ( $orb[ 'price_matrix' ] as $opt => $price ) { ?>
					<li>
						<dl class="text-center">
							<dt><?php echo $opt == "base" ? " " : ucwords( $opt ); ?></dt>
							<dd><?php echo money_format("%#3.2n", $price ); ?></dd>
						</dl>
					</li>
				<?php } ?>
			</ul>
		</li>
		<li id="orbcard-options">
		</li>
	</ul>
</div>