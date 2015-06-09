<?php

	if ( $this->get('response') ) {
		echo json_encode($response);
	} else {
	$opt_counts = array();
	for ($i = 0; $i<11; $i++) {
		$opt_counts[$i] = $i;
	}
?>
<div class="row">
	<div class="large-12 columns">
		<h1>Add A New Menu Item</h1>
		<p>Prices, default toppings & sizes can be set as usual from the normal menu-editing area. This menu simply adds a new menu item to that list.</p>
		<p class="note">Note: "Included Regular Toppings" & "Included Premium Toppings" aren't separate; "Included Premium Toppings" sets how many of the <kbd>Regular Toppings</kbd> can be <kbd>Premium Toppings</kbd> before the customer must pay extra.</p>
		<p class="note">Example: Choosing 4 x <kbd>Regular Toppings</kbd> and 3 x <kbd>Premium Toppings</kbd> does <strong>not</strong> mean the item price includes 8 toppings. Rather, it means that 4 toppings <em>total</em> are included in the price, and up to 3 of them may be <kbd>Premium Toppings</kbd>.</p>
		<div class="orbs form">
			<?php echo $this->Form->create('Orb'); ?>
				<div class="row">
					<div class="large-6 columns">
						<div class="row">
							<div class="large-12 columns">
								<?php echo $this->Form->input('title'); ?>
							</div>
						</div>
						<div class="row">
							<div class="large-12 columns">
								<?php echo $this->Form->input('description'); ?>
							</div>
						</div>
					</div>
					<div class="large-6 columns">
						<div class="row">
							<div class="large-12 columns">
								<?php echo $this->Form->input('Orbcat.id', array('type' => 'select', 'options' => $orbcats, 'label' => 'Category')); ?>
							</div>
						</div>
						<div class="row">
							<div class="large-12 columns">
								<?php echo $this->Form->input('opt_count', array('type' => 'select',
								                                           'options' => $opt_counts,
								                                           'label' => 'Included Regular Toppings')); ?>
							</div>
						</div>
						<div class="row">
							<div class="large-12 columns">
								<?php echo $this->Form->input('premium_count', array('type' => 'select',
								                                               'options' => $opt_counts,
								                                               'label' => 'Incldued Premium Toppings'
									)); ?>
							</div>
						</div>
					</div>
				</div>
				<?php echo $this->Form->input('pricedict_id', array('type'=> 'hidden', 'value' => -1)); ?>
				<?php echo $this->Form->input('pricelist_id', array('type'=> 'hidden', 'value' => -1)); ?>
				<?php echo $this->Form->input('config', array('type'=> 'hidden', 'value' => "")); ?>
			<a href="#" class="modal-button full-width med" data-route="orb/-1/add/save">
				<span class="text">Save & Reload</span>
			</a>
		<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<?php } ?>