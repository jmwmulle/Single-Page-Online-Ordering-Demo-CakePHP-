/**
 * Created by jono on 1/24/15.
 */
var accept_messages = ["Challenge accepted.", "No stomach unfed.", "Get ready to be XTREMELY fed.", "Yes ma'am!", "Yes sir!",
                       "I can refuse no appetite.", "Heed the call of hunger, mighty chef!"];
XtremePOS = function() {
	this.init();
	return this;
};

XtremePOS.prototype = {
	constructor: XtremePOS,
	last_tone_play: 0,
	init_list: ["buttons", "splash", "pending", "current", "delivery_times"],
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
		},
		delivery_times: {
			box: undefined,
			button:undefined
		},
		pos_hero: {
			box: undefined,
			message: {
				box: undefined,
				text: undefined
			}
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
			self.current.hide = function(slide) {
				var fade_time = $(self.DOM.box).hasClass(FX.fade_out) ? 0 : 300;
				var slide_time = $(self.DOM.box).hasClass(FX.slide_up) || !slide ? 0 : 300;
				$(self.DOM.box).addClass( FX.fade_out);
				if (slide) setTimeout( function() { $(self.DOM.box).addClass( FX.slide_up ) }, slide_time);
				return slide_time + fade_time;
			};
			self.current.show = function() {
				var slide_time = $(self.DOM.box).hasClass(FX.slide_up) ? 300 : 0;
				var fade_time = $(self.DOM.box).hasClass(FX.fade_out) ? 300 : 0;
				setTimeout( function() { $(self.DOM.box).removeClass( FX.slide_up ) }, slide_time);
				setTimeout( function() { $(self.DOM.box).removeClass( FX.fade_out) }, fade_time);
				return slide_time + fade_time;
			};
			self.current.update = function() {
				var hide_time = self.splash.hide();
				setTimeout( function() { hide_time += self.current.hide() }, hide_time);
				self.current.order = self.pending.next();
				if ( self.current.order ) {
					var route = ["pos_reply", self.current.order.id, C.ACCEPTED].join(C.DS);
					$(self.DOM.accept).data('route', route);
					$(self.DOM.current.address).html("").append( self.customer_details(self.current.order.Service) );
					$(self.DOM.current.food).html("").append( self.order_rows( self.current.order.Order) );
					self.update_order_method();
					setTimeout(function () { hide_time += self.current.show() }, hide_time);
				} else {
					self.current.hide();
				}
				return hide_time
			};
			self.current.resolve = function(data) {
				if (data.error) {
					// confirm user has been notified
					// if can't get confirmation, take ordering off-line
					// else advance to next order; if it happens again, take ordering off-line
					return;
				}
				$(self.DOM.pos_hero.message.text).html(accept_messages[ ranged_random(0, accept_messages.length -1) ]);
				var start_size = 10;
				$(self.DOM.pos_hero.message.text).css({
					fontSize: start_size,
					lineHeight: start_size + "px"
				});
				while($(self.DOM.pos_hero.message.text).height() < 70) {
					$(self.DOM.pos_hero.message.text).css({
									fontSize: start_size,
									lineHeight: start_size + "px"
								});
				  start_size++;
				}
				start_size--;
				$(self.DOM.pos_hero.message.text).css("font-size", start_size)
				$(self.DOM.pos_hero.box).removeClass(FX.slide_right);
				setTimeout(function() { $(self.DOM.pos_hero.message.box).removeClass(FX.fade_out) }, 300);
				setTimeout(function() { $(self.DOM.pos_hero.box).addClass(FX.slide_right) }, 1000);
				setTimeout(function() { $(self.DOM.pos_hero.message.box).addClass(FX.fade_out) }, 1300);
				self.current.receipt_lines();
//				setTimeout(function() { self.current.update() }, 1300);
			};
			self.current.receipt_lines = function() {
				pr(self.current.order);
				var o = self.current.order;
				var s = self.current.order.Service;
				var a = self.current.order.Service.address;
				var f = self.current.order.Order; //ie. [f]ood
				var r = [];
				r.push([s.order_method, "h1", true]);
				r.push([a.address_1, "h3", true]);
				if (a.address_2) r.push([a.address_2, "h3", true]);
				r.push([s.order_method, "h1", true]);
				r.push([s.order_method, "h1", true]);
				r.push([s.order_method, "h1", true]);
				r.push([s.order_method, "h1", true]);
				r.push([s.order_method, "h1", true]);
				r += a.address_1;
				if (a.address_2) r += a.address_2;

				pr(r, "receipt");
			}
		},
		hide: undefined,
		show: undefined,
		update: undefined,
		receipt_lines: undefined
	},
	pending: {
		fetch_count: 0,
		orders: {},
		init: function (self) {
			self.pending.list = function() { return self.pending.count() > 0 ? self.pending.orders : false };

			self.pending.fetch = function(orders) {
				if ( self.pending.fetch_count > 1) return;
				self.pending.fetch_count++;
				if ( defined(orders) && orders.length > 0 ) {
					var update = self.pending.count() == 0 && !self.current.order;
					self.pending.update_list(orders);
					var count_update_delay = 0;
					if (update) count_update_delay = self.current.update();
					setTimeout( function() { self.pending.update_DOM() }, count_update_delay);
				} else {
					setTimeout(function () { self.splash.show() }, self.current.hide())
				}

				setTimeout(function () { $(XT.router).trigger(C.ROUTE_REQUEST, {request: "pos_pending", trigger: {}}) }, self.update_interval);
			};

			self.pending.update_list = function(orders) {
				for(var i = 0; i < orders.length; i++) {
					var order = orders[i].Order.detail;
					order.id = orders[i].Order.id;
					if ( !(order.id in self.pending.orders) && order.id != self.current.order.id ) self.pending.orders[order.id] = order;
				}
			};

			self.pending.count = function() { return obj_len( self.pending.orders ) },

			self.pending.update_DOM = function() {
				var displayed = $(self.DOM.pending.box).data('count');
				var current = self.pending.count();
				current;
				if ( current != displayed) {
					setTimeout(function () {
						if (current > 0)  {
							$(self.DOM.pending.box).data('count', current);
							$(self.DOM.pending.display).html(current);
						}
						return current > 0 ? self.pending.show() : 0;
					}, self.pending.hide(current == 0));
				}
				return self;
			};

			self.pending.hide = function(hide_box) {
				var duration = 0;
				if ( !$(self.DOM.pending.display).hasClass(FX.fade_out) ) {
					$(self.DOM.pending.display).addClass(FX.fade_out);
					duration += 150;
				}
				if (hide_box && !$(self.DOM.pending.box).hasClass(FX.fade_out)) {
					$(self.DOM.pending.box).addClass(FX.fade_out);
					duration += 150;
				}
				return duration;
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
		update_DOM: undefined,
		update_list: undefined
	},
	delivery_times: {
		init: function(self) {
			self.delivery_times.show = function() {
				$(self.DOM.delivery_times.button).addClass(FX.slide_right);
				$(self.DOM.delivery_times.box).removeClass(FX.hidden);
				setTimeout(function() { $(self.DOM.delivery_times.box).removeClass(FX.fade_out); }, 150);
			};
			self.delivery_times.hide = function() {
				$(self.DOM.delivery_times.box).addClass(FX.fade_out);
				setTimeout(function() { $(self.DOM.delivery_times.box).addClass(FX.hidden); }, 300);
				setTimeout(function() { $(self.DOM.delivery_times.button).removeClass(FX.slide_right); }, 450);
			};
		},
		hide: undefined,
		show: undefined
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
		this.DOM.order_method = $("h2.order-method")[0];
		this.DOM.delivery_times.box = $("#delivery-times")[0];
		this.DOM.delivery_times.button = $("#delivery-time-button")[0];
		this.DOM.pos_hero.box = $("#accept-acknowledge")[0];
		this.DOM.pos_hero.message.box = $("#message")[0];
		this.DOM.pos_hero.message.text = $("#message span")[0];
		$(this.DOM.accept).on(C.VMOUSEDOWN, function() { $(this).addClass(FX.pressed) });
		var self = this;
		$(XSM.pos.order_accept_button+".pressed").on(C.VMOUSEMOVE, function(e) {
		   pr(e);
			if ( !self.buttons.pressed(C.ACCEPT) ) return;
			if (success_stop < 95) return $(self.DOM.accept).css({ background: self.bg_string(e) });
			$(self.DOM.accept).attr("style", null);
			$(self.DOM.accept).addClass('launching');
			$(XT.router).trigger(C.ROUTE_REQUEST, {request:"pos_print", trigger:e});
		});
		$(this.DOM.accept).on([C.VMOUSEOUT, C.VMOUSEUP].join(" "), function(e) {
			$(self.DOM.accept).removeClass(FX.pressed);
			$(self.DOM.accept).css({background: self.bg_gradient(e) });
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
					$("<ul>").addClass('orbs').append([
						$("<li>").addClass('orb').append([
							$("<div>").addClass("quantity").append($("<span>").html(r.pricing.quantity)),
			                $("<span>").addClass("size").html( "&nbsp;x&nbsp;" + r.pricing.label.replace("in",'"') ),
			                $("<span>").addClass("title").html("&nbsp;" + r.orb.Orb.title),
					        $("<li>").addClass("orb-opts").append(opts)
			            ]),
					])
				)
			)
		}
		return food;
	},
	customer_details: function(service) {
		var address = $.extend({}, service.address);
		var include_keys = ['address', 'address_2','postal_code', 'note'];
		var phone;
		try {
		 phone = "("+[address.phone.slice(0,3), address.phone.slice(3,6), address.phone.slice(6)].join("-")+")";
		} catch(e) {
			phone = address.phone;
		}
		var address_el = $("<ul>").addClass('address').append(
							$("<li>").addClass("customer-name").html([address.firstname, address.lastname, phone].join(" "))
						);
		if (address.note && address.note.length != 0) address.note = "Note: " + address.note;
		for (var key in address) {
			if (!in_array(key, include_keys) || !address[key] || address[key].length == 0 || !defined(address[key].length) ) continue;
			$(address_el).append( $("<li>").addClass(key).html(address[key]) );
		}
		return address_el;
	},
	update_order_method: function() {
		$(this.DOM.order_method)
			.html("")
			.removeClass("pickup delivery")
			.addClass(this.current.order.Service.order_method)
			.append( $("<span>").html(this.current.order.Service.order_method.toUpperCase()));
	}
};