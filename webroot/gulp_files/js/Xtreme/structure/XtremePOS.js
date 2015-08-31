/**
 * Created by jono on 1/24/15.
 */
XtremePOS = function() {
	this.init();
	return this;
};

XtremePOS.prototype = {
	constructor: XtremePOS,
	last_check: 0,
	last_tone_play: 0,
	init_list: ["buttons", "splash", "pending", "current"],
	icon_classes: { F:"", D:"icon-double", R:"icon-right-side", L:"icon-left-side"},
	update_interval: 3000,
	printer: undefined,
	ip: undefined,
	DOM: {
		current: undefined,
		box: undefined,
		accept: undefined,
		reject: undefined,
		next: undefined,
		splash: undefined,
		pending: {
			box: undefined,
			display: undefined
		}
	},
	buttons: {
		init: function(self) {
			self.buttons.pressed = function(button) { return $(self.DOM.buttons[defined(button) ? button : C.ACCEPT]).hasClass(FX.pressed) }
		},
		pressed: undefined
	},
	splash: {
		init: function(self) {
			self.splash.hide = function() {
				$(self.DOM.splash).addClass(FX.fade_out);
				setTimeout(function () { $(self.DOM.splash).addClass(FX.hidden); }, 300);
				return 310;
			};
			self.splash.show = function() {
				$(self.DOM.splash).removeClass(FX.hidden);
				setTimeout(function () { $(self.DOM.splash).removeClass(FX.fade_out); }, 10);
				return 300;
			}
		},
		hide: undefined,
		show: undefined
	},
	current: {
		order: false,
		init: function(self) {
			self.current.fetch = function () {
				if (self.pending.list()) return self.pending.orders[0];
				else throw e.NOTHING_PENDING
			};
			self.current.hide = function() {
				if ( !defined(self.DOM.current) ) return;
				var hide_class = self.pending.list() ? FX.fade_out : FX.slide_up;
				var other_class = hide_class == FX.fade_out ? FX.slide_up : FX.fade_out;
				if ($(self.DOM.current).hasClass(hide_class)) {
					return 0;
				} else {
					$(self.DOM.current).addClass(hide_class);
					if ( $(self.DOM.current).hasClass(other_class) ) $(self.current.fetch()).removeClass(other_class);
					return 300;
				}
			};
			self.current.show = function() {
				if ($(self.DOM.current).hasClass(FX.fade_out) || $(self.DOM.current).hasClass(FX.slide_up)) {
					$(self.DOM.current).removeClass([FX.fade_out, FX.slide_up].join(" "));
					return 300
				}
				return 0;
			};
			self.current.update = function() {
				pr("self.current.update");
				var hide_time = self.current.hide();
				self.current.order = self.pending.next();
				if ( self.current.order ) {
					pr(self.current.order);
					var route = ["pos_reply", self.current.order.id, C.ACCEPTED].join(C.DS);
					pr([route, self.DOM.accept]);
					$(self.DOM.accept).data('route', route);
					var new_order = self.order_template(self.current.order);
					$(self.DOM.current).replaceWith(new_order);
					$(self.DOM.splash).addClass(FX.slide_up);
					$(self.DOM.box).removeClass(FX.slide_up);
					setTimeout(function () { hide_time += self.current.show() }, hide_time);
				}
				return hide_time
			};
			self.current.resolve = function(data) {
				if (data.error) {
					pr("fuck fuck fuck")
					// confirm user has been notified
					// if can't get confirmation, take ordering off-line
					// else advance to next order; if it happens again, take ordering off-line
					return;
				}
				self.current.update();
			}
		},
		hide: undefined,
		show: undefined,
		update: undefined
	},
	pending: {
		fetch_count: 0,
		orders: {},
		init: function (self) {
			self.pending.list = function() { return self.pending.count() > 0 ? self.pending.orders : false };
			self.pending.fetch = function(orders) {
				if ( defined(orders) && orders.length > 0 ) {
					var update = self.pending.count() == 0;
					for(var i = 0; i < orders.length; i++) {
						var order = orders[i].Order.detail;
						order.id = orders[i].Order.id;
						if ( !(order.uid in self.pending.orders) && order.uid != self.current.order.uid ) {
							self.pending.orders[order.id] = order;
						}
					}
					var count_update_delay = 0;
					if (update) count_update_delay = self.current.update();
					setTimeout( function() { self.pending.update_DOM() }, count_update_delay);
				} else {
					setTimeout(function () { self.splash.show() }, self.current.hide())
				}

				if (self.pending.fetch_count < 10) {
					self.pending.fetch_count++;
					setTimeout(function () {
						$(XT.router).trigger(C.ROUTE_REQUEST, {request: "pos_pending", trigger: {}})
					}, self.update_interval);
				}
			};
			self.pending.count = function() { return obj_len( self.pending.orders ) },
			self.pending.update_DOM = function() {
				var displayed = $(self.DOM.pending.box).data('count');
				if (self.pending.count() - 1 != displayed) {
					setTimeout(function () {
						$(self.DOM.pending.box).data('count', displayed);
						$(self.DOM.pending.display).html(displayed);
						displayed = self.pending.count() - 1;
						return displayed > 1 ? self.pending.show() : 0;
					}, self.pending.hide());
				}
				return self;
			};
			self.pending.hide = function() {
				var target = self.pending.list() ? self.DOM.pending.display : self.DOM.pending.box;
				if ( $(target).hasClass(FX.fade_out) ) return 0;
				pr(target);
				$(target).addClass(FX.fade_out);
				return 150;
			};
			self.pending.show = function() {
				var display_wait = 0;
				if ( $(self.DOM.pending.box).hasClass(FX.fade_out) ) {
					$(self.DOM.pending.box).removeClass(FX.fade_out);
					display_wait = 150;
				}
				if ( $(self.DOM.pending.display).hasClass(FX.fade_out) ) {
					setTimeout(function () { $(self.DOM.pending.display).removeClass(FX.fade_out) }, display_wait);
					return display_wait + 150;
				}
				return display_wait;
			};
			self.pending.next = function() {
				if ( !self.pending.list() ) {
					setTimeout(function () { self.splash.show() }, self.current.hide());
					return false
				}
				var next = obj_pop(self.pending.orders);
				self.pending.update_DOM();
				return next;
			}
		},
		hide: undefined,
		show: undefined,
		list: undefined,
		fetch: undefined,
		next: undefined,
		update_DOM: undefined
	},
	init: function() {
		this.printer = new Printer();
		this.init_DOM();
		var self = this;
		if ( !this.printer.init( self.ip ) && !XT.development_mode ) {
			// handle this case;
		}
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		this.printer.build_styles();
		this.pending.fetch();
	},
	init_DOM: function() {
		this.DOM.box = $("#pos-ui")[0];
		this.DOM.accept = $(XSM.pos.order_accept_button)[0];
		this.DOM.next = $(XSM.pos.next_order)[0];
		this.DOM.current = $(XSM.pos.order_content_detail)[0];
		this.DOM.pending.box = $("#pending-orders-count")[0];
		this.DOM.pending.display = $("#order-count")[0];
		this.DOM.splash = $(XSM.pos.back_splash)[0];
		$(this.DOM.accept).on(C.VMOUSEDOWN, function() { $(this).addClass(FX.pressed) });
		$(this.DOM.accept).on(C.VMOUSEMOVE, function(e) {
			if ( !self.buttons.pressed(C.ACCEPT) ) return;
			if (success_stop < 95) return $(self.DOM.accept).css({ background: self.bg_string(e) });
			$(self.DOM.accept).attr("style", null);
			$(self.DOM.accept).addClass('launching');
			$(XT.router).trigger(C.ROUTE_REQUEST, {request:"pos_print", trigger:e});
		});
		$(this.DOM.accept).on([C.VMOUSEOUT, C.VMOUSEUP].join(" "), function(e) {
			$(this).removeClass(FX.pressed);
			$(self.DOM.accept).css({background: bg_string() });
		});

		return this
	},
	bg_gradient: function(e) {
		var pos = defined(e) ? e.pageX : 0;
		var success_stop = Math.round( 100 * (e.pageX - $(self.DOM.accept).offset().left) / 512);
		var success_color = "rgba(50,255,50,1)";
		var ready_color = "rgba(125,185,232,1)";
		var bg_str = "-webkit-linear-gradient(left, " + success_color + " " + success_stop + "%,";
		bg_str += ready_color + " " + (success_stop + 1) +"%," + ready_color + " 100%)";
		return bg_str;
	},
	order_rows: function(rows) {
		var food = [];
		for (var r in rows) {
			r = rows[r];
			var opts = [];
			for (var o in r.opts) {
				o = r.opts[o];
				opts.push(
					$("<li>").addClass("orb-opt").append([
						$("<span>").addClass("text").html(o.title),
						$("<span>").addClass(this.icon_classes[o.weight])
			            ])
				)
			}
			food.push(
				$("<li>").append(
					$("<ul>").addClass('orb').append([
						$("<li>").append([
							$("<span>").html("&#8226;"),
			                $("<span>").addClass("size").html( r.detail.size.replace("in",'"') ),
			                $("<span>").addClass("title").html(r.desc)
			            ]),
					    $("<li>").addClass("orb-opt").append(opts)
					])
				)
			)
		}
		return food;
	},
	order_template: function() {
		return $("<div>").attr("id", "order-content-detail").addClass('row').append([
			$("<div>").attr("id", "labels").addClass("small-3 columns").append([
				$("<span>").attr("id", "title").addClass("label").html("ADDRESS:"),
				$("<span>").attr("id", "customer").addClass("label").html("CUSTOMER:"),
				$("<span>").attr("id", "food").addClass("label").html("ORDER:")
			]),
			$("<div>").attr("id", "values").addClass("small-9 columns").append([
				$("<span>").attr("id", "customer-name").addClass("value").html(this.current.order.address),
				$("<span>").attr("id", "order-title").addClass("value").html(this.current.order.customer),
				$("<ul>").attr("id", "food-list").addClass("value").append(this.order_rows(this.current.order.food))
			])
		]);
	},
};