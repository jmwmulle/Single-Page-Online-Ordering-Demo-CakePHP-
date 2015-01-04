<?php
	/**
	 * J. Mulle, for app, 9/3/14 9:39 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
$logged_in = $this->Session->read('Auth');

?>
<div id="orb-card-stage" class="l-2 box retracted">
	<div id="orb-card-container" class="box abs l-2-2">
		<h3 id="float-label" class="box float-label text-center"></h3>
		<div id="orb-card" class="html5-3d-perspective-container box rel flush">
			<div id="orb-card-3d-context" class="html5-3d-context preserve-3d">
				<section id="orb-card-front" class="preserve-3d card-face m-pad">
					<ul class="orb-card">
					<!-- TOP  ROW --> <?php // every row has a button at left and content at right ;?>
						<li class="orb-card-row">
							<?php
								$classes = array("orb-card-button, inline, float-labeled", $logged_in ? "" : "disabled");
								$data = array("float-label" => "favorite-label");
								if ($logged_in) $data["route"] = "favorite/orb_card";
							?>
							<div id="favorite" <?php echo __cD($classes);?> <?php echo ___dA($data); ?>>
								<span class="icon-orb-card-favorite"></span>
							</div
							><div id="description" class="orb-card-content inline">
								<div class="orb-card-content-container">
									<p><?php echo $orb[ "description" ]; ?></p>
									<h4 id="hidden-description"><?php echo strtoupper($orb[ "title" ]); ?></h4>
								</div>
							</div>
						</li>
					<!-- MIDDLE  ROW -->
						<li class="orb-card-row">
							<?php $data_array = array("orb-id" => $orb["id"],
							                          "price-rank" => floor(count($orb["price_table"])/2),
							                          "float-label" => "order-label");
								$class_array = array("orb-card-button", "add-to-cart", "float-labeled", "inline");
							?>
							<div id="order" <?php echo ___cD($class_array);?> <?php echo ___dA($data_array);?>>
								<span class="icon-orb-card-cart"></span>
							</div
							><div id="price-matrix" class="flush orb-card-content inline">
								<div class="orb-card-content-container">
									<h5 id="price-matrix-size" class="text-left price-matrix-header">SIZE</h5
									><h5 id="price-matrix-price" class="text-right price-matrix-header">PRICE</h5
									><ul class="price-matrix-content">
									<?php foreach ( $orb[ "price_table" ] as $opt => $price ) {
										$data_array = array("orb-id" => $orb["id"],
										                    "price-rank" => array_search($opt, array_keys($orb[ "price_table" ])));
									?>
										<li class="add-to-cart" <?php echo ___dA($data_array);?>>
											<div class="orb-size text-left"><?php echo $opt == "base" ? "Regular" : ucwords( $opt ); ?></div
											><div class="orb-price text-right"><?php echo money_format("%#3.2n", $price ); ?></div>
										</li>
									<?php } ?>
									</ul>
								</div>
							</div>
						</li
						><li class="orb-card-row">
							<div id="like" class="orb-card-button inline float-labeled" data-float-label="like-label">
								<span class="icon-orb-card-share"></span>
							</div
							><div id="orb-card-options" class="orb-card-content inline">
								<div class="orb-card-content-container">
									<ul class="large-block-grid-4">
									<?php
										if (is_array($orb["config"] && count($orb["config"]) > 0) ) {
											foreach ($orb["config"] as $opt) {?>
										<li class="orb-card-option"><?php echo $opt;?></li>
										<?php }
										}?>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</section>
				<section id="orb-card-back" class="preserve-3d card-face back-face-y m-pad">
					<?php $class_array = array("orb-configuration", "orb-size-panel", "activizing", "flush", "stretch",
					                           sprintf("large-block-grid-%s", count($orb["price_table"])));?>
					<h3 class="orb-opt-configure-header"><?php echo strtoupper($orb["title"]); ?></h3>
					<ul <?php echo ___cD($class_array);?>>
					<?php foreach ( array_keys($orb[ "price_table" ]) as $rank => $opt ) {?>
						<li class="orb-size-button inactive" data-price-rank="<?php echo $rank;?>">
							<h3 class="flush xtreme-select-list"><?php echo strtoupper($opt);?></h3>
						</li>
					<?php };?>
					</ul

					><div id="order-details" class="orb-card-row"
						><div id="orb-order-form" class="inline">
							<?php
							echo $this->Form->create("Order", array("action" => "add_to_cart", "id" => "orderOrbForm"));
							echo $this->Form->input("Order.Orb.id", array("type" => "hidden", "value" => $orb['id']));
							echo $this->Form->input("Order.Orb.price_rank", array("type" => "hidden", "value" => 0));?>
							<div id="OrderOrbPreparationInstructions-wrapper" class="t-pad inline">
								<label for="orderOrbPreparationInstructions">PREPARATION INSTRUCTIONS</label
								><input name="data[Order][Orb][preparation_instructions]" type="text" id="OrderOrbPreparationInstructions">
							</div
							><div id="OrderOrbQuantity-wrapper" class="inline">
								<label for="orderOrbQuantity">QUANTIY</label>
								<input type="text" name="data[Order][Orb][quantity]" id="OrderOrbQuantity" value="1" />
							</div>
							<?php
							foreach($orb["Orbopt"] as $opt) {
								$field_name = sprintf("Order.Orb.orbopts.%s", $opt["id"]);
								echo $this->Form->input($field_name, array( "type" => "hidden", "value" => -1, "class" => array("orb-opt-weight")));
							}
							echo $this->Form->end();?>
						</div
						><div id="tiny-orb-opts-list" class="inline">
							<label for="">TOPPINGS & OPTIONS</label>
							<div id="tiny-orb-opts-list-wrapper"></div
						></div
					></div
					><div id="orb-finalize-details" class="inline orb-card-row text-center">
						<a id="cancel-order-button" href="#" class="xtreme-button secondary left cancel-order">Cancel</a>
						<a id="confirm-order-button" href="#" class="xtreme-button right confirm-order">Confirm</a>
					</div>
				</section>
			</div>
		</div>
	</div>

<?php if ($ajax) { ?>
	<div id="orb-opts-container" class="true-hidden"
		<?php
			echo $this->Element('orb_opts_menu_header');
			echo $this->Element('orb_opts_list', array('orb' => $orb, 'ul' => true));?>
	</div>
<?php } ?>
</div>



