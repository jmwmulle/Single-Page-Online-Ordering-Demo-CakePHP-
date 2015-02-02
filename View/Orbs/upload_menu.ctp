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
<?php if ( $this->Session->read('Upload') ) {?>
		<h3>Upload Successful — Menu Updated</h3>
<?php } else { ?>
	<h3>Upload Xtreme Menu Options & Xtreme Menu Documents</h3>
	<?php echo $this->Form->create( 'menu_upload', array( 'type' => 'file' ) );
	echo $this->Form->input( 'opts', array( 'type' => 'file' ) );
	echo $this->Form->input( 'menu', array( 'type' => 'file' ) );
	echo $this->Form->end( 'Submit' );
}
?>
	</div>
</div>
