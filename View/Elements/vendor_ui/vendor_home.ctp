<?php
/**
 * J. Mulle, for app, 5/20/15 4:16 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
$buttons = [];

$sv_labels = [
	STORE_OPEN => ['open', 'closed'],
	DEBIT_AVAILABLE => ['available', 'unavailable'],
	CREDIT_AVAILABLE => ['available', 'unavailable'],
	DELIVERY_AVAILABLE => ['available', 'unavailable'],
	ONLINE_ORDERING => ['available', 'unavailable'] ];
//$closing_time = new DateTime($closing);
$now = new DateTime('now');
?>
<div id="vendor-home-tab">
	<div class="row">
		<div class="large-12 columns text-center">
			<?php echo $this->Html->Image('splash/logo.png', array('id' => 'vendor-home-logo'));?>
			<h3>Menu & Store Activity Manager</h3>
			<h4><?php echo $now->format("l, F jS, H:iA");?></h4>
		</div>
	</div>
<?php
	$new_row = false;
	$sys_vars = array_slice($system, 0, 5);
	array_push($sys_vars, $system[11]); # online-ordering added late
	foreach ( $sys_vars as $sysvar):
		$sv = $sysvar['Sysvar'];
		if ($sv['id'] == POS_AVAILABLE) continue;
		$new_row = !$new_row;
			$left  = [$sv['name'], "modal-button",  "full-width", "left"];
            $right = [$sv['name'], "modal-button", "full-width", "right"];?>
	<?=$new_row ? "<div class='row'>" : "";?>
		<div class="large-6 columns">
			<div class="row">
				<div class="large-12 columns text-center">
					<h2><?=strtoupper(str_replace("_", " ",$sv['name']));?></h2>
				</div>
			</div>
			<div class="row">
				<div class="large-12 columns">
					<ul class="activizing">
						<li class="sysvar  <?=$sv['status'] ? "active" : "inactive";?>" data-route="system/<?=$sv['id'];?>/1/1">
							<a href="#" <?=___cD($right);?> >
								<span class='text'><?=$sv_labels[$sv['id']][0];?></span>
							</a>
						</li>
					<li class="sysvar <?=$sv['status'] ? "inactive" : "active";?>" data-route="system/<?=$sv['id'];?>/1/0">
						<a id='store-status-closed' href="#" <?=___cD($left);?> >
							<span class='text'><?=$sv_labels[$sv['id']][1];?></span>
						</a>
					</li>
				</div>
			</div>
		</div>
	<?=!$new_row ? "</div>" : "";?>
	<?php endforeach; ?>
	<div class="row">
		<div class="large-12 columns">
			<P>Store will automatically be set to 'OPEN' or 'CLOSED' according to the normal store hours. Delivery will automatically end 30 minutes before close. Opening or closing the store early/late will persist until the next scheduled open or close (ie. if you close the store at 5pm, it will remain closed until the next automatic opening; if you open the store at 4am to be open late, you will have to manually close it or it will remain open until the next scheduled closing time).</p>
		</div>
	</div>
</div>