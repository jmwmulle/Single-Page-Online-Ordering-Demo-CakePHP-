/**
 * Created by jono on 1/22/15.
 */

XtremeRouter = function() { this.init(); return this}

XtremeRouter.prototype = {
	constructor: XtremeRouter,
	route_data: {},

	init: function () {
		for (var rc in XT.route_collections) {
			rc = XT.route_collections[rc]();
			for (var route in rc ) this.route_data[route] = rc[route];
		}
		/* For launching routes via the DOM */
		var self = this;
		$(C.BODY).on(C.CLK, XSM.global.route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				e.stopPropagation();
				$(self).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('route'), trigger: e});
			}
		});

		$(C.BODY).on(C.CHANGE, XSM.global.onchange_route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				$(self).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('changeroute'), trigger: e});
			}
		});

		/* For launching routes via ROUTE_REQUEST events */
		$(this).on(C.ROUTE_REQUEST, function (e, data) {
			this.launch(data) });
	},


	/**
	 * launch() creates and runs an instance of the class from passed param array
	 * @param params
	 * @param event
	 * @returns {boolean}
	 */
	launch: function (request_obj) {
		var debug_this = 0;
		if (debug_this > 0) pr([request_obj], "XtremeRouter.launch(request_obj, event)", 2);
		var route_name = request_obj.request.split(C.DS)[0];
		var route_data = this.route_data[ route_name ];
		var route = new XtremeRoute( route_name, route_data, request_obj);
		var launch_delay = 0;
		if (route.stash) launch_delay += XT.orbcard.exposed_face == C.BACK ? 1860 : 900;
		if (route.modal) {
			if ( !route.modal.hidden() && route.url.url && !route.url.defer) launch_delay += route.modal.hide();
			route.modal.resize();
		}
		// >>> LAUNCH MODALS IF REQUIRED<<<
		if (route.url.url) {
			var launch_triggered = false;
			if (!route.suppress_loader) XT.layout.loader.show();
			try {
				$.ajax({
					type: route.url.type ? route.url.type : C.POST,
					url: [XT.host, route.url.url].join(C.DS),
					data: "data" in route.url ? route.url.data : null,
					statusCode: {
						403: function () {
							this.launch("flash/fail");
							if (!launch_triggered) {
								launch_triggered = true;
								$(route).trigger("route_launched", "403_FORBIDDEN")
							}
						}
					},
					success: function (data) {
						XT.layout.loader.hide();
						// >>> DO PRE-LAUNCH EFFECTS <<<
						if (route.stash) XT.menu.stash();

						setTimeout(function () {
							if (debug_this > 1) pr([route, data], route.__debug("launch/success"));
							if (route.url.defer) {
								route.set_deferral_data(data);
							} else {
								route.modal.content.set(data);
								route.modal.show();
							}
							launch_triggered = true;
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "TRIGGERED_POST_SUCCESS"});
						}, launch_delay);
					},
					fail: function () {
						XT.layout.loader.hide();
						if ("fail_callback" in route) {
							route.fail_callback();
						} else {
							XT.layout.launch_route(XT.routes.fail_flash);
						}
						if (!launch_triggered) {
							launch_triggered = true;
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "FAIL_TRIGGER"})
						}
					},
					always: function () {
						XT.layout.loader.hide();
						if (!launch_triggered) {
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "FALLBACK_TRIGGER"})
						}
					}
				});
			} catch (e) {
				pr(e);
				if (!launch_triggered) {
					launch_triggered = true;
					$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "CAUGHT_EXCEPTION"})
				}
				$(this).trigger(C.ROUTE_REQUEST, {request: "flash"});
			}
		} else {
			$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "NO_AJAX"});
		}

	},

	/**
	 *
	 * @param deferral_data
	 * @param success_handler
	 * @param print_response
	 * @param print_deferral_data
	 */
	cake_ajax_response: function (deferral_data, success_handler, print_response, print_deferral_data) {
		if (print_deferral_data) pr(deferral_data, "deferral DATA");
		try {
			if (deferral_data.substr(0, 4) == "<pre") {
				$(XSM.modal.primary_content).html(deferral_data);
				setTimeout(function () { $(XSM.modal.primary).removeClass(XSM.effects.slide_up); }, 30);
			}
		} catch(e) {
			pr("Warning: deferral data wasn't defined.");
		}
		var response = $.parseJSON(deferral_data)
		if (print_response) pr(response, "RESPONSE");

		//TODO: 1) add error messages for the user on appropriate errors; 2) handle such messages via the flash/<msg> route

		// don't proceed to success handler if any part of the request failed
		if ("success" in response) {
			if (is_object(response.success)) {
				for (var controller in response.success) if (response.success[controller] != true) return;
			} else if (response.success !== true)  return
		}

	//	XT.layout.dismiss_modal(XSM.modal.primary);
		if ( success_handler && success_handler.callback != undefined ) {
			"data" in success_handler ? success_handler.callback(response, success_handler.data) : success_handler.callback(response);
		}
		if ( response.delegate_route != undefined ) {
			$(this).trigger(C.ROUTE_REQUEST, {request: response.delegate_route, trigger: {}});
		}
	},


	/**
	 *
	 * @param route_string
	 * @param start_index
	 * @param stop_index
	 * @returns {Array}
	 */
	route_split: function (route_string, start_index, stop_index) {
		var route = route_string.split(C.DS);
		if (isInt(start_index)) {
			return isInt(stop_index) ? route.slice(start_index, stop_index) : route.slice(start_index);
		}
		return route;
	}
}