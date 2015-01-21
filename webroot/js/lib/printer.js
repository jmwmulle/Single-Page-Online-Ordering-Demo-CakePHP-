/**
 * Created by jono on 1/20/15.
 */
function accept_order (order) {
	print_simple(order.address);
	print_simple('Delivery Instructions: '+order.delivery_instructions+'\n');
	print_simple('Time Ordered: '+order.time+'\n');
	print_simple('Total: $'+order.price+'\n');
	print_simple('Ordered for: '+order.order_method+'\n');
	print_simple('Paying with: '+order.payment_method+'\n');
	if (order.paid) {
		print_simple('Paid: Yes\n');
	} else {
		print_simple('Paid: No\n');
	}
	print_items(order.food);


}

//String message, String title
function show_dialog(message, title) {
	Android.showDialog(message, title);
}
//String text, int font_id, String alignment, int line_space, int size_w, int size_h, int x_pos, boolean bold, boolean underline
function print_text(text, font_id, alignment, line_space, size_w, size_h, x_pos, bold, underline) {
	Android.printText(text, font_id, alignment, line_space, size_w, size_h, x_pos, bold, underline);
}

function print_simple(text) {
	print_text(text, 1, 'left', 1, 1, 1, 1, false, false);
}

function print_title(text) {
	print_text(text, 1, 'left', 1, 2, 2, 1, true, false);
}

//boolean feed
function cut(feed) {
	Android.cut(feed);
}

function print_items(items) {
	for (var name in items) {
		print_item(name, items[name]);
	}
}

function print_item(name, item) {
	print_simple(item.quantity+'x '+name+'\n');
	print_simple('$'+item.price+'\n');
	for (var topping in toppings) {
		print_simple('\t' + topping.title + ' ' + topping.weight +'\n');
	}
	print_simple(item.instructions+'\n');
}

