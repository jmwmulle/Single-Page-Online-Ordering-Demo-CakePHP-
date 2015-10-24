/**
 * Created by jono on 1/24/15.
 */
var accept_messages = ["Challenge accepted.", "No stomach unfed.", "Get ready to be XTREMELY fed.", "Yes ma'am!", "Yes sir!",
                       "I can refuse no appetite.", "Heed the call of hunger, mighty chef!", "Thwart yonder angry stomach!",
                       "Hells yeah I'ma cook that.", "Ain't no party like an Xtreme party.", "The drunk & hungry yearn for you...",
                       "Sauce. Cheese. Sauce. Cheese.", "Mountains are nice.", "BOOM goes the dynamite.", "I can has 'za?",
                       "Psy... again? Can we change this?"
];

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
	is_tablet: undefined,
	styles: {
		default: {indent:0, scale: 3, line_h:1.75, align:'left', bold: false, underline: false},
		center: {indent:0, scale: 3, line_h:1.75, align:'center', bold: false, underline: false},
		right: {indent:0, scale: 3, line_h:1.75, align:'right', bold: false, underline: false},
		medium: {indent:0, scale: 2.5, line_h:2.5, align:'medium', bold: false, underline: false},
		small: {indent:0, scale:1.5, line_h:3, align:'left', bold: false, underline: false},
		h1: {indent:0, scale: 5, line_h:7.5, align: "center", bold: false, underline: false},
		h2: {indent:0, scale: 4, line_h:6, align: "center", bold: false, underline: false},
		h3: {indent:0, scale: 3, line_h:4.5, align: "center", bold: false, underline: false},
		h4: {indent:0, scale: 2, line_h:5, align: "center", bold: false, underline: false},
		h5: {indent:0, scale: 1, line_h:2, align: "center", bold: false, underline: false}
	},
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
		},
		error: {
			box: undefined,
			mesage: undefined
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
		receipt: undefined,
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
			self.current.update = function(recovered) {
				var hide_time = self.splash.hide();
				setTimeout( function() { hide_time += self.current.hide() }, hide_time);
				if ( defined(recovered) ) {
					self.current.order = recovered;
				} else {
					self.current.order = self.pending.next();

					try {
						if (self.current.order) {
							var r_lines = self.current.receipt_lines();
							var order_json = JSON.stringify( self.current.order );
							var receipt_json = JSON.stringify( r_lines );

							self.tablet_response( Android.set_current( order_json, receipt_json) );
						}
					} catch (e) {
						if ( self.is_tablet ) self.pos_error(e, "133: current.update()[1]");
					}
				}
				if ( self.current.order ) {
					try {
						Android.play_tone("gangnam");
					} catch (e) {
						console.log(e)
						if (self.is_tablet) self.pos_error(e, "133: current.update()[2]");
						new Audio("files/gangnam_style.mp3").play();
					}
					var route = ["pos_reply", self.current.order.id, C.ACCEPTED].join(C.DS);
					$(self.DOM.accept).data('pressroute', route);
					$(self.DOM.current.address).html("").append( self.customer_details(self.current.order.Service) );
					$(self.DOM.current.food).html("").append( self.order_rows( self.current.order.Order) );
					self.update_order_method();
					setTimeout(function () { hide_time += self.current.show() }, hide_time);
				} else {
					self.current.hide();
				}
				return hide_time
			};
			self.current.accept = function(data) {
				if (data.error) self.pos_error(data.error, "153: current.accept()")
				$(self.DOM.accept).removeClass(FX.loading);
				$(self.DOM.accept).removeClass(FX.pressed);
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
				start_size -= 2;
				$(self.DOM.pos_hero.message.text).css("font-size", start_size)
				$(self.DOM.pos_hero.box).removeClass(FX.hidden);
				setTimeout( function() { $(self.DOM.pos_hero.box).removeClass(FX.slide_right); }, 30);
				setTimeout(function() { $(self.DOM.pos_hero.message.box).removeClass(FX.fade_out) }, 300);
			};
			self.current.receipt_lines = function() {
				var s = self.current.order.Service;
				var a = self.current.order.Service.address;
				var r = [];

				r.push( [ s.order_method == "delivery" ? "DELIVERY (" + str_to_upper(s.pay_method) + ")" : "PICKUP", "h2", true]);
				r.push( [ s.paid ? "::::: PAID :::::" : "*** NOT PAID ***", "h2", true] );
				r.push([" ", "h5", true]);
				r.push([ [a.firstname, a.lastname].join(" "), "h3", true]);
				r.push([" ", "h5", true]);
				try {
					r.push([[a.phone.substr(0, 3), a.phone.substr(3, 3), a.phone.substr(6)].join("."), "h4", true]);
				} catch (e) {
					r.push("PHONE_NUM_ERROR :( :( :(", "h4", true);
				}
				if (s.order_method == "delivery") {
					r.push([a.address, "default", true]);
					if ( defined(a.address_2) ) r.push([a.address_2, "h4", true]);
					r.push([a.postal_code, "h4", true]);
					switch (a.building_type) {
						case 0:
							r.push(["House", "h4", true]);
							break;
						case 1:
							r.push(["Apartment", "h4", true]);
							break;
						case 2:
							r.push(["Office/Other", "h4", true]);
							break;
					}
					if (defined(a.note)) {
						r.push(["************************ ADDRESS NOTE **************************", "h5", true]);
						r.push([a.note, "h4", true]);
						r.push(["****************************************************************", "h5", true]);
					}
				}
				for (var id in self.current.order.Order) {
					var o = self.current.order.Order[id];
					var inv = self.current.order.Invoice;
					var rank = o.pricing.rank;
					var size = o.orb.Pricedict["l"+rank];
					var o_str = "(" + o.pricing.quantity + ")" + " x " + size + " " + o.orb.Orb.title;
					var p_str = o.pricing.net_formatted;
					while (p_str.length < 10) { p_str = " " + p_str; }
					if (o_str.length > 22) {
						var o_str_parts = o_str.split(" ");
						var new_ostr_l1 = "";
						var new_ostr_l2 = "";
						for (var ol_part = 0; ol_part < o_str_parts.length; ol_part++) {
							var part = o_str_parts[ol_part];
							if (new_ostr_l1.length + 1 + part.length < 22) {
								new_ostr_l1 += " " + part;
							} else {
								new_ostr_l2 += " " + part;
							}
						}
						while (new_ostr_l2.length < 22) { new_ostr_l2 += " " }
						new_ostr_l2 += p_str;
						r.push([new_ostr_l1, "medium", true]);
						r.push([new_ostr_l2, "medium", true]);
					} else {
						while (o_str.length < 22) { o_str += " "; }
						o_str += p_str;
						r.push([o_str, "medium", true]);
					}
					if (obj_len(o.orbopts) > 0) {
						var opt_str = [];
						for (var i in o.orbopts) {
							var opt = o.orbopts[i].Orbopt;
							//if (opt.default) continue;
							var coverage;
							switch (opt.coverage) {
								case "L":
									coverage = "(L) 0.5 x ";
									break;
								case "R":
									coverage = "(R) 0.5 x";
									break;
								case "D" :
									coverage = "2 x ";
									break;
								default:
									coverage = "";
									break
							}

							r.push(["     " + coverage + opt.title, "small", true]);
						}
					}
				}
				r.push([" ", "h5", true]);
				r.push(["****************************************************************", "h5", true]);
				while (inv.subtotal.length != 10) { inv.subtotal = " " + inv.subtotal;}
				while (inv.hst.length != 10) { inv.hst = " " + inv.hst;}
				while (inv.total.length != 10) { inv.total = " " + inv.total;}
				var subtotal = "           Subtotal: $" + inv.subtotal;
				var hst = "           HST:      $" + inv.hst;
				var total = "           Total:    $" + inv.total;
				r.push([subtotal, "h4", true]);
				r.push([hst, "h4", true]);
				r.push([total, "h4", true]);
				r.push(["****************************************************************", "h5", true]);
				for (var i=0; i < r.length; i++) {
					r[i] = [ r[i][0], obj_values( self.styles[ r[i][1] ]) ];
				}
				return r;
			};
			self.current.print = function() {
				try {
					return self.tablet_response( Android.print_current(), {} );
				} catch (e) {
					if ( self.is_tablet ) self.pos_error( e, "283: current.print()" );
				}
			};
			self.current.clear = function() {
				var cleared = undefined;
				try {
					cleared = self.tablet_response(Android.clear_current(), {});
				} catch (e) {
					if ( self.is_tablet ) self.pos_error(e, "290: current.clear()");
					cleared = true;
				}
				if ( cleared ) {
					$(self.DOM.pos_hero.box).addClass(FX.slide_right);
					setTimeout(function() { $(self.DOM.pos_hero.message.box).addClass(FX.fade_out) }, 500);
					setTimeout(function() { self.current.update() }, 800);
				}
				return cleared;
			}
		},
		hide: undefined,
		show: undefined,
		update: undefined,
		accept: undefined,
		receipt_lines: undefined,
		print: undefined,
		clear: undefined
	},
	pending: {
		fetch_count: 0,
		orders: {},
		init: function (self) {
			self.pending.list = function() { return self.pending.count() > 0 ? self.pending.orders : false };

			self.pending.fetch = function(orders) {
				//if ( self.pending.fetch_count > 2) return;
				self.pending.fetch_count++;
				if ( defined(orders) && orders.length > 0 ) {
					var update = self.pending.count() == 0 && self.current.order == false;
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
					try {
						Android.end_tone();
					} catch(e) {
						if ( self.is_tablet) self.pos_error(e, "389: pending.next()");
					}
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
		this.is_tablet = navigator.userAgent == C.XTREME_TABLET_USER_AGENT;
		this.init_DOM();
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		var uncleared_order = undefined;
		try {
			this.tablet_response(Android.get_current(), {
				callback: function(data) {
					if ( data.Order != null ) {
						console.log("RESTORING THE FOLOWING ORDER");
						//console.log(data.Order);
						uncleared_order = [{Order:{id:data.Order.id, detail:data.Order}}];
					}
				}
			});
		} catch(e) {
			console.log("ERROR TRYING TO GET CURRENT");
			if ( this.is_tablet ) this.pos_error(e.message, "428: init()");
		}

		this.pending.fetch(uncleared_order);
	},
	init_DOM: function() {
		this.DOM.box = $("#pos-ui")[0];
		this.DOM.accept = $(XSM.pos.order_accept_button)[0];
		this.DOM.print_button = $("#order-print-button")[0];
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
		this.DOM.error.box =  $("#fatal-error")[0];
		this.DOM.error.message =  $("span.error-message", this.DOM.error.box)[0];
		$(".pos-button").on("mousedown", function(e) {
			$(this).addClass(FX.pressed);
			var button = this;
			setTimeout( function() {
				if ( $(button).hasClass(FX.pressed) ) {
					$(button).addClass(FX.loading);
					var route = $(button).data('pressroute');
					$(XT.router).trigger(C.ROUTE_REQUEST, {request:route, trigger: e});
				}
			}, 600);
		});
		$(".pos-button").on("mouseup", function() { $(this).removeClass(FX.pressed) });
		return this
	},
	tablet_response: function(response, handler) {
		response = $.parseJSON(response);
		if ( response.success == true) {
			if (defined(handler) && "callback" in handler) handler.callback(response.data);
			return true;
		} else {
			this.pos_error(response.error, "485: tablet_response()");
			return false;
		}
	},
	pos_error: function(e, func) {
		if (!defined(func) ) func = "<undefined>";
		$(this.DOM.error.message).html(e + "<br /><h3>Trace: " + func);
		$(this.DOM.error.box).removeClass(FX.hidden);
		var self = this;
		setTimeout( function() { $(self.DOM.error.box).removeClass(FX.fade_out) }, 30)
		console.log(e.stack);
		throw e;
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
			if ( !is_string(r.pricing.label) ) r.pricing.label = "";
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
