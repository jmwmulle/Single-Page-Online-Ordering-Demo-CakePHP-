/**
 * Created by jono on 1/20/15.
 */
function XtremePrinter() {
	this.printer_available = null;
	this.ip = null;
	this.status = null;


	this.style_template = function() {
		return {
			font_id:null,
			alignment:null,
			line_space:null,
			width:null,
			height:null,
			indent:null,
			bold:false,
			underline: false
		}
	}

	this.virtual_receipt = function(receipt_lines) {
		var vr = $("<div/>").addClass('virtual-receipt');
		for (var i=0; i< receipt_lines.length; i++) $(vr).append(receipt_lines[i]);
		pr(vr, "XtremePrinter::virtual_receipt()", 2);
		$(C.BODY).append(vr);
		return true;
	}

	this.styles = {}

	this.init = function(ip) {
		if (ip) this.open_printer(ip);
	}


	this.open_printer = function(ip) {
		var status = ip ? Android.openPrinter(ip) : Android.openDefaultPrinter();
		this.printer_available = status.success === true;
		this.ip = status.ip;
		this.status = status;
	}

	this.play_order_tone = function() {
		return true;
//		tout(navigator.userAgent, "userAgent");
//		if ( navigator.userAgent === 'xtreme-pos-tablet') {
//			Android.playTone();
//		} else {
//			var audio = new Audio(XSM.vendor.new_order_tone);
//			audio.play();
//		}
	}

	/**
	 * print_accepted_order()
	 *
	 * @param {obj} order
	 * @returns {void}
	 */
	this.print_accepted_order = function(order) {
		if (this.printer_available) {
			var p = "";
		    p += order.address + '\n';
			p += 'Delivery Instructions: '+ order.delivery_instructions + '\n';
			p += 'Time Ordered: '+ order.time + '\n';
			p += 'Total: $' + order.price + '\n';
			p += 'Ordered for: ' + order.order_method + '\n';
			p += 'Paying with: ' + order.payment_method + '\n';
			var paid = order.paid ? 'Paid: Yes\n' : 'Paid: No\n'
			p += this.print_items(order.food);
		        this.print_simple(p);
		        this.cut(true);
		} else {
			var receipt_elements = [];
			for (key in order)  {
				if (key != "food" && key != "id") {
					receipt_elements.push($("<div/>").html(sprintf("%s: %s", title_case(sel_to_str(key)), order[key])));
				}
			}
			receipt_elements.push(this.print_items(order.food));
			//this.virtual_receipt(receipt_elements)
		}
		return true;
	};

	/**
	 * add_style()
	 * @param name
	 * @param font_id
	 * @param alignment
	 * @param line_space
	 * @param width
	 * @param height
	 * @param indent
	 * @param bold
	 * @param underline
	 * @returns {boolean}
	 */
	this.add_style = function(name, font_id, alignment, line_space, width, height, indent, bold, underline) {
		self.styles[name] = {
			font_id: font_id,
			alignment: alignment,
			line_space: line_space,
			width: width,
			height: height,
			indent: indent,
			bold: bold,
			underline: underline
		}
		return true;
	}


	/**
	 * extend_style()
	 *
	 * @param {str} name
	 * @param {str} parent_style
	 * @param {obj} variations
	 * @returns {boolean}
	 */
	
	this.extend_style = function(name, parent_style, variations) {
		try {
			self.styles[name] = this.style_template();
			for (var attr in self.styles[name]) {
				self.styles[name][attr] = attr in variations ? variations[attr] : self.styles[parent_style][attr];
			}
			return true;
		} catch (e) {
			pr(e);
			return false;
		}
	}
	/*

	/**
	 * show_dialog()
	 *
	 * @param {str} message
	 * @param {str} title
	 * @returns {void}
	 */
	this.show_dialog = function (message, title) { if (this.printer_available) Android.showDialog(message, title);}


	/**
	 *  print_text()
	 *
	 *  @param {str} text
	 *  @param {int} font_id
	 *  @param {str} alignment
	 *  @param {int} line_space
	 *  @param {int} size_w
	 *  @param {int} size_h
	 *  @param {int} x_pos
	 *  @param {bool} bold
	 *  @param {bool} underline
	 *  @returns {void}
	 */
	this.print_text = function (text, style) {
		var s = self.styles[style];
		if (this.printer_available) {
			Android.printText(text, s.font_id, s.alignment, s.line_space, s.width, s.height, s.indent, s.bold, s.underline);
		} else {
			return text;
		}
	}

	/**
	 * print_simple()
	 * @param {str} text
	 * @returns {void}
	 */
	this.print_simple = function(text) { this.print_text(text, 1, 'left', 1, 1, 1, 1, false, false); }

	/**
	 * print_title()
	 * @param {str} text
	 * @wraps print_simple()
	 * @returns {void}
	 */
	this.print_title = function (text) { this.print_text(text, 1, 'left', 1, 2, 2, 1, true, false); }


	/**
	 * cut()
	 * @param {bool} feed
	 * @returns {void}
	 */
	this.cut = function (feed) { if (this.printer_available) Android.cut(feed); }

	/**
	 * print_items()
	 * @param {obj} items
	 * @returns {string}
	 */
	this.print_items = function (items) {
		var ret;
		if (this.printer_available) {
			ret = "";
			for (var name in items) ret += this.print_item(name, items[name]);
		} else {
			ret = $("<ul/>").addClass('order-items');
			for (name in items) $(ret).append(this.print_item(name, items[name]));
		}
		return ret;
	}

	/**
	 * print_item()
	 * @param {str} name
	 * @param {obj} item
	 * @returns {string}
	 */
	this.print_item = function (name, item) {
		var debug_this = 1;
		if (debug_this > 0) pr({name:name, item:item}, "XtremePrinter::print_item(name, item)", 2);

		var ret;
		if (this.printer_available) {
			ret = "";
			ret += item.quantity+'x '+name+'\n';
			ret += '$' + item.price + '\n';
			for (var topping in item.toppings) {
				topping = item.toppings[topping];
				ret += '\t' + topping.title + ' ' + topping.weight +'\n';
			}
			ret += item.instructions + '\n';
		} else {
			var topping_str = "<span class='opt-name'>%s</span><span class='opt-weight'>(%s)</span>";
			ret = $("<li/>").addClass('orb');
			$("<span/>").addClass('quantity').html(sprintf("%sx", item.quantity)).appendTo(ret);
			$("<span/>").addClass('name').html(name).appendTo(ret);
			$("<span/>").addClass('price').html(sprintf("\$%s", item.price)).appendTo(ret);
			var opts = $("<ul/>").addClass('opt-list');
			for (var key in item.toppings) {
				$("<li/>").html(sprintf(topping_str, item.toppings[key].title, item.toppings[key].weight)).appendTo(opts);
			}
			$(ret).append(opts);
		}
		return ret;
	}

	this.init();
	return this;
}


