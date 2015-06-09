<?php
/**
 * J. Mulle, for app, 5/15/15 6:04 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
	if ( $this->get('response') ) {
		echo json_encode($response);
	} else {

?>

<div class="row">
	<div class="large-12 columns">
	<h1>Add Pricing Labels</h1>
	<p>Use this to create new groups of menu size/price labels, such as "small" "medium" & "large". Each group can have up to 5 labels, but only 1 is required; you can leave the remaining fields blank if they aren't needed.</p>
	<?php for ($i=1; $i<6; $i++) {?>
	<div class="dict-label-input">
		<form class="price-dict-update-form">
		<label>Size <?php echo $i; ?></label>
		<input type="text" name="Pricedict[l<?php echo $i;?>]">
		</form>
	</div>
	<?php }?>
	<br />
	<a href="#" class="modal-button full-width" data-route="update_menu/pricedicts/save">
		<span class="text">Save & Reload Page</span>
	</a>
</div>
<?php } ?>