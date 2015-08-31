/**
 * Created by jono on 8/11/15.
 */
XT.route_collections.pos_api = function() {
	this.pos_print = {
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
	this.pos_pending = {
		url: {url: 'pending', defer: true, type: C.GET},
		callbacks: {
			launch: function () {
				pr("here");
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function (response) { XT.pos.pending.fetch(response.data.orders) }
				}, true, true);
			}
		}

	};
	this.pos_reply = {
		params: {id: {url_fragment: true}, reply:{url_fragment: true} },
		url: { url: "resolve-order", type: C.POST, defer: true},
		callbacks: {
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function (response) { XT.pos.current.resolve(response.data) }
				}, true, true);
			}
		}
	};

	return this;
};