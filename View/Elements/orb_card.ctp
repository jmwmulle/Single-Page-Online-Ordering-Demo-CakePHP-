<?php
	/**
	 * J. Mulle, for app, 9/3/14 9:39 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
?>
<div id="orb-card-stage" class="l-2">
	<div class="orb-card">
		<ul class="orb-card">
		<!-- TOP  ROW --> <?php // every row has a button at left and content at right ;?>
			<li class="orb-card-row">
				<div id="favorite" class="orb-card-button">
					<div class="triangle-down"></div>
				</div
				><div id="description" class="orb-card-content">
					<p><?php echo $orb[ 'description' ]; ?></p>
					<h4 id="hidden-description"><?php echo strtoupper($orb[ 'title' ]); ?></h4>
				</div>
			</li>
		<!-- MIDDLE  ROW -->
			<li class="orb-card-row">
				<div id="order" class="orb-card-button">
					<div class="triangle-down"></div>
				</div
				><div id="price-matrix" class="orb-card-content">
							<h4 id="price-matrix-size" class="text-left">SIZE</h4
							><h4 id="price-matrix-price" class="text-right">PRICE</h4>
					<ul class="price-matrix-content">
					<?php foreach ( $orb[ 'price_matrix' ] as $opt => $price ) { ?>
						<li>
							<div class="orb-size text-left"><?php echo $opt == "base" ? "Regular" : ucwords( $opt ); ?></div
							><div class="orb-price text-right"><?php echo money_format("%#3.2n", $price ); ?></div>
						</li>
					<?php } ?>
					</ul>
				</div>
			</li
			><li class="orb-card-row">
				<div id="like" class="orb-card-button">
					<div class="triangle-right"></div>
				</div
				><div id="orb-card-options" class="orb-card-content">
					<ul class="large-block-grid-4">
						<?php
							if (is_array($orb['config'] && count($orb['config']) > 0) ) {
								foreach ($orb['config'] as $opt) {?>
							<li class="orb-card-option"><?php echo $opt;?></li>
							<?php }
							}?>
					</ul>
				</div>
			</li>
		</ul>
	</div>
</div>