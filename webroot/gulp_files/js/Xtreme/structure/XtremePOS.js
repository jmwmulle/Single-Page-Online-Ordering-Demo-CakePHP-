/**
 * Created by jono on 1/24/15.
 */
XtremePOS = function() {
	this.init();
	return this;
};

XtremePOS.prototype = {
	constructor: XtremePOS,
	last_tone_play: 0,
	init_list: ["buttons", "splash", "pending", "current"],
	icon_classes: { F:"", D:"icon-double", R:"icon-right-side", L:"icon-left-side"},
	update_interval: 3000,
	printer: undefined,
	ip: undefined,
	DOM: {
		current: {
			box: undefined,
			address: undefined,
			customer: undefined,
			food: undefined
		},
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
				var hide_time = self.current.hide();
				self.current.order = self.pending.next();
				if ( self.current.order ) {
					var route = ["pos_reply", self.current.order.id, C.ACCEPTED].join(C.DS);
					$(self.DOM.accept).data('route', route);
					$(self.DOM.current.address).html("").append( self.customer_details(self.current.order.Service.address) );
					$(self.DOM.current.food).html("").append( self.order_rows( self.current.order.Order) );
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
						if ( !(order.id in self.pending.orders) && order.id != self.current.order.id ) {
							self.pending.orders[order.id] = order;
						}
					}
					var count_update_delay = 0;
					if (update) count_update_delay = self.current.update();
					setTimeout( function() { self.pending.update_DOM() }, count_update_delay);
				} else {
					setTimeout(function () { self.splash.show() }, self.current.hide())
				}

				if (self.pending.fetch_count < 1) {
					self.pending.fetch_count++;
					setTimeout(function () {
						$(XT.router).trigger(C.ROUTE_REQUEST, {request: "pos_pending", trigger: {}})
					}, self.update_interval);
				}
			};

			self.pending.count = function() { return obj_len( self.pending.orders ) },

			self.pending.update_DOM = function() {
				var displayed = $(self.DOM.pending.box).data('count');
				var current = self.pending.count();
				current--;
				if ( current != displayed) {
					setTimeout(function () {
						if (current < 0)  {
							$(self.DOM.pending.box).data('count', current);
							$(self.DOM.pending.display).html(current);
						}
						return current > 0 ? self.pending.show() : 0;
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
		this.DOM.current.box = $(XSM.pos.order_content)[0];
		this.DOM.current.address = $(XSM.pos.order_address)[0];
		this.DOM.current.food = $(XSM.pos.order_food)[0];
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
		var food = $("<ul>").addClass("food");
		for (var r in rows) {
			r = rows[r];
			var opts = $("<ul>");
			for (var o in r.orbopts) {
				o = r.orbopts[o];
				$(opts).append(
					$("<li>").addClass("orb-opt").append([
						$("<span>").addClass("text").html(o.Orbopt.title),
						$("<span>").addClass(this.icon_classes[o.Orbopt.coverage])
			            ])
				)
			}
			$(food).append(
				$("<li>").append(
					$("<ul>").addClass('orb').append([
						$("<li>").append([
							$("<span>").addClass("quantity").html([r.pricing.quantity, "x&nbsp;"].join(" ")),
			                $("<span>").addClass("size").html( r.pricing.label.replace("in",'"') ),
			                $("<span>").addClass("title").html(r.orb.Orb.title)
			            ]),
					    $("<li>").addClass("orb-opt").append(opts)
					])
				)
			)
		}
		return food;
	},
	customer_details: function(address) {
		var address_arr = [];
		address_arr.push( [address.firstname, address.lastname].join(" "),
						  address.address,
						  address.address_2,
						  address.postal_code,
						  address.phone,
						  address.note);
		var address_el = $("<ul>")
		for (var i =0 ; i < address_arr.length; i++) {
			if (address_arr[i] == null) continue;
			$(address_el).append( $("<li>").html(address_arr[i]) );
		}
		return address_el;
	}
};