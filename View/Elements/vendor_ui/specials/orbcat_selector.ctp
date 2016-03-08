<div class="large-9 columns">
	<div id="orbcat-selector" class="breakout modal fade-out hidden">
		<h3>Category Selector</h3>
		<div class="input select">
			<div id="orbcat-selector-index" class="large-12 columns">
				<label for="orbcat-selector-index-select">Choose From Here</label>
				<select id="orbcat-selector-index-select" class="select">
					<option>--</option>
					<?php foreach ($data as $id => $oc):?>
						<option id='orbcat-selector-index-<?=$id;?>' value="<?=$id;?>"><?=$oc;?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="large-12 columns">
				<a href="#" class="cancel-button modal-button cancel bisecting left" data-route="specials_breakouts/cancel/orbcat/select">
					<span class="icon-circle-arrow-l"></span><span>Cancel</span>
				</a>
				<a href="#" class="save-button modal-button confirm bisecting right" data-route="specials_breakouts/save/orbcat/select">
					<span>Save</span><span class="icon-circle-arrow-r"></span>
				</a>
			</div>
		</div>
	</div>
</div>