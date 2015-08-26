/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.tablet_ui = function() {
	this.tablet_print = {
		params: ['context'],
		callbacks: {
			params_set: function () {
				var debug_this = 0;
				if (debug_this > 0) pr(XBS.printer.queue, "ROUTE: print_from_queue");
				var response;
				switch (this.read('context')) {
					case 'vendor_accepted':  // ie. first entry into printing loop
						response = XBS.printer.queued() ? XBS.printer.print_from_queue() : false;
						break;
					case 'line_complete':
						response = XBS.printer.queued() ? XBS.printer.print_from_queue() : false;
						if (!XBS.printer.queued()) XBS.printer.cut(true);
						break;
				}
				if (response === false) this.unset('launch');
				//
				//							XBS.printer.tab_out(response, "print response");
				//							if ( response.error) {
				//								XBS.printer.queue.unshift({text:"WARNING: Receipt Line Dropped!", style:"h4"})
				//								pr(response, "DROPPED LINE", 1);
				//							}
				//							if (response.queue_empty) {
				//								if (!XBS.printer.printer_available) XBS.printer.render_virtual_receipt();
				//								this.unset('launch');
				//							}
			},
			launch: function () {
				try {
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "print_from_queue/line_complete", trigger: {}});
				} catch (e) {
					pr(e);
				}
			}
		}
	};
	this.tablet_fetch_pending = {
		url: {url: 'pending', defer: true, type: C.GET},
		callbacks: {
			params_set: function () {
				var debug_this = 0;
				if (debug_this > 0) pr("<no_args>", "XBS.routing.vendor_get_pending()::params_set", 2);
				if (!XBS.vendor.last_check) {
					XBS.vendor.last_check = now();
					this.unset('url');
				}
				if (now() - XBS.vendor.last_check < 3000) this.unset('url');
			},
			launch: function () {
				if (this.url.url) {
					XBS.vendor.last_check = now();
					var data = $.parseJSON(this.deferral_data);
					if (!data.error && data.orders.length > 0) {
						XBS.vendor.post_orders(data.orders);
					}
				}
				setTimeout(function () {
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger: {}});
				}, 500);
			}
		}

	};
	this.tablet_reject = {
		params: ['state'],
		callbacks: {
			params_set: function () {
				switch (this.read('state')) {
					case 'unconfirmed':
						$(XSM.vendor.order_reject_confirmation).removeClass(XSM.effects.slide_left);
						break;
					case 'confirm':
						this.url = {
							url: "vendor-reject" + C.DS + XBS.vendor.current().id + C.DS + C.REJECTED,
							type: C.POST,
							defer: true
						};
						this.set_callback("launch", function () {
							var data = $.parseJSON(this.deferral_data);
							$(XSM.vendor.order_reject_confirmation).addClass(XSM.effects.slide_left);
							// todo: make sure the rejection went well;
						});
						break;
					case 'cancel':
						$(XSM.vendor.order_reject_confirmation).addClass(XSM.effects.slide_left);
						break;
				}
			}
		}
	};
	this.tablet_accept = {
		params: ['method', 'context'],
		url: { url: "vendor-accept", type: C.POST, defer: true},
		callbacks: {
			params_set: function () {
				this.url.url += C.DS + XBS.vendor.current().id + C.DS + C.ACCEPTED;
				$(XSM.vendor.accept_acknowledge).show();
				setTimeout(function () {
					$(XSM.vendor.accept_acknowledge).addClass(XSM.effects.exposed);
					setTimeout(function () {
						$(XSM.vendor.accept_acknowledge).removeClass(XSM.effects.exposed).hide();
					}, 300);
				}, 30);
				XBS.vendor.last_check = -100000;
			},
			launch: function () {
				var data = $.parseJSON(this.deferral_data);
				if (data.success) {
					var order = XBS.vendor.current();
					XBS.vendor.pending_orders.shift()
					XBS.vendor.current_order = null;
					XBS.vendor.last_check = now();
					$(XSM.vendor.order_accept_button).removeClass(XSM.effects.launching);
					$(XSM.vendor.order_accepted).addClass(XSM.effects.fade_out);
					XBS.printer.queue_order(order);
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {
						request: "print_from_queue/vendor_accepted",
						trigger: {}
					});
					if (true) XBS.vendor.next();
					else $(XSM.vendor.error_pane).removeClass(XSM.effects.slide_up);
				}
			}
		}
	};

	return this;
};