<?php
	/**
	 * J. Mulle, for app, 9/3/14 9:39 PM
	 * www.introspectacle.net
	 * Email: this.impetus@gmail.com
	 * Twitter: @thisimpetus
	 * About.me: about.me/thisimpetus
	 */

?>

<div class="row">
	<?php AppController::anchor($orb['title']);?>
	<div class="large-4 small-6 columns thumb">
		<?php echo $this->Html->image('nothumb.png', array('alt' => ucwords($orb['title'])." menu photo", "class" => "orb-card-thumb"));?>
	</div>
	<div class="large-8 small-6 columns">
		<h2 class="orb-card"><?php echo $orb[ 'title' ]; ?><span class='subheader'><?php echo $orb[ 'subtitle' ]; ?></span></h2>
	</div>
</div>
<div class="row">
	<p class="large-12 small-12 columns"><?php echo $orb[ 'description' ]; ?></p>
</div>
<div class="row">
	<div class="large-12 small-12 columns price-table">
		<h4 class="orb-card">Price</h4>
		<dl>
			<?php foreach ( $orb[ 'price_matrix' ] as $opt => $price ) { ?>
				<dt><?php echo ucwords( $opt ); ?></dt>
				<dd><?php echo ucwords( $price ); ?></dd>
			<?php } ?>
		</dl>
	</div>
</div>