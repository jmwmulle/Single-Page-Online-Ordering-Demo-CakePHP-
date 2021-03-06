<?=$this->Element('primary_modal/masthead',$masthead);?>
<div id="order-confirmation-spinner" class="row">
	<div id="load-dot-box" class="large-10 large-centered columns">
		<div class="row">
			<div class="large-12 columns text-left">
				<?php if ($status == "relaunching"):?>
					<h5 class="warning">Well Now <em>That's</em> Interesting</h5>
					<p id="#relaunch-warning" class="warning">
					Our pizza-bot thinks you've just placed an order. Since (of course) you wouldn't hit refresh before we'd replied,
					we'll just assume that the cat jumped on the keyboard. And don't worry, a second order
					hasn't been placed&mdash;we've got everything under control.</p>
				<?php endif; ?>
				<?php if ($status != "confirmed" and $status != "timeout"): ?>
					<h5 class="text-center">Just letting the Xtreme Team know about your order.</h5>
					<p>A real, live human is making sure this order can be fulfilled. In just a moment
						we'll let you know if you're good to go, plus you'll receive an e-mail as further confirmation.
						So please don't close your browser<?=$status == "relaunching" ? " (...again...)":"";?>!</p>
					<p class="information">And just so you know, if the store can't be reached within <strong>90 seconds</strong>,
						we'll let you know about that, too.</p>
					<h4 id="order-confirmation-thanks" class="friendly text-center">
						<span class="friendly-1">Thanks </span>
						<span class="friendly-2">so much!</span>
						<span class="friendly-3"> We'll </span>
						<span class="friendly-4">get back to you </span>
						<span class="friendly-5">shortly!</span>
					</h4>
				<?php endif;?>
				<?php if ($status == "confirmed"):?>
					<h5 class="text-center">Your Order Has Been Accepted! Yay!</h5>
					<?php if ($time[0] == DELIVERY):?>
						<p>We're currently expecting delivery to take about <span id="order-ready-time"><?=$time[1];?></span> minutes.</p>
					<?php else:?>
						<p>We're currently expecting your order to be ready for pick-up in about <span id="order-ready-time"><?=$time[1];?></span> minutes.</p>
					<?php endif;?>
					<a href="#" class="modal-button full-width success" data-route="menu/unstash">
						</span><span class="text">Awesome!</span><span class="icon-circle-arrow-r">
					</a>
				<?php endif; ?>
				<?php if ($status == "timeout"):?>
					<h5 class="warning">Oh No! We Can't Reach The Team!</h5>
					<p>Sorry to report this, but our effort to contact the store has taken longer than expected. You'll just have to do this the old fashion way, so sorry! Give us a call at <strong>902-404-1600</strong></p>
					<p>Just so you know, we've cleared your cart to prevent the order from accidentally happening later.</p>
					<a href="#" class="modal-button full-width cancel" data-route="menu/unstash">
						<span class="icon-cancel"></span><span class="text">Understood</span>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php if ($status != "confirmed" and $status != "timeout"): ?>
		<div class="row">
			<div class="large-12 text-center columns">
				<div id="load-dot-1" class="modal-load-dot"></div>
				<div id="load-dot-2" class="modal-load-dot"></div>
				<div id="load-dot-3" class="modal-load-dot"></div>
				<div id="load-dot-4" class="modal-load-dot"></div>
			</div>
		</div>
		<?php endif;?>
		<?php if ($status == "relaunching" ): ?>
		<p class="information">If, errr, you <em>haven't</em> placed an order in the last 90 seconds and believe you're seeing this in
		error, we'd <em>really</em> appreciate a phone call (902-404-1600) to let us know so we don't make food you
		haven't ordered!</p>
		<?php endif; ?>

	</div>
</div>