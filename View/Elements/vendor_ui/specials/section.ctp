<?php
	$section_name = $type == "features" ? "-$section" : "-conditions-$section";
	$data = ['changeroute' => "specials_selects/select/$type/$section"];
	$span_classes = ['config-label'];
	$select_classes = [];
	if ($type == "conditions") array_push($select_classes, "specials-add-condition");

?>
<!--- SPECIALS SET-<?=$section;?>--->
<div class="row">
	<div class="large-12 columns specials-section">
		<div id=""add-special<?=$section_name;?>-config-label" class="large-3 columns">
			<div class="input select">
				<label>&nbsp</label>
				<span <?=___cD($span_classes);?>><?=$label;?><?=$label != "" ? ":" : "";?></span>
			</div>
		</div>
		<div id="add-special<?=$section_name;?>" class="large-9 columns">
			<div id="add-special<?=$section_name;?>-wrapper" class="input select">
				<label for="add-special<?=$section_name;?>-select">&nbsp;</label>
				<select id="add-special<?=$section_name;?>-select" <?=___cD($select_classes);?> <?=___dA($data);?>>
				<option data-breakout="0">--</option>
				<?php
					foreach ($options as $o) echo sprintf("<option value='%s' data-breakout='%s'>%s</option>", $o[0], $o[1], $o[2]);
				?>
				</select>
			</div>
			<div id="add-special<?=$section_name;?>-choice" class="select hidden fade-out">
				<label>&nbsp;</label>
				<span class="select-choice" data-route="specials_selects/reset/<?=$type?>/<?=$section;?>"></span>
			</div>
		</div>
	</div>
</div>