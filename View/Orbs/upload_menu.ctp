<?php
	/**
	 * J. Mulle, for app, 2/2/15 5:49 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */
	function orb_config_route($orb_id, $orb_attr) {
		echo ___dA(array('restore' => null, 'route' => sprintf('orb_config/edit/%s/%s', $orb_id, $orb_attr)));
	}
//	function toppings_cats($id = false, $orbcats) {
//		$check_boxes = "";
//		$form_str    = "<input id='OrboptOrbcat' name='OrboptOrbcat' value='%s' type='checkbox' rel='8'>%s</input>";
//		$table_str   = "<input id='orb-$id-is-%s' type='checkbox'>%s</input>";
//		foreach ( $orbcats as $oc ) {
//			$check_boxes .= $id ? sprintf( $form_str, $oc[ 'Orbcat' ][ 'title' ] ) : sprintf( $table_str, $oc[ 'Orbcat' ][ 'id' ], $oc[ 'Orbcat' ][ 'title' ] );
//		}
//
//		return $check_boxes;
//	}
//	$optcats = toppings_cats( false, $orbcats );
//db($orbopts);

?>

<div id="menu-editor" class="row">
	<div class="large-12 columns">
		<div id="ui-tabs">
			<ul>
				<li><a href="#menu-tab">Menu</a></li>
				<li><a href="#menu-options-tab">Menu Options</a></li>
			</ul
			><div id="menu-tab"
				><div class="row">
					<table role="grid" id="menu-table" class="large-12 columns">
						<thead>
						<tr>
							<th><a href="#">Name</a></th>
							<th><a href="#">Description</a></th>
							<th><a href="#">Category</a></th>
							<th><a href="#">Size 1</a></th>
							<th><a href="#">Size 2</a></th>
							<th><a href="#">Size 3</a></th>
							<th><a href="#">Size 4</a></th>
							<th><a href="#">Size 5</a></th>
							<th><a href="#">Configuration</a></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ( $orbs as $orb ) {
							$id = $orb['Orb']['id'];
							$o = $orb['Orb'];
							$data = array('orb-id' => $id, 'orb-opts' => Hash::extract($orb['Orbopt'], "{n}.id"), 'orbopts-orbcats' => array());

							?>
							<tr id="<?php echo $id; ?>" <?php echo ___dA($data);?>>
								<td>
									<div class="orb-attr display" <?php orb_config_route($id, "title");?>>
										<?php echo $orb[ 'Orb' ][ 'title' ]; ?>
									</div>
									<div class="orb-attr edit ce"
								</td>
								<td <?php orb_config_route($id, "description");?>><?php echo $o[ 'description' ] ? $o[ 'description' ] : "&nbsp;" ?></td>
								<td <?php orb_config_route($id, "orbcat");?>><?php echo $orb[ 'Orbcat' ][ 0 ][ 'full_title' ]; ?></td>
								<?php for ($i = 1; $i <= 5; $i++) {?>
								<td <?php orb_config_route($id, "price_$i");?>">
									<?php echo $this->Element('vendor_ui/size_column', array('dict_val' => $orb['Pricedict']["l$i"],
									                                                           'list_val' => $orb['Pricelist']["p$i"]));?>
								</td>
								<?php }

								$data = array('name' => 'orbopts', 'orbopts' =>Hash::extract($orb['Orbopt'], "{n}.id"));
								?>
								<td id="orb-<?php echo $id;?>-orbopts-cell" <?php echo ___dA($data)?> <?php echo ___cD("invisiform");?> >
									<a href="#" data-route="<?php echo sprintf("orbopt_config/launch/%s", $id);?>">Double-Click to View</a>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
					<?php

						$price_labels = array("-", "Small", "Medium", "Large", "New..." );
						$price_label_selects = "";
						foreach ( $price_labels as $i => $price_label ) {
							$price_label_selects .= sprintf( '<option value="%s">%s</option>', $i, $price_label );
						}

					?>
					<form id="formAddNewRow" action="#" class="hidden">
						<label for="Orb[name]">Name</label><input type="text" name="Orb[name]" id="OrbName" class="required"
						                                          rel="0"/>
						<br/>
						<label for="Orb[description]">Description</label><input type="text" name="Orb[description]"
						                                                        id="OrbDescription" rel="1"/>
						<br/>
						<label for="Orb[Orbcat][id]">Category</label>
						<?php echo $this->Form->input('OrbcatId', array('options' => $orbcats, 'div' => false, 'name' => "Orb[Orbcat][id]", "rel" => 2));?>
							<br/>
							<label for="Orb[Pricedict][label_1]">Size 1</label><select name="Pricedict[label_1]"
							                                                           id="PricedictLabel1"
							                                                           rel="3"><?php echo $price_label_selects; ?></select>
							<br/>
							<label for="Orb[Pricedict][label_2]">Size 2</label><select name="Pricedict[label_2]"
							                                                           id="PricedictLabel2"
							                                                           rel="4">><?php echo $price_label_selects; ?></select>
							<br/>
							<label for="Orb[Pricedict][label_3]">Size 3</label><select name="Pricedict[label_3]"
							                                                           id="PricedictLabel3"
							                                                           rel="5"><?php echo $price_label_selects; ?></select>
							<br/>
							<label for="Orb[Pricedict][label_4]">Size 4</label><select name="Pricedict[label_4]"
							                                                           id="PricedictLabel4"
							                                                           rel="6"><?php echo $price_label_selects; ?></select>
							<br/>
							<label for="Orb[Pricedict][label_5]">Size 5</label><select name="Pricedict[label_5]"
							                                                           id="PricedictLabel5"
							                                                           rel="7"><?php echo $price_label_selects; ?></select>
							<br/>
							<label for="Orb[config]">Configuration</label><input name="Orb[config" id="OrbFormConfig"
							                                                     rel="9"/><br/>
					</form>
				</div
			></div
			><div id="menu-options-tab">
				<div class="row">
					<?php
					$opt_cats = array( "meat", "veggie", "cheese", "sauce", "condiment" );
					$opt_select = "<select>";
					foreach ( $opt_cats as $oc ) {
						$opt_select .= sprintf( "<option>%s</option>", $oc );
					}
					$opt_select .= "</select>";?>
						<table role="grid" class="large-12 columns">
							<thead>
							<tr>
								<th>Category</th>
								<th>Pizzas</th>
								<th>Burgers</th>
								<th>Pitas & Sandwhiches</th>
								<th>Subs</th>
								<th>Donairs</th>
								<th>Fingers</th>
								<th>Poutines</th>
								<th>Nachos</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach ( $orbopts as $opt ) { ?>
								<tr>
									<td><?php echo $opt[ 'Orbopt' ][ 'title' ]; ?></td>
									<td><?php echo $opt_select ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'pizza' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'burger' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'pita' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'subs' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'donair' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'nacho' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'poutines' ]; ?></td>
									<td><?php echo $opt[ 'Orbopt' ][ 'fingers' ]; ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	$this->Html->scriptStart(array('inline' => false, 'block' => 'vendor'));
	echo sprintf("var orbcats = %s", json_encode($orbcats));
	$this->Html->scriptEnd();?>
<!--<div class="row">-->
<!--	<div id="upload-menu-form-container" class="large-8 large-centered columns">-->
<!--		--><?php //if ( $this->Session->read( 'Upload' ) ) { ?>
<!--			<h3 class="success">Upload Successful — Menu Updated</h3>-->
<!--		--><?php //} ?>
<!--		<h4>Upload Xtreme Menu Options & Xtreme Menu Documents</h4>-->
<!--		--><?php //echo $this->Form->create( 'menu_upload', array( 'type' => 'file' ) );
//			echo $this->Form->input( 'menu', array( 'type' => 'file', 'label' => 'Xtreme Menu' ) );
//			echo $this->Form->input( 'opts', array( 'type' => 'file', 'label' => 'Xtreme Menu Options' ) );
//			echo $this->Form->end( 'Submit' );
//		?>
<!--	</div>-->
<!--</div>-->
<!---->
