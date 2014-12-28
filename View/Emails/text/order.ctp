Shop Order:

First Name: <?php echo $cart['Order']['first_name'];?>

Last Name: <?php echo $cart['Order']['last_name'];?>

Email: <?php echo $cart['Order']['email'];?>

Phone: <?php echo $cart['Order']['phone'];?>


Billing Address: <?php echo $cart['Order']['billing_address'];?>

Billing Address 2: <?php echo $cart['Order']['billing_address_2'];?>

Billing City: <?php echo $cart['Order']['billing_city'];?>

Billing Province: <?php echo $cart['Order']['billing_province'];?>

Billing Postal Code: <?php echo $cart['Order']['billing_postal_code'];?>

Billing Country: <?php echo $cart['Order']['billing_country'];?>


Delivery Address: <?php echo $cart['Order']['delivery_address'];?>

Delivery Address 2: <?php echo $cart['Order']['delivery_address_2'];?>

Delivery City: <?php echo $cart['Order']['delivery_city'];?>

Delivery Zip: <?php echo $cart['Order']['delivery_postal_code'];?>




Size			Description			Quantity			Price
<?php foreach ($cart['OrderItem'] as $orderitem): ?>
<<?php echo $orderitem['size_name']; ?>			?php echo $orderitem['title']; ?>			<?php echo $orderitem['quantity']; ?>			$<?php echo $orderitem['subtotal']; ?>

<?php endforeach; ?>

Items:	<?php echo $cart['Order']['quantity'];?>

Total:	$<?php echo $cart['Order']['total'];?>


////////////////////////////////////////////////////////////
//
//<?php print_r($cart); ?>
//
//////////////////////////////////////////////////////////////
