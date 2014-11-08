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
		<button class="large expand unavailable">&nbsp;</button>
                <?php echo $this->Form->create(NULL, array('url' => array('controller' => 'shop', 'action' => 'add'))); ?>
		<?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $orb['id'])); ?>

		<?php echo $this->Form->button('Add to Cart', array('class' => 'btn btn-primary addtocart', 'id' => 'addtocart', 'id' => $orb['id']));?>
		<?php echo $this->Form->end(); ?> 
	</div>
	<div class="large-8 small-6 columns">
		<div class="row">
			<div class="large-12 small-12 columns">
				<h4 class="orb-card">
					<?php echo $orb[ 'title' ]; ?>
					<span class='subheader'><?php echo $orb[ 'subtitle' ]; ?></span>
				</h4>
				<p><?php echo $orb[ 'description' ]; ?></p>
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
			</div>
		</div>
	</div>
</div>
