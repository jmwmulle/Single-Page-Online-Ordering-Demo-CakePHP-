<?php
/**
 * J. Mulle, for app, 2/2/15 5:49 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

?>
<div class="row">
	<div class="large-12 columns">
		<table role="grid">
			<thead>
				<tr>
					<th>Name</th>
					<th>PriceRank1</th>
					<th>PriceRank2</th>
					<th>PriceRank3</th>
					<th>PriceRank4</th>
					<th>PriceRank5</th>
					<th>Category</th>
					<th>SubCategory</th>
					<th>PriceLabel1</th>
					<th>PriceLabel2</th>
					<th>PriceLabel3</th>
					<th>PriceLabel4</th>
					<th>PriceLabel5</th>
					<th>Description</th>
					<th>Configure</th>
					<th>burger</th>
					<th>salad</th>
					<th>pizza</th>
					<th>pita</th>
					<th>subs</th>
					<th>donair</th>
					<th>nacho</th>
					<th>poutines</th>
					<th>fingers</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

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
