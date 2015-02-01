/**
 * Created by jono on 1/20/15.
 */
var allow_tab_out = false;

function tab_out(output, label, error_ob) {
	if (!allow_tab_out) return;
	try {
		if (output === null) output = "null";
		if (output === true) output = "true";
		if (output === false) output = "false";
		if (error_ob) {
			var error = {e_txt:output.message, e_stack:output.stack};
			output = sprintf("<pre>%s</pre>", JSON.stringify( error, null, "\t"));
		}
		if (typeof(output) != "string") output = sprintf("<pre>%s</pre>", (JSON.stringify(output, null, "\t")));
		var line = $("<div/>").addClass('jsc-line');
		if (!label) label = "----";
		$(line).append($("<div/>").addClass('tout-label').html(label));
		$(line).append($("<div/>").addClass('tout-text').html(output));
		$("main#js-console").append(line);
	} catch (e) {
		$('body').style({backgroundColor: 'yellow'});
		setTimeout(function() {
			$('body').css({backgroundColor: 'yellow'});
			$("main").hide();
			$("body").append($("<div/>").css({marginTop:'80px'}).html(e.message));
		}, 250);
	}
}

function tout_show(called_from) {
	if (!allow_tab_out) return;
	called_from = called_from ? called_from : "anon"
	$("h4#called-from").html(called_from);
	$("main").hide();
	setTimeout(function() {
	$("main#js-console").show();
		setTimeout(function() {
			$("main#js-console").css({position:'fixed', top:'80px', left:'0px', zIndex:9999999});
		}, 100);
	}, 50);
}


function XtremePrinter() {
	this.printer_available = false;
	this.ip = null;
	this.status = null;
	this.queue = {};
	this.styles = {}
	this.tab_out = function(text, label) { tab_out(text, label); };
	this.tout_show = function() {tout_show();};
	this.style_template = function() {
		return {
			indent:0,
			scale:1,
			line_h:1,
			align: C.LEFT,
			bold:false,
			underline: false
		}
	}
	this.virtual_receipts = [];

	this.init = function(ip) {
		this.status = this.open_printer(ip);
		this.add_style('default', 0, 'left', 3, 1.5, false, false);
		this.extend_style('center', 'default', {align:'center'});
		this.extend_style('right', 'default', {align:'right'});
		this.extend_style('orb_opt', 'default', {indent:2, scale:2});

		// headers
		this.add_style('h1', 0, 'center', 7.5, 5, true, false);
		this.extend_style('h2', 'h1', {scale:4, line_h:6});
		this.extend_style('h3', 'h1', {scale:3, line_h:4.5});
		this.extend_style('h4', 'h1', {scale:2, line_h:3});
		this.extend_style('h5', 'h1', {scale:1, line_h:2});

	}

	this.dequeue = function() {
		var line;
		var queue = {}
		var count = 0;
		for (var key in this.queue) {
			if (count == 0) {
				line = this.queue[key];
			} else {
				queue[key-1] = this.queue[key];
			}
			count++;
		}
		this.queue = queue;
		return line;
	}

	this.print_from_queue = function() {
		var response = {success: true, error:false, line:null, queue_empty:false, raw:null};
		if ( this.queued() ) {
			var line = this.dequeue();
			var cut = in_array(line.text, [C.CUT, C.FEED_CUT]);
			if (line.text != C.CUT) {
				if ( in_array(line.text, C.FEED, C.FEED_CUT) ) line.text = " \n";
				var print_response = this.print(line.text, line.style, cut);
				if (print_response.success === false) {
					response.success = false;
					response.error = print_response;
					response.line = line;
				}
			}
			if (cut) this.cut(true);
		} else {
			this.cut(true);
			response.queue_empty = true;
		}
		return response;
	}

	this.queued = function() { return obj_len(this.queue) > 0;}

	this.open_printer = function(ip) {
		var status;
		if ( XBS.printer.is_xtreme_tablet() || true) {
			status = ip ? Android.openPrinter(ip) : Android.openDefaultPrinter();
			status = status.split(":");
			status = {success: parseInt(status[0]) === 1, error:status[1] == 'false' ? false : status[1]};
			this.printer_available = status.success ? true : false;
			this.ip = status.ip;
			this.status = status;
		} else {
			this.printer_available = false;
			this.ip = false;
			this.status = {ip:false, success:false};
			status = this.status;
		}
		return status;
	}

	this.play_order_tone = function() {
		return
		if ( XBS.printer.is_xtreme_tablet() ) {
			Android.playTone();
		} else {
			var audio = new Audio(XSM.vendor.new_order_tone);
			audio.play();
		}
	}

	this.feed_line = function(lines) {
		if (!lines && lines != 0) lines = 1;
		for (var i = 0; i < lines; i++) this.queue_line(" \n", 'default');
	}

	this.queue_line = function(line, style, feed) {
		if (!feed) feed = 0;
		var index = obj_len(this.queue);
		if (style) {
			this.queue[index] = {style:style, text:line};
		} else {
			this.queue[index] = {style:'default', text:line};
		}
		this.queue[index+1] = {style:'default', text:' '};
	}


	this.has_virtual_receipts = function() {
		if ( is_array(this.virtual_receipts) ) {
			return this.virtual_receipts.length > 0;
		} else {
			this.virtual_receipts = [];
			return false;
		}
	}

	/**
	 * queue_order()
	 *
	 * @param {obj} order
	 * @returns {void}
	 */
	this.queue_order = function(order) {
		pr(order, 'the order');
		tab_out("arrived", "queue_order()");
		try {
			this.queue_line(sprintf('Ordered At: %s', order.time), 'h4', 1);
			this.queue_line(sprintf("For: %s", order.order_method), 'h2', 1);
			if ( order.order_method == C.DELIVERY) {
				if (order.customer) this.queue_line(order.customer, 'center', 'h3');
				if (order.address) this.queue_line(order.address, 'center', 'h3');
				if (order.delivery_instructions ) this.queue_line(order.delivery_instructions, 'h4');
			}
			this.queue_line(sprintf("Payment Status: %s", order.paid ? "PAYED" : "OWED"), 'h3',1);
			this.queue_line(sprintf("Payment Type: %s", order.payment_method), 'h4');
			this.__queue_orb_list(order.food);
			this.queue_line(sprintf("TOTAL: $%s", order.price), 'h2');
		} catch(e) {
			// todo: handle this maybe?!
			tab_out(e, 'queue_order() error', true);
			return false;
		}
//		tout_show();
		pr(this.queue, 'the queue');
		return this.queue;
	};

	/**
	 * add_style()
	 * @param name
	 * @param indent
	 * @param scale
	 * @param line_space
	 * @param alignment
	 * @param bold
	 * @param underline
	 * @returns {boolean}
	 */
	this.add_style = function(name, indent, alignment, line_space, scale, bold, underline) {
		this.styles[name] = {
			align: alignment ? alignment : 'left',
			line_h: line_space ? line_space : 1,
			scale: scale ? scale : 1,
			indent: indent ? indent : 0,
			bold: bold ? bold :false,
			underline: underline ? underline : false
		}
		return true;
	}

	/**
	 * extend_style()
	 * @param {str} name
	 * @param {str} base
	 * @param {obj} ext
	 * @returns {boolean}
	 */
	this.extend_style = function(name, base, ext) {
		try {
			var style = this.style_template();
			for (var attr in style)  style[attr] = attr in ext ? ext[attr] : this.styles[base][attr];
			this.styles[name] = style
			return {name:name, style:style};
		} catch (e) {
			return e;
		}
	}

	/**
	 * show_dialog()
	 *
	 * @param {str} message
	 * @param {str} title
	 * @returns {void}
	 */
	this.show_dialog = function (message, title) { if (this.printer_available) Android.showDialog(message, title);}

	/**
	 *  print()
	 *
	 *  @param {str} text
	 *  @param {int} style
	 *  @returns {string}
	 */
	this.print = function (text, style, virtual_cut) {
		pr({text:text, style:style, virtual_cut:virtual_cut}, "XtremePrinter::print(text, style, virtual_cut)", 2);
		tab_out({text:text, style:style}, 'print()');
		var response = null;
		var s = XBS.printer.styles[style];
		try {
			if (this.printer_available) {
				response = Android.printText(text, 1, s.align, s.line_h, s.scale, s.scale, s.indent, s.bold, s.underline);
			} else {
				if (virtual_cut || this.virtual_receipts.length === 0) this.virtual_receipts.unshift([]);
				this.virtual_receipts[0].push(
					$("<li/>").addClass(["virtual-receipt", style]).html(text)
				);
				response = {success:true, error:false};
			}
		} catch (e) {
				tab_out(e, 'print error', true);
		}
		return response;
	}

	/**
	 * cut()
	 * @param {bool} feed
	 * @returns {void}
	 */
	this.cut = function (feed) { feed === true ? Android.cut(feed) : Android.cut(false); }

	/**
	 * __queue_orb_list()
	 * @param {obj} items
	 * @returns {string}
	 */
	this.__queue_orb_list = function (items) {
		try {
			for (var orb_name in items)  {
				var item = items[orb_name];
				try {
					this.queue_line(sprintf("$%s   (%s) x %s %s", item.price, item.quantity, item.size, orb_name), 'h4');
					for (var opt in item.opts) {
						this.queue_line(sprintf("%s: %s", item.opts[opt].weight, item.opts[opt].title), 'orb_opt');
						if (item.instructions) this.queue_line(item.instructions, 'opt_note');
					}
				} catch (e) {
					tab_out({
							e_txt: e.message,
							e_stack: sprintf("<pre>%s</pre>", JSON.stringify(e.stack, null, "\t"))
							}, "__format_orbs() error");
				}
			}
		} catch (e) {
			tab_out({
				e_txt: e.message,
				e_stack: sprintf("<pre>%s</pre>", JSON.stringify(e.stack, null, "\t"))
				}, "__queue_orb_list() error");
		}
		return true;
	}

	this.render_virtual_receipt = function() {
		pr(this.virtual_receipts, "XtremePrinter::render_virtual_receipt()", 2 );
		if (this.virtual_receipts.length) return;
		var receipts = $("<ul/>").attr('id', 'virtual-receipt').addClass('virtual-receipt-container');
		for (var i= 0; i < this.virtual_receipts.length; i++) {
			var receipt_wrapper = $("<li />");
			var receipt = $("<ul />").addClass('virtual-receipt receipt');
			for (var j = 0; j < this.virtual_receipts[i].length; j++) {
				$(receipt).append(this.virtual_receipts[i][j]);
			}
			$(receipt_wrapper).append(receipt);
			$(receipts).append(receipt_wrapper);
		}
		$(C.BODY).append(receipts);
		this.virtual_receipts = [];
		return true;
	}

	this.is_xtreme_tablet = function() { return navigator.userAgent == C.XTREME_TABLET_USER_AGENT }

	return this;
}


