<?php
	/**
	 * J. Mulle, for app, 9/3/14 9:39 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
$logged_in = $this->Session->read('Auth');
$orb = $content['Orb'];
$prices = $content['Prices'];
?>
<div id="orb-card-stage" class="l-2 box retracted">
	<div id="orb-card-container" class="box abs l-2-2">
		<h3 id="float-label" class="box float-label text-center"></h3>
		<div id="orb-card" class="html5-3d-perspective-container box rel flush" data-default-opts="<?=json_encode($orb['default_opts']);?>">
			<div id="orb-card-3d-context" class="html5-3d-context preserve-3d">
<!--  FRONT ------->
				<section id="orb-card-front" class="preserve-3d card-face m-pad">
					<ul class="orb-card">
					<?=$this->Element('orbcard/rows/top', compact('orb', 'ajax'))?>
					<!-- MIDDLE  ROW -->
						<li id="orb-card-row-2" class="orb-card-row">
							<?php $data_array = [
							                          "price-rank" => floor( count($prices) / 2 ),
							                          "float-label" => "order",
							                          "route" => "configure_orb".DS.$orb['id'].DS.'0'];
								$class_array = ["orb-card-button", "float-labeled", "inline"];
							?>
							<div id="order" <?=___cD($class_array);?> <?=___dA($data_array);?>>
								<span class="icon-orb-card-cart"></span>
							</div
							><div id="price-matrix" class="flush orb-card-content inline">
								<div class="orb-card-content-box<?=$ajax ? " fade-out" : "";?>">
									<h5 id="price-matrix-size" class="text-left price-matrix-header">SIZE</h5
									><h5 id="price-matrix-price" class="text-right price-matrix-header">PRICE</h5
									><ul class="price-matrix-content">
									<?php foreach ( $content[ "Prices" ] as $label => $price ) {
										$route = "configure_orb".DS.$orb["id"].DS.array_search($label, array_keys($content[ "Prices" ]));?>
										<li class="add-to-cart" data-route="<?=$route?>">
											<div class="orb-size text-left"><?=$label == "base" ? "Regular" : ucwords( $label ); ?></div
											><div class="orb-price text-right"><?=money_format("%#3.2n", $price ); ?></div>
										</li>
									<?php } ?>
									</ul>
								</div>
							</div>
						</li><?=""
					  ?><li id="orb-card-row-3" class="orb-card-row">
							<div id="like" class="orb-card-button inline float-labeled disabled" data-float-label="coming soon">
								<span class="disabled icon icon-orb-card-share" data-route="orb_card/share"></span
								<?php if (!$logged_in) {?>
								><div id="orb-card-social-panel" class="fade-out orb-card-row-panel"
									><a id="orb-card-register-twitter" href="https://twitter.com/intent/tweet?screen_name=XtremePizza&text=XtremePizza!%20Halifax!%20Delicious!%20This%20menu%20is%20huge! #XtremePizza #XtremePizzaHalifax #iheartbigmenus" class="twitter-mention-button" data-routse="orb_card/share/twitter"
										><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><span class="icon-twitter"></span
									></a
									><a id="orb-card-register-facebook" href="#" data-route="orb_card/share/facebook"
										><span class="icon-facebook"></span
									></a
									><a id="orb-card-register-gplus" href="#" data-route="orb_card/share/gplus"
										><span class="icon-gplus"></span
									></a
								></div
							<?php } ?>
							></div
							><div id="orb-card-options" class="orb-card-content inline">
								<div class="orb-card-content-box<?=$ajax ? " fade-out" : "";?>">
									<ul class="large-block-grid-4">
									<?php
										if (is_array($orb["config"] && count($orb["config"]) > 0) ) {
											foreach ($orb["config"] as $opt) {?>
										<li class="orb-card-option"><?=$opt;?></li>
										<?php }
										}?>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</section>

<!--  BACK ------->

				<section id="orb-card-back" class="preserve-3d card-face back-face-y m-pad">
					<?php $class_array = ["orb-configuration", "orb-size-panel", "activizing", "flush", "stretch",
					                           sprintf("large-block-grid-%s", count($prices))];?>
					<h3 class="orb-opt-configure-header"><?=strtoupper($orb["title"]); ?></h3>
					<ul <?=___cD($class_array);?>>
					<?php foreach ( array_keys($prices) as $rank => $opt ):?>
						<li class="orb-size-button inactive" data-rank=<?=$rank;?> data-route="price_rank/<?=$rank;?>">
							<h3 class="flush xtreme-select-list"><?=strtoupper($opt);?></h3>
						</li>
					<?php endforeach;?>
					</ul><?=""
				       ?><div id="order-details" class="orb-card-row"><?=""
					   ?><div id="orb-order-form" class="inline">
							<?=$this->Form->create("Order", ["action" => "add_to_cart", "id" => "orderOrbForm"]);?>
							<?=$this->Form->input("Order.Orb.id", ["type" => "hidden", "value" => $orb['id']]);?>
							<?=$this->Form->input("Order.Orb.uid", ["type" => "hidden", "value" => null]);?>
							<?=$this->Form->input("Order.Orb.price_rank", ["type" => "hidden", "value" => 0]);?>
							<div id="OrderOrbOrbNote-wrapper" class="t-pad inline">
								<label for="orderOrbOrbNote">PREPARATION INSTRUCTIONS</label
								><input name="data[Order][Orb][orb_note]" type="text" id="OrderOrbOrbNote">
							</div
							><div id="OrderOrbQuantity-wrapper" class="inline">
								<label for="orderOrbQuantity">QUANTIY</label>
								<input type="text" name="data[Order][Orb][quantity]" id="OrderOrbQuantity" value="1" />
							</div>
							<?php
							foreach($content['Orb']['Orbopt'] as $opt) {
								$field_name = sprintf("Order.Orb.Orbopt.%s", $opt["id"]);
								echo $this->Form->input($field_name, [ "type" => "hidden", "value" => -1, "data-id" => $opt['id'], "class" => ["orb-opt-weight"]]);
							}
							echo $this->Form->end();?>
						</div
						><div id="tiny-orb-opts-list" class="inline">
							<label for="">TOPPINGS & OPTIONS</label>
							<div id="tiny-orb-opts-list-wrapper"></div
						></div
					></div
					><div id="orb-finalize-details" class="orb-card-row text-center">
						<a id="cancel-order-button" href="#" class="rounded modal-button bisecting cancel left" data-route="cart/cancel">
							<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span>
						</a>
						<a id="confirm-order-button" href="#" class="rounded modal-button bisecting confirm right" data-route="cart/add">
							<span class="text">Confirm</span><span class="icon-circle-arrow-r right"></span>
						</a>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
