/**
 * Created by jono on 1/20/15.
 */
XT.allow_tab_out = false;


Style =	function(printer, cfg) {
	this.__config = cfg;
	this.indent = 0;
	this.scale = 1;
	this.line_h = 1;
	this.align = C.LEFT;
	this.bold = false;
	this.underline = false;
	this.spawn = function(name, cfg) {
		var self = this;
		this.printer.styles[name] = new Style($.extend({}, cfg, self.__config)); return this;
	};
	this.init(printer, cfg);
	return this;
};

Style.prototype = {
	constructor: Style,
	printer: undefined,
	default_vals: {
		indent: 0,
		scale: 1,
		line_h: 1,
		align: C.LEFT,
		bold: false,
		underline: false
	},
	init: function(printer, cfg) {
		this.printer = printer;
		if ( defined(cfg) ) for (var key in cfg) this[key] = cfg[key]
	}
};

Printer = function() {
	this.printer_available = false;
	this.ip = null;
	this.status = null;
	this.queue = {};
	this.styles = {};

	this.virtual_receipts = [];

	return this
}

Printer.prototype = {
	constructor: Printer,
	tab_out: function (output, label, error_ob) {
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
	},
	tout_show: function tout_show(called_from) {
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
	},
	init: function(ip) {
		this.status = this.open(ip);
		//return this.status;
	},
	build_styles: function() {

		this.styles['default'] = new Style(this, {indent:0, scale: 3, line_h:1.75, align:'left', bold: false, underline: false});
		this.styles['center'] = new Style(this, {indent:0, scale: 3, line_h:1.75, align:'center', bold: false, underline: false});
		this.styles['right'] = new Style(this, {indent:0, scale: 3, line_h:1.75, align:'right', bold: false, underline: false});
		this.styles['medium'] = new Style(this, {indent:0, scale: 2.5, line_h:2.5, align:'medium', bold: false, underline: false});
		this.styles['small'] = new Style(this, {indent:0, scale: 2, line_h:3, align:'left', bold: false, underline: false});

		// headers
		this.styles['h1'] = new Style(this, {indent:0, scale: 5, line_h:7.5, align: "center", bold: false, underline: false});
		this.styles['h2'] = new Style(this, {indent:0, scale: 4, line_h:6, align: "center", bold: false, underline: false});
		this.styles['h3'] = new Style(this, {indent:0, scale: 3, line_h:4.5, align: "center", bold: false, underline: false});
		this.styles['h4'] = new Style(this, {indent:0, scale: 2, line_h:5, align: "center", bold: false, underline: false});
		this.styles['h5'] = new Style(this, {indent:0, scale: 1, line_h:2, align: "center", bold: false, underline: false});
		//this.styles['h1'].spawn('h2',  {scale:4, line_h:6})
		//				 .spawn('h3',  {scale:3, line_h:4.5})
		//				 .spawn('h4',  {scale:2, line_h:3})
		//				 .spawn('h5',  {scale:1, line_h:2});
	},

	dequeue: function() {
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
	},

	print_from_queue: function() {
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
	},

	queued: function() { return obj_len(this.queue) > 0 },

	open: function(ip) {
		var status;
		if ( this.is_xtreme_tablet() ) {
			status = defined(ip) ? Android.openPrinter(ip) : Android.openDefaultPrinter();
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
	},

	play_order_tone: function() { return this.is_xtreme_tablet() ? Android.playTone() : new Audio(XSM.vendor.new_order_tone).play(); },

	feed_line: function(lines) {
		if (!lines && lines != 0) lines = 1;
		for (var i = 0; i < lines; i++) this.queue_line(" \n", 'default');
	},

	queue_line: function(line, style, feed) {
		if (!feed) feed = 0;
		var index = obj_len(this.queue);
		if (style) {
			this.queue[index] = {style:style, text:line};
		} else {
			this.queue[index] = {style:'default', text:line};
		}
		this.queue[index+1] = {style:'default', text:' '};
	},


	has_virtual_receipts: function() {
		if ( is_array(this.virtual_receipts) ) {
			return this.virtual_receipts.length > 0;
		} else {
			this.virtual_receipts = [];
			return false;
		}
	},

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
	add_style: function(name, indent, alignment, line_space, scale, bold, underline) {
		this.styles[name] = {
			align: alignment ? alignment : 'left',
			line_h: line_space ? line_space : 1,
			scale: scale ? scale : 1,
			indent: indent ? indent : 0,
			bold: bold ? bold :false,
			underline: underline ? underline : false
		}
		return true;
	},

	/**
	 * show_dialog()
	 *
	 * @param {str} message
	 * @param {str} title
	 * @returns {void}
	 */
	show_dialog: function (message, title) { if (this.printer_available) Android.showDialog(message, title) },

	/**
	 *  print()
	 *
	 *  @param {str} text
	 *  @param {int} style
	 *  @returns {string}
	 */
	print: function (text, style, virtual_cut) {
		//pr({text:text, style:style, virtual_cut:virtual_cut}, "XtremePrinter::print(text, style, virtual_cut)", 2);
		var response = null;
		var s = this.styles[style];
		try {
			if (this.printer_available) {
				pr([text, 1, s.align, s.line_h, s.scale, s.scale, s.indent, s.bold, s.underline], "POS -> PRinter");
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
	},

	/**
	 * cut()
	 * @param {bool} feed
	 * @returns {void}
	 */
	cut: function (feed) { feed === true ? Android.cut(feed) : Android.cut(false); },


	render_virtual_receipt: function() {
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
	},

	is_xtreme_tablet: function() { return navigator.userAgent == C.XTREME_TABLET_USER_AGENT }
}


