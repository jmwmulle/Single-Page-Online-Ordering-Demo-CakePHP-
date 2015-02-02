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
