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
	<div id="favorite-label" class="float-label"><h2>FAVORITE</h2></div>
	<div id="order-label" class="float-label"><h2>ORDER</h2></div>
	<div id="like-label" class="float-label"><h2>LIKE</h2></div>
	<div id="orb-card-shadow-wrapper">
	<div class="orb-card">
		<div id="orb-card-3d-pane">
			<section id="orb-card-front" class="orb-card-face">
				<ul class="orb-card">
				<!-- TOP  ROW --> <?php // every row has a button at left and content at right ;?>
					<li class="orb-card-row">
						<div id="favorite" class="orb-card-button float-labeled" data-float-label="favorite-label">
							<div class="triangle-down"></div>
						</div
						><div id="description" class="orb-card-content">
							<p><?php echo $orb[ 'description' ]; ?></p>
							<h4 id="hidden-description"><?php echo strtoupper($orb[ 'title' ]); ?></h4>
						</div>
					</li>
				<!-- MIDDLE  ROW -->
					<li class="orb-card-row">
						<?php $data_array = array("orb-id" => $orb['id'],
						                          "price-rank" => floor(count($orb['price_table'])/2),
						                          "float-label" => "order-label");
							$class_array = array("orb-card-button", "add-to-cart", "float-labeled");
						?>
						<div id="order" <?php echo ___cD($class_array);?> <?php echo ___dA($data_array);?>>
							<div class="triangle-down"></div>
						</div
						><div id="price-matrix" class="orb-card-content">
									<h4 id="price-matrix-size" class="text-left">SIZE</h4
									><h4 id="price-matrix-price" class="text-right">PRICE</h4>
							<ul class="price-matrix-content">
							<?php foreach ( $orb[ 'price_table' ] as $opt => $price ) {
								$data_array = array("orb-id" => $orb['id'],
								                    "price-rank" => array_search($opt, array_keys($orb[ 'price_table' ])));
							?>
								<li class="add-to-cart" <?php echo ___dA($data_array);?>>
									<div class="orb-size text-left"><?php echo $opt == "base" ? "Regular" : ucwords( $opt ); ?></div
									><div class="orb-price text-right"><?php echo money_format("%#3.2n", $price ); ?></div>
								</li>
							<?php } ?>
							</ul>
						</div>
					</li
					><li class="orb-card-row">
						<div id="like" class="orb-card-button float-labeled" data-float-label="like-label">
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
			</section>
			<section id="orb-card-back" class="orb-card-face">
				<?php $class_array = array("orb-configuration",
										  "orb-size-panel",
										  "activizing",
										  sprintf("large-block-grid-%s", count($orb['price_table'])));?>
				<ul <?php echo ___cD($class_array);?>>
					<?php foreach ( array_keys($orb[ 'price_table' ]) as $rank => $opt ) {?>
						<li class="orb-size-button inactive" data-price-rank="<?php echo $rank;?>">
							<h3><?php echo strtoupper($opt);?></h3></li>
					<?php };?>
					<li>
					<ul>
						<?php foreach ($orb['Orbopt'] as $opt) {
							if ($opt['pizza']) {?>
							<li class="topping" data-opt-id="<?php echo $opt['id'];?>"><?php echo $opt['title'];?></li>
							<?php }} ?>
						}?>
					</ul>
				</ul>
				<?php
					$this->Form->create('Orb', array('action' => ___cakeUrl('order', 'add_to_cart')));
					$this->Form->input('id', array('type' => 'hidden', 'value' => $orb['id']));
					$this->Form->input('quantity', array('type' => 'hidden', 'value' => $orb['id']));
					$this->Form->input('orbopts', array('type' => 'select', 'options' => $options));
					$this->Form->end();?>
				<a class="tiny button" style="position:absolute; bottom:10px; right:10px" onclick="$('#orb-card-3d-pane').removeClass('flipped')">Confirm</a>
			</section>
		</div>
	</div>
	</div>
</div>x`