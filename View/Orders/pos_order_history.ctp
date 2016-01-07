<?php
/**
 * J. Mulle, for app, 12/21/15 7:20 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

	?>
<div class="row">
	<div class="large-12 columns content-box">
		<br />
		<table class="text-center">
			<thead>
				<tr>
					<th>Time</th>
					<th>Accepted</th>
					<th>Customer</th>
					<th>Total</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>

			<?php foreach ($orders as $i => $order) {
				$o = $order['Order'];
				$id = $o['id'];
				$o['detail'] = json_decode($o['detail'], true);
				unset($o['detail']['Debug']);
				$u = $o['detail']['Service'];
				$address = $u['address'];
				$time = new DateTime($order['Order']['created']);
				$uid = $o['detail']['uid'];

			?>
				<tr id="<?=$uid?>-details">
					<td><?=$time->format("g:i A");?></td>
					<td><span class="icon-<?=$o['state'] ? "check-mark icon-green" : "cancel icon-red";?>"></span></td>
					<td>
						<ul>
							<li><?=sprintf("%s %s", $address['firstname'], $address['lastname']);?></li>
							<?=$address['address'] ? sprintf("<li>%s</li>", $address['address']) : null;?>
							<?=$address['address_2'] ? sprintf("<li>%s</li>", $address['address_2']) : null;?>
							<li><?=sprintf("(%s)%s-%s", substr($address['phone'],0,3), substr($address['phone'],3,3), substr($address['phone'],6,4));?></li>
						</ul>
					</td>
					<td>
						<?="$".$o['detail']['Invoice']['total'];?>
					</td>
					<td>
					<a href="#" class="modal-button pos-button" data-pressroute="order_history/reprint/<?=$id;?>">
						<span>Reprint</span>
					</a>
				</tr>


			<?php } ?>
			</tbody>
		</table>
		<a href="#" class="modal-button lrg full-width cancel bottom-anchored bottom-rounded" data-route="order_history/hide">
			<span class="icon-cancel"></span><span class="text">DONE</span>
		</a>

	</div>
</div>

