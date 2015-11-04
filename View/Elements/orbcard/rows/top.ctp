<li id="orb-card-row-1" class="orb-card-row">
<?php if ($this->Session->read('Auth') ):?>
	<?='<div id="favorite" class="orb-card-button inline float-labeled disabled" data-route="orb_card/register" data-float-label="coming soon">'?>
			<span class="disabled icon icon-orb-card-favorite"></span>
		</div>
<?php else: ?>
	<?='<div id="register" class="orb-card-button inline float-labeled disabled" data-route="favorute/orb_card" data-float-label="coming soon">'?>
		<?='<span class="icon icon-orb-card-register"></span>'?>
	<?='</div>'?>
	<?='<div id="orb-card-register-panel" class="fade-out true-hidden orb-card-row-panel">'?>
		<?='<a id="orb-card-register-twitter" href="http://development-xtreme-pizza.ca/auth/twitter">'?>
			<?='<span class="icon-twitter"></span>'?>
		<?='</a>'?>
		<?='<a id="orb-card-register-facebook" href="http://development-xtreme-pizza.ca/auth/facebook">'?>
			<?='<span class="icon-facebook"></span>'?>
		<?='</a>'?>
		<?='<a id="orb-card-register-gplus" href="http://development-xtreme-pizza.ca/auth/google">'?>
			<?='<span class="icon-gplus"></span>'?>
		<?='</a>'?>
		<?='<a id="orb-card-register-email" href="#" data-route="register/orb_card/email/">'?>
			<?='<span class="icon-topbar-email"></span>'?>
		<?='</a>'?>
	<?='</div>'?>
<?php endif;?>
	<div id="description" class="orb-card-content inline">
		<div class="orb-card-content-box<?=$ajax ? " fade-out" : "";?>">
			<p><?=$orb[ "description" ]; ?></p>
			<h4 id="hidden-description"><?=strtoupper($orb[ "title" ]); ?></h4>
		</div>
	</div>
</li>