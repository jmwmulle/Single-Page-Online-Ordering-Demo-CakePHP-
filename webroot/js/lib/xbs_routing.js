/**
 * Created by jono on 1/22/15.
 */
var xbs_routing = {
	init: function () {
		XBS.routing.generate_routes();
		/* For launching routes via the DOM */
		$(C.BODY).on(C.CLK, XSM.global.route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('route'), trigger: e});
			}
		});

		$(C.BODY).on(C.CHANGE, XSM.global.onchange_route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('changeroute'), trigger: e});
			}
		});

		/* For launching routes via ROUTE_REQUEST events */
		$(this).on(C.ROUTE_REQUEST, function (e, data) {
			var route = this.route(data.request, data.trigger);
			if (route) this.launch(route, e);
		});
	},
	launch: null,
	routes: {},
	route_generators: {},
	generate_routes: function () {
		// a small adapter allowing route-grouping for readability without changing original routing functions
		for (var route_group in XBS.routing.route_generators) {
			var routes = XBS.routing.route_generators[route_group]();
			for (var route in routes) {
				XBS.routing.routes[route] = routes[route];
			}
		}
	},
	route: function (request, event) {
		var request = request.split(C.DS);
		if (request[0] in XBS.routing.routes) {
			var route = new XtremeRoute(request[0], XBS.routing.routes[request[0]]);
			// please forgive the dual init functions. It was an insane decision to write the "class" that way
			// I learned how to properly use prototype after the budget was long spent
			route.__init(request, request[0], event);
			route.init(request.slice(1));
			return route;
		}
		return false;
	},
	cake_ajax_response: function() {}
};

/**
 * launch() creates and runs an instance of the class from passed param array
 * @param params
 * @param event
 * @returns {boolean}
 */
xbs_routing.launch = function (route) {
	var debug_this = 0;
	if (debug_this > 0) pr({route: route}, "XBS.routing.launch(route)", 2);
	var launch_delay = 0
	var hide_class = false;
	if (route.stash) {
		launch_delay = 900;
		if (XBS.data.orb_card_out_face == C.BACK_FACE) launch_delay += 960;
	}
	if (route.overlay) launch_delay = 300;
	if (in_array(route.modal, [XSM.modal.primary, XSM.modal.splash]))  hide_class = XSM.effects.slide_up;
	if (hide_class && !$(route.modal).hasClass(hide_class) && route.url.defer == false) {
		launch_delay += 300;
		XBS.layout.dismiss_modal(route.modal, false);
	}
	// >>> RESIZE & POSITION PRIMARY IF NEEDED <<<
	XBS.layout.resize_modal(route.modal)

	// >>> LAUNCH MODALS IF REQUIRED<<<
	if (route.url.url) {
		var launch_triggered = false;
		try {
			$.ajax({
				type: route.url.type ? route.url.type : C.POST,
				url: [XBS.data.cfg.root, route.url.url].join(C.DS),
				data: "data" in route.url ? route.url.data : null,
				statusCode: {
					403: function () {
						XBS.routing.launch("flash/fail");
						if (!launch_triggered) {
							launch_triggered = true;
							$(route).trigger("route_launched", "403_FORBIDDEN")
						}
					}
				},
				success: function (data) {
					// >>> DO PRE-LAUNCH EFFECTS <<<
					$(XSM.global.loading).addClass(XSM.effects.fade_out);
					if (route.stash) XBS.menu.stash_menu();

					setTimeout(function () {
						if (debug_this > 2) pr([route, data], route.__debug("launch/success"));
						if (route.url.defer) {
							route.set_deferal_data(data);
						} else {
							$(route.modal_content).html(data)
							if (hide_class) $(route.modal).removeClass(hide_class);
						}
						$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "TRIGGERED_POST_SUCCESS"});
					}, launch_delay);
				},
				fail: function () {
					$(XSM.global.loading).addClass(XSM.effects.fade_out);
					if ("fail_callback" in route) {
						route.fail_callback();
					} else {
						XBS.layout.launch_route(XBS.routes.fail_flash);
					}
					if (!launch_triggered) {
						launch_triggered = true;
						$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "FAIL_TRIGGER"})
					}
				},
				always: function () {
					$(XSM.global.loading).addClass(XSM.effects.fade_out);
					if (!launch_triggered) {
						$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "FALLBACK_TRIGGER"})
					}
				}
			});
		} catch (e) {
			if (!launch_triggered) {
				launch_triggered = true;
				$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "CAUGHT_EXCEPTION"})
			}
			$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "flash"});
		}
	} else {
		$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "NO_AJAX"});
	}
	delete route;
	return true;
};

/**
 *
 * @param deferal_data
 * @param success_handler
 * @param print_response
 * @param print_deferal_data
 */
xbs_routing.cake_ajax_response = function (deferal_data, success_handler, print_response, print_deferal_data) {
	if (print_deferal_data) pr(deferal_data, "DEFERAL DATA");
	if (deferal_data.substr(0, 4) == "<pre") {
		$(XSM.modal.primary_content).html(deferal_data);
		setTimeout(function() { $(XSM.modal.primary).removeClass(XSM.effects.slide_up); }, 30);
	}
	var response = $.parseJSON(deferal_data)
	if (print_response) pr(response, "RESPONSE");

	//TODO: 1) add error messages for the user on appropriate errors; 2) handle such messages via the flash/<msg> route

	// don't proceed to sucess handler if any part of the request failed
	if ("success" in response) {
		if (is_object(response.success)) {
			for (var controller in response.success) if (response.success[controller] != true) return;
		} else if (response.success !== true)  return
	}
	XBS.layout.dismiss_modal(XSM.modal.primary);
	try {
		"data" in success_handler ? success_handler.callback(response, success_handler.data) : success_handler.callback(response);
	} catch (e) {};
	try {
		$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: response.delegate_route, trigger: {}});
	} catch (e) {}

};


/**
 *
 * @param route_string
 * @param start_index
 * @param stop_index
 * @returns {Array}
 */
xbs_routing.route_split = function (route_string, start_index, stop_index) {
	var route = route_string.split(C.DS);
	if (isInt(start_index)) {
		return isInt(stop_index) ? route.slice(start_index, stop_index) : route.slice(start_index);
	}
	return route;
}


xbs_routing.route_generators.vendor_ui = function() {
	this.vendor_ui = {
		params: {target: {url_fragment: true}},
		url: {url: "vendor-ui", defer: true, type: C.POST},
		modal: XSM.modal.primary,
		callbacks: {
			params_set: function () {
				pr(this.read('target'), "vendor_ui");
			},
			launch: function () {
				if (this.read('target') == "opts") {
					$("#menu-options-tab").replaceWith(this.deferal_data);
				}
				if (this.read('target') == "menu") {
					$("#menu-tab").replaceWith(this.deferal_data);
				}
				$(document).foundation();
				//							XBS.vendor_ui.init();
			}
		}
	};
	this.update_menu = {
		params: { target: {url_fragment: true}, attribute: {url_fragment: true} },
		url: { url: "orbs" + C.DS + "update_menu", type: C.POST, data: null },
		callbacks: {
			params_set: function () {
				var data = null;
				if (this.read('attribute') == "orbopts") {
					data = $(XSM.vendor_ui.orbopt_config_form_wrapper).serialize();
				} else {
					var cell_id = as_id(["orb", this.read('target'), this.read('attribute')].join("-"));
					data = $("form", cell_id).serialize();
				}
				this.url = {
					url: ["orbs", "update_menu", this.read('target'), this.read('attribute')].join(C.DS),
					type: C.POST,
					defer: true,
					data: data
				};
				switch (this.read('target')) {
					case 'pricedicts':
						if (this.read('attribute') == 'fetch') {
							this.url = {
								url: "add-price-labels",
								type: C.GET,
								defer: false
							};
							this.set_modal(XSM.modal.primary);
							this.unset('launch');
						}
						if (this.read('attribute') == 'save') {
							this.url = {
								url: "add-price-labels",
								type: C.POST,
								defer: true,
								data: $(XSM.vendor_ui.price_dict_update_form).serialize()
							};
							this.unset('modal');
						}
						break;
				}

			},
			launch: function () {
				XBS.routing.cake_ajax_response(this.deferal_data, {
						callback: function (response, data) {
							if (data.target == 'pricedicts') $("#menu-tab").load("vendor-ui/menu");
							$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "close_modal/primary", trigger: {}});
							XBS.vendor_ui.save_orb(data.target, data.attribute, 'replace' in response ? response.replace : null);
						},
						data: { target: this.read('target'), attribute: this.read('attribute') }
					},
					true
				);
			}
		}
	};
	// ORBS
	this.orb_edit = {
				params: { id: {value: null}, action: {}, action_arg: {} },
				callbacks: {
					params_set: function () {
						switch (this.read('action')) {
							case "delete":
								var confirmation_box = as_id(['delete', 'orb', this.read('id')].join("-"));
								switch (this.read('action_arg')) {
									case "confirm":
										$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
										setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
										this.unset('launch');
										break;
									case "cancel":
										$(confirmation_box).addClass(XSM.effects.fade_out);
										setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
										this.unset('launch');
										break;
									case "delete":
										this.url = {
											url: ["delete-menu-item", this.read('id')].join(C.DS),
											type: C.POST,
											defer: true
										};
										this.set_callback("launch", function () {
											XBS.routing.cake_ajax_response(this.deferal_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_tab).load("vendor-ui/menu", function () {
														XBS.vendor_ui.data_tables('menu');
														XBS.vendor_ui.fix_breakouts();
													});
												}
											}, true, true);
										});
								}
							case "add":
								switch (this.read('action_arg')) {
									case "create":
										this.set_modal(XSM.modal.primary);
										this.url = {
											url: "add-menu-item",
											type: C.GET,
											defer: false
										}
										this.unset('launch');
										break;
									case "save":
										this.unset('modal');
										this.url = {
											url: "add-menu-item",
											type: C.POST,
											defer: true,
											data: $(XSM.vendor_ui.orb_add_form, XSM.modal.primary).serialize()
										}
										this.set_callback("launch", function () {
											XBS.routing.cake_ajax_response(this.deferal_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_options_tab).load("vendor-ui/menu",
														function () {
															XBS.vendor_ui.data_tables('menu');
															XBS.vendor_ui.fix_breakouts();
														});
												}
											}, true);
										});
										break;
								}
								break;

						}
					},
					launch: function () { XBS.routing.cake_ajax_response(this.deferal_data, {}, true, true);}
				}
			};
	this.orb_config = {
		params: ["target", "action", "attribute"],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case 'edit':
						XBS.vendor_ui.edit_cell('orb', this.read('target'), this.read('attribute'));
						break;
					case 'cancel':
						XBS.vendor_ui.cancel_cell_editing('orb', this.read('target'), this.read('attribute'));
						break;
				}
			}
		}
	};

	// ORBOPTS
	this.orbopt_config = {
		url: {url: "orbopt-config", method: C.GET},
		modal: XSM.modal.primary,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				if (this.read('action') != "launch") {
					this.unset('url');
					this.unset('modal');
				}
				switch (this.read('action')) {
					case 'add':
						this.url = {
							url: "add-menu-option",
							type: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.add_orbopt_form).serialize()
						};
						this.set_callback("launch", function () {
							XBS.routing.cake_ajax_response(this.deferal_data, {
								callback: function () { XBS.vendor_ui.reload_tab('opts'); }
							}, true);
						});
					case 'delete':
						var confirmation_box = as_id(['delete', 'orbopt', this.read('id')].join("-"));
						switch (this.read('action_arg')) {
							case "confirm":
								$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
								setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
								this.unset('launch');
								break;
							case "cancel":
								$(confirmation_box).addClass(XSM.effects.fade_out);
								setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
								this.unset('launch');
								break;
							case "delete":
								this.url = {
									url: ["delete-menu-option", this.read('id')].join(C.DS),
									type: C.POST,
									defer: true
								};
								this.set_callback("launch", function () {
									XBS.routing.cake_ajax_response(this.deferal_data, {
										callback: function () {
											$(XSM.vendor_ui.menu_options_tab).load("vendor-ui/opts",
												function () {
													XBS.vendor_ui.data_tables('opts');
													XBS.vendor_ui.fix_breakouts();
												});
										}
									}, true);
								});

						}
						break;
					case 'toggle':
						this.set_callback('launch', function () {
							XBS.vendor_ui.toggle_orbopt(this.read('id'));
						});
						break;
					case 'set_opt_state':
						this.set_callback('launch', function () {
							pr([this.read('id'), this.read('action_arg')]);
							XBS.vendor_ui.set_orbopt_state(this.read('id'), this.read('action_arg'));
						});
						break;
					case 'toggle_group':
						this.set_callback('launch', function () {
							XBS.vendor_ui.toggle_orbopt_group($(this.trigger.event.target).val());
						});
						break;
					case 'filter':
						this.set_callback('launch', function () {
							XBS.vendor_ui.toggle_filter(this.read('id'));
						});
					default:
						break;
				}
			},
			launch: function () {
				var prim_con_h = $(XSM.modal.primary_content).innerHeight();
				var prim_mod_max = $(XSM.modal.primary).innerHeight() - 48;
				if (prim_con_h > prim_mod_max) {
					$(XSM.modal.primary_content).css({
						height: prim_mod_max,
						overflowY: "auto"
					});
				}
				;

				$(document).foundation();
			}
		}
	},
	// "edit" is to orbopts as "config" is to orbs in terms of routing names / functionality in vendor_ui
	this.orbopt_edit = {
		params: ['id', 'action', 'attribute'],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case 'breakout':
						this.unset('launch');
						this.unset('url');
						var target = null;
						if (this.read('attribute') == 'add_opt') {
							target = XSM.vendor_ui.orbopt_add_breakout;
						} else {
							target = XSM.vendor_ui.orbopt_pricelist_add_breakout;
						}
						XBS.vendor_ui.toggle_menu_options_breakout(target);
					case 'edit':
						pr(this.trigger);
						XBS.vendor_ui.edit_cell('orbopt', this.read('id'), this.read('attribute'));
						break;
					case 'cancel':
						pr(this.trigger);

						XBS.vendor_ui.cancel_cell_editing('orbopt', this.read('id'), this.read('attribute'));
						break;
					case 'save':
						this.url = {
							url: ["update-orbopt", this.read('id'), this.read('attribute')].join(C.DS),
							type: C.POST,
							defer: true,
							data: $("form", as_id(["orbopt", this.read('id'), this.read('attribute')].join("-"))).serialize()
						};
						this.set_callback("launch", function () {
							XBS.routing.cake_ajax_response(this.deferal_data, {
									callback: XBS.vendor_ui.save_orbopt,
									data: { id: this.read('id'), attribute: this.read('attribute') }
								},
								true, true);
						});
						break;
				}
			}
		}
	};
	this.orbopt_pricelist = {
		params: ['action', 'action-arg', 'id'], // by context can be either of pricelist.id or orbopt.id, usually former
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "set":
						XBS.vendor_ui.set_orbopt_pricelist_focus();
						break;
					case 'launch':
						this.set('modal', XSM.modal.primary);
						this.url = {
							url:["launch-orbopt-pricelist-config", this.read('id')].join(C.DS),
							type: C.GET,
							defer:false
						};
						this.set_callback('launch', XBS.vendor_ui.fix_breakouts);
						break;
					case 'add':
						XBS.vendor_ui.toggle_pricelist_add("reveal");
						break;
					case "edit":
						if (this.read('action-arg') == "save") {
							var orbopt_id = $("#orbopt-pricelist-select").val();
							this.url = {
								url: ["save-orbopt-pricing-edit", this.read('id'), orbopt_id].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("#orbopt-pricelist-add-edit-form").serialize()
							};
						} else {
							XBS.vendor_ui.edit_orbopt_pricelist();
						}
						break;
					case 'cancel-add':
						XBS.vendor_ui.set_orbopt_pricelist_focus();
						break;
					case "cancel":
						$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "orbopt_edit/-1/cancel/pricing", trigger: {}});
						break;
					case "save":
						if (this.read('action-arg') == "pricelist") {
							var orbopt_id = $("#orbopt-pricelist-select-form", C.BODY).data('opt');
							this.url = {
								url: ["add-orbopt-pricelist", orbopt_id].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("form", XSM.vendor_ui.orbopt_pricelist_add).serialize()
							};
						}
						if (this.read('action-arg') == "opt") {
							this.url = {
								url: ["update-orbopt", this.read('id'), 'pricing'].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("#orbopt-pricelist-select-form", C.BODY).serialize()
							};
						}
						break;
					case "delete":
						//TODO: you wrote the *entire* delete method when you were a) fucking ballin' at code but b) fiending to leave; it's not tested AT ALL yet
						// delete.delete is reached on the second pass, after warning & confirmation
						if ( this.read('action-arg') == "delete" ) {
							this.url = {
								url: ["orbopt-pricing-delete", this.read('id')].join(C.DS),
								type: C.POST,
								defer:true
							}
						} else {
							this.unset("launch");
						}
						XBS.vendor_ui.delete_orbopt_pricelist(this.read('action-arg'));
						break;

				}
			},
			launch: function() {XBS.routing.cake_ajax_response(this.deferal_data, {}, true, true);}
		}
	};
	this.orbopt_optgroup_config = {
		url: {url: "orbopt-optgroup-config", method: C.GET},
		modal: XSM.modal.primary,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "toggle":
						this.unset('url');
						this.unset('modal');
						this.unset('launch');
						var label_id = as_id(["optgroup", this.read('id'), 'label'].join("-")) + " span";
						var field_sel = "input[name='OrboptOrbcat[" + this.read('id') + "]'";
						if ($(label_id).hasClass(XSM.effects.success)) {
							$(label_id).removeClass(XSM.effects.success);
							$(label_id).addClass(XSM.effects.secondary);
							$(field_sel).val(0);
						} else {
							$(label_id).addClass(XSM.effects.success);
							$(label_id).removeClass(XSM.effects.secondary);
							$(field_sel).val(1);
						}
						break;
					case "save":
						this.url = {
							url: this.url.url,
							method: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.orbopt_optgroup_config_form).serialize()
						};
						this.set_callback('launch', function () {
								XBS.routing.cake_ajax_response(this.deferal_data, null, true, true);
							}
						);
						break;
				}
			},
			launch: function () { $(document).foundation(); }
		}
	};
	this.orbflag_config = {
		params: {orbopt: {url_fragment: true}, optflag: {url_fragment: true}},
		url: {url: "optflag-config", type: C.POST, defer: true},
		callbacks: {
			params_set: function () {
				this.url = {
					url: ["optflag-config", this.read('orbopt'), this.read('optflag')].join(C.DS),
					type: C.POST,
					defer: true}
			},
			launch: function () {
				XBS.routing.cake_ajax_response(this.deferal_data, {
						callback: XBS.vendor_ui.toggle_optflag,
						data: { orbopt: this.read('orbopt'), optflag: this.read('optflag') }
					},
					true, true);
			}
		}
	};

	return this
};

xbs_routing.route_generators.modals = function() {
	this.close_modal = {
			params: ["modal", "on_close"],
			callbacks: {
				launch: function () {
					var action = $(XSM.modal[this.read("modal")]).find(XSM.modal.on_close)[0];
					if (action) {
						XBS.layout.dismiss_modal(this.read("modal"), $(action).data('action'));
					} else {
						XBS.layout.dismiss_modal(this.read("modal"), false);
					}
				}
			}
		};
	this.orbcard_modal = {
			params: ["action", "target"],
			propagates:false,
			modal: XSM.modal.primary,
			callbacks: {
				params_set: function() {
					XBS.menu.show_orb_card_front_face()
					switch ( this.read("action") ) {
						case "continue_ordering":
								setTimeout(function () {
									XBS.layout.dismiss_modal();
									XBS.menu.reset_orb_card_stage();
								}, 900);
							break;
						case "view":
							this.url= {
								url: ["review", this.read('target')].join("-"),
								defer: false,
								type: C.GET
							};
							this.change_behavior(C.STASH_STOP);
							break;
					}
				}
			}
		};

	return this
};

xbs_routing.route_generators.menu_ui = function() {
	this.favorite = {
				params: {context: {value: null, url_fragment: false}},
				url: {url: "favorite"},
				data: false,
				callbacks: {
					modal: XSM.modal.flash,
					params: function () { this.data = $(XSM.menu.orb_order_form).serialize(); },
					launch: function (e, resp) { pr(resp);}
				}
			};
	this.menu = {
				params: ['reveal_method'],
				modal: false,
				url: {url: "menu"},
				callbacks: {
					params_set: function () {
						if (this.read('reveal_method') == 'unstash') {
							this.unset('url');
							this.unset('launch_callback');
							XBS.layout.dismiss_modal(XSM.modal.primary);
							setTimeout(function () { XBS.menu.unstash_menu();}, XBS.data.delays.global_transition);
						}
					},
					launch: function () { XBS.splash.fold();}
				}
			};
	this.menuitem = {
		url: {url: "menuitem"},
		params: {orb_id: {value: null, url_fragment: true}}
	};
	this.orbcat =  {
		url: {url: "menu", defer: true},
		params: {
			id: {value: null, url_fragment: true},
			name: {}
		},
		callbacks: {
			launch: function () { XBS.menu.refresh_active_orbcat_menu(this.deferal_data); }
		}
	};
	this.orb_opt = {
		params: ["context", "element", "title", "weight"],
		behavior: C.STOP,
		callbacks: {
			params_set: function () {
				switch (this.read('context')) {
					case "form_update":
						if (!this.read('weight')) this.write("params.title.value", title_case(this.read('title')));
						XBS.menu.set_orbopt_form_state(this.read('element'), this.read('title'));
						break;
					case "weight":
						var parent_opt = $(this.trigger.element).parents(XSM.menu.orb_opt)[0];
						if ($(parent_opt).hasClass(XSM.effects.active)) {
							this.trigger.event.stopPropagation();
						}
						if ($(this).hasClass(XSM.effects.inelligible)) return;
						XBS.menu.toggle_orb_opt_icon(this.trigger.element, true);
						break;
					case "opt":
						XBS.menu.toggle_orb_opt(this.read('element'), true);
						break;
				}
			}
		}
	};
	this.optflag = {
		params: ['target', 'action'],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "filter":
						XBS.menu.toggle_optflag(Number(this.read('target')), false)
						break;
				}
			}
		}
	};
	this.load_orb = {
		url: { url: "menu-item", defer: true },
		params:{id:{url_fragment:true} },
		callbacks: {
			launch: function () {
				// Note: response as a deleaget route to configure_orb/id
				XBS.routing.cake_ajax_response(this.deferal_data,{
					callback: function(response, id) {
						XBS.menu.load_orb_to_stage(id, response.data.view_data)
					},
					data: { id: this.read('id') }
				}, true, true);
			}
		}
	};
	this.configure_orb = {
		params: ['id', 'rank'],
		callbacks: {
			params_set: function() {
				// populate form with any orb configurations in progress
				XBS.menu.load_orb_configuration( XBS.cart.configure( this.read('id'), this.read('rank') ) );
			},
			launch: function() {
				// flip to backface
				XBS.menu.show_orb_card_back_face(this.read('rank'));
			}
		}
	};
	this.orb_card = {
		params: ["action", "action_arg", "data"],
		stop_propagation: true,
		callbacks: {
			params_set: function () {
				var launch = false;
				switch (this.read('action')) {
					case 'add_to_cart':
						switch (this.read('action_arg')) {
							case "confirm":
								this.url = {
									url: "add-to-cart",
									type: C.POST,
									defer: true,
									data: $(XSM.menu.orb_order_form).serialize()
								};
								launch = function () {
									try {
										var data = JSON.parse(this.deferal_data);
										if (data.success == true && XBS.cart.add_to_cart()) {
											XBS.layout.reveal_orb_card_modal();
										}
									} catch (e) {
										throw "Add to cart failed at server:\n " + this.deferal_data;
									}
								}
								break;
							case 'cancel':
								launch = XBS.menu.reset_orb_card_stage();
								break;
						}
						break;
					default:
						launch = function () { XBS.menu.toggle_orb_card_row_menu(this.read('action'), null);}
						break;
				}
				if (launch) this.set_callback("launch", launch)
			}
		}
	};

	return this;
};

xbs_routing.route_generators.order = function() {
	this.order = {
		params: ["method", "context"],
		url: { url: "review-order", type: C.GET, defer: false},
		modal: XSM.modal.primary,
		callbacks: {
			params_set: function () {
				switch (this.read('method')) {
					case "finalize":
						this.url = {
							url: "orders" + C.DS + "finalize",
							defer: true,
							data: $("#OrderReviewForm").serialize(),
							type: C.POST
						}
						this.set_callback("launch", function () {
							var data = $.parseJSON(this.deferal_data);
							var trigger = this.trigger;
							if (!data.error) {
								if (data.order_id) {
									var route = "pending_order" + C.DS + data.order_id + C.DS + "launching";
									$(XBS.routing).trigger(C.ROUTE_REQUEST, {
										request: route,
										trigger: trigger.event
									});
								}
							}
						});
						break;
					case "review":
						if (this.read('context') == 'orb_card') {
							this.change_behavior(C.STASH_STOP);
						}
						break;
				}
			},
			launch: function (e, fired_from) {
				if (this.deferal_data) {
					var data = $.parseHTML(this.deferal_data);
					$(XSM.modal.primary).addClass(XSM.effects.slide_down);
					setTimeout(function () {
						var default_content = $(XSM.modal.primary).find(XSM.modal.default_content)[0];
						$(default_content).replaceWith(data);
						$(XSM.modal.primary).hide()
							.removeClass(XSM.effects.slide_down)
							.addClass(XSM.effects.slide_up).show();
						setTimeout(function () {
							$(XSM.modal.primary).removeClass(XSM.effects.slide_up);
						}, 30);
					}, 300);
				} else {
					XBS.cart.validate_order_review();
				}
			}
		}
	};
	this.order_accepted = {
					url: "order-accepted",
					modal: XSM.modal.primary
			};
	this.splash_order = {
				params: {method: { value: null, url_fragment: false}},
				callbacks: {
					launch: function () {
						$(XSM.splash.order_delivery).removeClass(XSM.effects.slide_left);
						$(XSM.splash.order_pickup).removeClass(XSM.effects.slide_right);
					}
				}
			};
	this.payment_method = {
				params: ['context', 'action'],
				callbacks: {
					params_set: function () {
						pr(this);
						switch (this.read('context')) {
							case "review_modal":
								XBS.modal.payment_method(this.read('action'));
								break;
						}
					}
				}
			};
	this.pending_order = {
		params: {
			order_id: {value: null, url_fragment: true},
			status: {value: null, url_fragment: true}
		},
		modal: XSM.modal.primary,
		url: {url: "order-confirmation", defer: true, type: C.POST},
		callbacks: {
			params_set: function () {
				switch (this.read('status')) {
					case "launching":
						this.params.status = {value: true};
						this.url.defer = false;
						var request = {
							request: "pending_order" + C.DS + this.read('order_id') + C.DS + C.PENDING,
							trigger: this.trigger
						};
						setTimeout(function () { $(XBS.routing).trigger(C.ROUTE_REQUEST, request)}, 3000);
						this.unset("launch_callback");
						break;
				}
			},
			launch: function () {
				var data = $.parseJSON(this.deferal_data);

				switch (Number(data.status)) {
					case C.REJECTED:
						break;
					case C.ACCEPTED:
						$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "order_accepted", trigger: this.trigger});
						break;
					default:
						var request = {
							request: "pending_order" + C.DS + this.read('order_id') + C.DS + C.PENDING,
							trigger: this.trigger
						};
						setTimeout(function () {
							$(XBS.routing).trigger(C.ROUTE_REQUEST, request);
						}, 3000);
						break;

				}

			}
		}
};
	this.order_update = {
		params: ['source'],
		callbacks: {
			params_set: function () {
				switch (this.read('source')) {
					case 'form':
						XBS.menu.orbcard_load_configuration();
						break;
					case 'ui':
						XBS.menu.update_orb_form();
						break;
					case 'reset':
						XBS.menu.update_orb_form();
						XBS.menu.orbcard_load_configuration();
						break;
				}
			}
		}
	};
	this.confirm_address = {
				modal: XSM.modal.primary,
				url: {url: "confirm-address", type: C.GET, defer: true},
				params: ['context', 'restore'],
				callbacks: {
					params_set: function () {
						switch (this.read('context')) {
							case "menu":
								// ie. not from within the modal, so no need to defer
								this.url.defer = false;
								break;
							case "cancel":
								this.unset('url');
								this.unset('launch');
								switch (this.read('restore')) {
									case 'menu':
										XBS.menu.unstash_menu();
										$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"set_order_method/menu/just_browsing", trigger:{}});
										XBS.menu.set_order_method(C.JUST_BROWSING);
										break;
									default:
										XBS.layout.dismiss_modal(this.modal);
										break;
								}
								break;
						}
					},
					launch: function () {
						if (is_object(this.deferal_data) && "modal" in this.deferal_data) { // super tired may be fuckin' wroooong
							XBS.routing.cake_ajax_response(this.deferal_data, {
								data: {context: this.read('context'), modal: this.modal, modal_content: this.modal_content}
							})
						}
					}
				}
			};
	this.set_order_method = {
		modal: XSM.modal.primary,
		url: {url: "order-method", type: C.POST, defer: true},
		params: {
			context: {value: null, url_fragment: true},
			method: {value: null, url_fragment: true}
		},
		callbacks: {
			launch: function () {
				XBS.routing.cake_ajax_response(this.deferal_data, {
					callback: function(response) {
						XBS.data.Service = response.data.Cart.Service;
						XBS.menu.set_order_method();
						XBS.cart.init(response.data.Cart);
					}
				}, true, true);
			}
		}
	};
	this.set_user_address = {
		params: ['id', 'action'],
		callbacks: {
			params_set: function() {
				switch (this.read('action') ) {
					case 'reveal':
						XBS.modal.order_method.reveal_user_addresses(this.read('id'));
						break;
					case 'set':
						XBS.modal.order_method.populate_address_form(this.read('id'));
						break;
				}
			}
		}
	};
	this.cart = {
				modal: XSM.modal.primary,
				params: ["action", "action_arg", "data"],
				propagates: false,
				callbacks: {
					params_set: function () {
						switch ( this.read("action") )  {
							case "add":
								 if ( this.read('action_arg') == 'cancel') {
									this.unset(['url', 'launch']);
									 XBS.menu.reset_orb_card_stage()
								 } else {
									 this.url = {
						                url: "add-to-cart",
						                type: C.POST,
						                defer: true,
						                data: $(XSM.menu.orb_order_form).serialize()
						            }
								 }
							break;
							case "clear":
								this.url = {
									url: "clear-cart",
									type: C.POST,
									defer: true
								};
								this.unset("launch");
							break;
							case "review":
								this.url =  {
									url: "review-cart",
									type: C.GET,
									defer: false
								};
								this.change_behavior(C.STASH_STOP);
								this.unset('launch');
								break;
						}
					},
					launch: function () {
						XBS.routing.cake_ajax_response(this.deferal_data, {
							callback: function(response) {
								pr(response, "add to cart resp.");
								XBS.cart.init(response.data.Cart);
								// only "clear cart" has a delegate response
								if ( !("delegate_route" in response) ) XBS.layout.reveal_orb_card_modal();
							}
						}, true, true);
						try {
							var data = JSON.parse(this.deferal_data);
							if (data.success === true && XBS.cart.add_to_cart()) {

							} else { throw "Add to cart failed at server:\n " + this.deferal_data; }
						} catch (e) {
							try {
								pr(JSON.parse(this.deferal_data), "cart->order", true);
								throw "Add to cart failed at server:\n " + this.deferal_data;
							} catch (e) {
								throw "Add to cart failed at server:\n " + this.deferal_data;
							}
						}
					}
				}
			};
	return this
};

xbs_routing.route_generators.layout_elements = function() {
	this.flash = {
				params: {type: { url_fragment: false}},
				modal: XSM.modal.flash,
				behavior: C.OL,
				callbacks: {
					launch: function () {
						$(this.modal_content).html("<h5>Oh Noes!</h5><p>Something went awry there. Let us know so we can fix it!</p>");
						$(this.modal).removeClass(XSM.effects.slide_up);
					}
				}
			};
	this.footer = {
		params: ["method"],
		callbacks: {
			launch: function () {
				if ($(XSM.global.footer).hasClass('reveal')) {
					$(XSM.global.footer).addClass('stow');
					setTimeout(function () {
						$(XSM.global.footer).removeClass('reveal');
						setTimeout(function () {
							$(XSM.global.footer).removeClass('stow');
						}, 30);
					}, 30);
				}
				if (!$(XSM.global.footer).hasClass('reveal')) {
					$(XSM.global.footer).addClass('reveal');
				}
			}
		}
	};
	this.launch_apology = {
		url: {url: 'launch-apology', type: C.GET, defer: true},
		modal: XSM.modal.primary,
		params: ['action'],
		callbacks: {
			params_set: function () {
				if (this.read('action') == 'close') {
					this.unset('url');
					XBS.layout.dismiss_modal(this.modal);
				}
			}
		}
	};

	return this;
};

xbs_routing.route_generators.user_accounts = function() {
	this.login = {
		url: {url: false, type: C.POST},
		modal: XSM.modal.primary,
		params: {
			context: {},
			channel: {url_fragment: true},
			restore: {}
		},
		callbacks: {
			params_set: function () {
				var context = this.read('context');
				var channel = this.read('channel');
				var restore = this.read('restore');
				pr(this.params, "login::params");
				switch (context) {
					case "confirm-address":
						switch (channel) {
							case "email":
								this.unset("url");
								this.set_callback('launch', function () {
									$(XBS.routing).trigger(C.ROUTE_REQUEST, {
										request: "login/modal/email/confirm-address",
										trigger: this.trigger
									});
								});
								break;
							default:
								this.unset('url');
								this.unset('modal');
								this.set_callback('launch', function () {
									$(XSM.modal.confirm_address_login_panel).hide().removeClass(XSM.effects.true_hidden);
									setTimeout(function () {
										$(XSM.modal.confirm_address_login_panel).show('clip');
									}, 30);
								})
						}
						break;
					case "modal":
						this.url = {url: "login/email", defer: false, type: C.GET};
						if (restore) {
							this.set_callback('launch', function () {
								pr("got here");
								$("#on-close").replaceWith(
									'<div id="on-close" class="true-hidden" data-action="restore/confirm_address"></div>');
							});
						}
						break;
					default:
						if (channel == "email") {
							this.url.url = "login/email";
							this.url.type = C.GET;
							//											this.url.defer = true;
							this.set_callback("launch", function () {
								pr("login/email");
								pr(this.deferal_data);
							})
						}
				}
			},
			launch: function () {
				window.location = "http://development-xtreme-pizza.ca/auth/" + this.read('channel');
			}
		}
	};
	this.register = {
				modal: XSM.modal.primary,
				url: {url: "sign-up", type: C.POST, defer: true},
				params: {
					context: {},
					channel: {value: null, url_fragment: false},
					restore: {},
					hide_reg: {value: false}
				},
				callbacks: {
					params_set: function () {
						var channel = this.read('channel');
						switch (this.read('context')) {
							case "modal":
								this.url.defer = true;
								if (in_array(channel, ['email', "submit"])) this.url.url = false;
								if (in_array(channel, ["twitter", "facebook", "google"])) {
									this.add_param("hide-reg", true, false);
									this.url.url = "auth/" + channel;
								}
								if (channel == 'submit') {
									this.set_callback("launch", function () { XBS.validation.submit_register(this);})
								}
								break;
							case "topbar":
								this.url.defer = false;
								this.unset('launch');
								break;
							case "orb_card":
								this.set_callback("launch", function () {
									var data = this.deferal_data;
									$(XSM.modal.primary_content).html(data);
									$($(XSM.modal.primary).find(".register-link.email")[0]).addClass(XSM.effects.active);
									XBS.menu.toggle_orb_card_row_menu("register", C.HIDE);
									setTimeout(function () {
										$(XSM.modal.primary).removeClass(XSM.effects.slide_up);
										setTimeout(function () {
											$("#registration-method-bar").addClass("diminish");
											setTimeout(function () {
												$(XSM.modal.primary_deferred_content).removeClass(XSM.effects.slide_left);
											}, XBS.data.delays.global_transition);
										}, XBS.data.delays.global_transition);
									}, XBS.data.delays.orb_card_row_toggle);
								});
								break;
						}
					},
					launch: function () {
						var container = $(this.modal).find(XSM.modal.deferred_content)[0];
						var load_time = 30;
						$("#registration-method-bar").addClass("diminish");
						setTimeout(function () {
							$(".register-link.email").addClass(XSM.effects.active);
							if (this.deferal_data) {
								$(container).replaceWith(
									$("<div/>").addClass([XSM.modal.deferred_content, XSM.effects.slide_left].join(" "))
										.html(this.deferal_data)
								);
							}
							setTimeout(function () { $(container).removeClass(XSM.effects.slide_left);}, load_time);
						}, 300);
					}
				}
			};
	this.submit_registration = {
				modal: XSM.modal.primary,
				url: {url: false, type: C.POST, defer: true},
				params: {channel: {value: false, url_fragment: false}},
				callbacks: {
					launch: function () {
						if (this.read('channel') == "email") {
							XBS.validation.submit_register(this);
						} else {
							this.url.url = "auth" + C.DS + this.read('channel');
						}
					}
				}
			};

	return this;
};

xbs_routing.route_generators.tablet_ui = function() {
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
					var data = $.parseJSON(this.deferal_data);
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
							var data = $.parseJSON(this.deferal_data);
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
				var data = $.parseJSON(this.deferal_data);
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

xbs_routing.route_generators.validation = function() {
		this.validate_form = {
			params: ['target', 'context', 'delegate_route'],
			callbacks: {
				params_set: function () {
					switch (this.read('target')) {
						case 'address':
							XBS.validation.submit_address(this.read('context'), this.read('delegate_route'));
							break;
					}
				}
			}
		};

		return this;
};