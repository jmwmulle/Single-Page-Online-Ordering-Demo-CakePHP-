<?php
/**
 * J. Mulle, for app, 2/2/15 5:49 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
function toppings_cats($id) {
	return sprintf('<ul id="orb-%s-opt-cats-list" class="large-block-grid-3">
						<li class="tiny button">burger</li>
						<li class="tiny button">salad</li>
						<li class="tiny button">pizza</li>
						<li class="tiny button">pita</li>
						<li class="tiny button">subs</li>
						<li class="tiny button">donair</li>
						<li class="tiny button">nacho</li>
						<li class="tiny button">poutines</li>
						<li class="tiny button">fingers</li>
					</ul>', $id);
}
$show_tables = false;
if ($show_tables) {?>

<div class="row">
	<div class="large-12 columns">
		<table role="grid">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Category</th>
					<th>SubCategory</th>
					<th>Size 1</th>
					<th>Size 2</th>
					<th>Size 3</th>
					<th>Size 4</th>
					<th>Size 5</th>
					<th>Toppings</th>
					<th>Configure</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($orbs as $orb) {?>
				<tr>
					<td><a href="#" route=""><?php echo $orb['Orb']['title'];?></a></td>
					<td><a href="#" route=""><?php echo $orb['Orb']['description'];?></a></td>
					<td><a href="#" route=""><?php echo $orb['Orbcat'][0]['title'];?></a></td>
					<td><a href="#" route=""><?php echo $orb['Orbcat'][0]['subtitle'];?></a></td>
					<td><a href="#" route="">
						<?php
							if ($orb['Pricedict']['l1']) {
								echo sprintf("Label: %s<br />", $orb['Pricedict']['l1']);
								echo money_format("%#3.2n", $orb['Pricelist']['p1']);
							} else { echo "-"; }?>

					</a></td>
					<td><a href="#" route="">
						<?php
							if ($orb['Pricedict']['l2']) {
								echo sprintf("Label: %s<br />", $orb['Pricedict']['l2']);
								echo money_format("%#3.2n", $orb['Pricelist']['p2']);
							} else { echo "-";}?>
					</a></td>
					<td><a href="#" route="">
						<?php if ($orb['Pricedict']['l3']) {
								echo sprintf("Label: %s<br />", $orb['Pricedict']['l3']);
								echo money_format("%#3.2n", $orb['Pricelist']['p3']);
						} else { echo "-"; }?>
					</a></td>
					<td><a href="#" route="">
						<?php
							if ($orb['Pricedict']['l4']) {
								echo sprintf("Label: %s<br />", $orb['Pricedict']['l4']);
								echo money_format("%#3.2n", $orb['Pricelist']['p4']);
							} else { echo "-"; }?>
					</a></td>
					<td><a href="#" route="">
						<?php
							if ($orb['Pricedict']['l5']) {
								echo sprintf("Label: %s<br />", $orb['Pricedict']['l5']);
								echo money_format("%#3.2n", $orb['Pricelist']['p5']);
							} else { echo "-"; }?>
					</a></td>
					<td><?php echo toppings_cats($orb['Orb']['id']);?></td>
				</tr>
			<?php } ?>

			</tbody>
		</table>

		<?php $opt_cats = array("meat", "veggie", "cheese", "sauce", "condiment");
			$opt_select = "<select>";
			foreach($opt_cats as $oc) {
				$opt_select .= sprintf("<option>%s</option>", $oc);
			}
		   $opt_select .= "</select>";

		?>
		<table role="grid">
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
			<?php foreach($opts as $opt) {?>
				<tr>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['title'];?></a></td>
					<td><a href="#" route=""><?php echo $opt_select?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['pizza'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['burger'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['pita'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['subs'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['donair'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['nacho'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['poutines'];?></a></td>
					<td><a href="#" route=""><?php echo $opt['Orbopt']['fingers'];?></a></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php }?>
<div class="row">
	<div id="upload-menu-form-container" class="large-8 large-centered columns">
<?php if ( $this->Session->read('Upload') ) {?>
		<h3 class="success">Upload Successful — Menu Updated</h3>
<?php } ?>
	<h4>Upload Xtreme Menu Options & Xtreme Menu Documents</h4>
	<?php echo $this->Form->create( 'menu_upload', array( 'type' => 'file' ) );
	echo $this->Form->input( 'menu', array( 'type' => 'file', 'label' => 'Xtreme Menu' ) );
	echo $this->Form->input( 'opts', array( 'type' => 'file', 'label' => 'Xtreme Menu Options' ) );
	echo $this->Form->end( 'Submit' );
?>
	</div>
</div>
