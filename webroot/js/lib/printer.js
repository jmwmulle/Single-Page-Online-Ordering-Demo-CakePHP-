/**
 * Created by jono on 1/20/15.
 */
function accept_order (order) {

	var p = ""
    p += order.address + '\n';
	p += 'Delivery Instructions: '+ order.delivery_instructions + '\n';
	p += 'Time Ordered: '+ order.time + '\n';
	p += 'Total: $' + order.price + '\n';
	p += 'Ordered for: ' + order.order_method + '\n';
	p += 'Paying with: ' + order.payment_method + '\n';
	if (order.paid) {
		p += 'Paid: Yes\n';
	} else {
		p += 'Paid: No\n';
	}
	p += print_items(order.food);
        print_simple(p);
        cut(true);
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
	print_text(text, 1, 'left', 1, 3, 2, 1, false, false);
}

function print_title(text) {
	print_text(text, 1, 'left', 1, 2, 2, 1, true, false);
}

//boolean feed
function cut(feed) {
	Android.cut(feed);
}

function print_items(items) {
	var ret = "";
    for (var name in items) {
		ret += print_item(name, items[name]);
	}
        return ret;
}

function print_item(name, item) {
	var ret = "";
	ret += item.quantity+'x '+name+'\n';
	ret += '$'+item.price+'\n';
	for (var topping in item.toppings) {
		topping = item.toppings[topping];
		ret += '\t' + topping.title + ' ' + topping.weight +'\n';
	}
	ret += item.instructions + '\n';
	return ret;
}

