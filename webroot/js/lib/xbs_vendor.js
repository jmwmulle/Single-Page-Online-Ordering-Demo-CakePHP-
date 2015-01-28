/**
 * Created by jono on 1/24/15.
 */
var xbs_vendor = {
	pending_orders:[],
	last_check: 0,
	last_tone_play: 0,
	init: function() {
		var vendor_page = $("html").find(XSM.vendor.self)[0]

		if (vendor_page) {
			XBS.data.is_vendor_page = true;
			setTimeout(function() {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
			}, 3000);
		}

		$(XSM.vendor.order_accept_button).on(C.VMOUSEDOWN, function(e) { $(this).addClass(XSM.effects.pressed); });
		var bg_string = function(success_stop, ready_stop) {
			var success_color = "rgba(50,255,50,1)";
			var ready_color = "rgba(125,185,232,1)";
			var bg_str = "-webkit-linear-gradient(left, " + success_color + " " + success_stop + "%,";
			bg_str += ready_color + " " + ready_stop +"%," + ready_color + " 100%)";
			return bg_str;
		}
		$(C.BODY).on(C.VMOUSEMOVE, XSM.vendor.order_accept_button_pressed, null, function(e) {
			var success_stop = Math.round( 100 * (e.pageX - $(XSM.vendor.order_accept_button).offset().left) / 512);
			var ready_stop = Math.round( 100 * (e.pageX - $(XSM.vendor.order_accept_button).offset().left) / 512) + 1;
			if (success_stop < 95) {
				$(XSM.vendor.order_accept_button).css({ background: bg_string(success_stop, ready_stop) });
			} else {
				$(XSM.vendor.order_accept_button).attr("style", null);
				$(XSM.vendor.order_accept_button).addClass('launching');
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"vendor_accept/print", trigger:this});
			}
		});
		$(XSM.vendor.order_accept_button).on(C.VMOUSEOUT, function(e) {
			$(this).removeClass(XSM.effects.pressed);
			$(XSM.vendor.order_accept_button).css({background: bg_string(0, 0) });
		});
		$(XSM.vendor.order_accept_button).on(C.VMOUSEUP, function(e) {
			$(this).removeClass(XSM.effects.pressed);
			$(XSM.vendor.order_accept_button).css({background: bg_string(0, 0) });
		});

		return true;
	},
	next: function() {
		var debug_this = 1;
		if (debug_this > 0) pr("<no_args>", "XBS.vendor.next()", 2);
		if (XBS.vendor.pending()) {
			$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
		} else {
			$(XSM.vendor.next_order).addClass(XSM.effects.slide_up);
			$(XSM.vendor.back_splash).show();
			setTimeout(function() {
				setTimeout( function() {
					$(XSM.vendor.back_splash).removeClass(XSM.effects.fade_out);
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
				}, 150);
			}, 150);
		}
		return true;
	},
	current: function(order) {
		if (order) XBS.vendor.current_order = order;
		return XBS.vendor.current_order === null ? false : XBS.vendor.current_order
	},
	pending: function() {
		var length = parseInt(XBS.vendor.pending_orders.length);
		return length > 0 ? length : false;
	},
	post_orders: function(orders) {
		var debug_this = 0;
		if (debug_this > 0) pr({orders:orders, orders_length:orders.length}, "vendor.post_orders(orders)", 2);
		XBS.vendor.pending_orders = orders;
		XBS.vendor.update_pending_display()
		XBS.vendor.update_current_order()
		return;
	},
	update_current_order: function() {
		if ( !XBS.vendor.current() && XBS.vendor.pending() ) {
			/* Get a fresh copy of the basic order display HTML from a hidden element in the page. */
			var order_content = $.parseHTML($(XSM.vendor.order_content_sample).html());
			var order = XBS.vendor.current(XBS.vendor.pending_orders[0]);
			var food = "";
			for (var i in order.food) food += XSM.generated.vendor_orb_desc(i, order.food[i]);
			$($(order_content).find(XSM.vendor.order_address)[0]).html(order.address);
			$($(order_content).find(XSM.vendor.customer_name)[0]).html(order.customer);
			$($(order_content).find(XSM.vendor.food_list)[0]).html(food);
			if ( $(XSM.vendor.next_order).hasClass(XSM.effects.slide_up) ) {
				$(XSM.vendor.order_content_detail).replaceWith(order_content)
				$(XSM.vendor.order_content_detail).removeClass(XSM.effects.fade_out);
				$(XSM.vendor.back_splash).addClass(XSM.effects.fade_out);
				setTimeout(function() {
					$(XSM.vendor.back_splash).hide();
					setTimeout(function() {
						if (now() - XBS.vendor.last_tone_play > 10000) {
							XBS.printer.play_order_tone();
							XBS.data.last_tone_play = now();
						}
						$(XSM.vendor.next_order).removeClass(XSM.effects.slide_up);
					}, 30);
				}, 300);
			} else {
				$(XSM.vendor.order_content_detail).addClass(XSM.effects.fade_out);
				setTimeout(function() {
					$(XSM.vendor.order_content_detail).replaceWith(order_content);
					setTimeout(function() {
						$(XSM.vendor.order_content_detail).removeClass(XSM.effects.fade_out);
					},30);
				}, 300)
			}
		}
	},
	update_pending_display: function() {
		var debug_this = 0;
		var current_displayed_count = parseInt($(XSM.vendor.order_count).html());
		var pending = XBS.vendor.pending_orders.length - 1;
		if (debug_this > 1) pr([pending, current_displayed_count], "vendor.update_pending_display()", 2);
		if (pending > 0 ) $(XSM.vendor.order_count_bubble).removeClass(XSM.effects.fade_out);
		else $(XSM.vendor.order_count_bubble).removeClass(XSM.effects.fade_out);

		if (current_displayed_count != pending) {
			$(XSM.vendor.order_count).addClass(XSM.effects.fade_out);
			setTimeout(function() {
				$(XSM.vendor.order_count).html(pending);
				setTimeout(function() {
					$(XSM.vendor.order_count).removeClass(XSM.effects.fade_out);
				}, 30);
			}, 150);
		}
		return true;
	}
};