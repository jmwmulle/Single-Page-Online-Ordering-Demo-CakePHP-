<?=$this->Element('primary_modal/masthead',$masthead);?>
<div id="order-confirmation-spinner" class="row">
	<div id="load-dot-box" class="large-8 large-centered columns">
		<div class="row">
			<div class="large-12 columns text-left">
				<h5 class="text-center">Just letting the Xtreme Team know about your order.</h5>
				<p>This could take a minute; a real human being is making sure this order can be fulfilled. In just a moment
					we'll let you know if it's been accepted, and then you'll receive an e-mail as further confirmation.
					So please don't close your browser!</p>
				<p class="hint">(And just so you know, if something goes wrong and the store can't be reached within 90 seconds,
					this message will update to let you know.)</p>
				<h4 class="friendly text-center">
					<span class="friendly-1">Thanks </span>
					<span class="friendly-2">so much</span>
					<span class="friendly-3">for</span>
					<span class="friendly-4">your</span>
					<span class="friendly-5">patience!</span>
				</h4>
				<?php if ($status == "relaunching"):?>
				<p id="#relaunch-warning">
				Our pizza-bot seems to be under the impression you've just placed an order and were waiting for a reply.
				Perhaps your cat jumped on the keyboard, causing you to hit "refresh"? If so, don't worry, a second order
				hasn't been placed, we've got everything under control.</p>
				<p class="hint">(However, if you <em>haven't</em> placed an order in the last 90 seconds and believe you're seeing this in
				error, we'd really appreciate it if you'd call the store (902-404-1600) and let us know so we don't make food you
				haven't ordered!)</p>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 text-center columns">
				<div id="load-dot-1" class="modal-load-dot"></div>
				<div id="load-dot-2" class="modal-load-dot"></div>
				<div id="load-dot-3" class="modal-load-dot"></div>
				<div id="load-dot-4" class="modal-load-dot"></div>
			</div>
		</div>
	</div>
</div>