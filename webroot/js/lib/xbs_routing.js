/**
 * Created by jono on 1/22/15.
 */
	var print_from_queue_requests = 0;
var xbs_routing = {
		init: function () {
			/* For launching routes via the DOM */
			$(C.BODY).on(C.CLK, XSM.global.route, null, function (e) {
				var disabled = $(e.currentTarget).hasClass(XSM.effects.disabled);
//				var inelligible = $(e.currentTarget).hasClass(XSM.effects.disabled)
				if ( !disabled ) {
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('route'), trigger: e});
				}
			});

			/* For launching routes via ROUTE_REQUEST events */
			$(this).on(C.ROUTE_REQUEST, function (e, data) {
				var route = this.route(data.request, data.trigger);
				if (route) this.launch(route, e);
			});
		},
		launch: null,
		route: function (request, event) {
			var routes = {
				close_modal: new XtremeRoute("close_modal", {
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
								}),
				confirm_address: new XtremeRoute("confirm_address", {
					modal: XSM.modal.primary,
					url: {url: "confirm-address", type: C.GET, defer: true},
					params: ['context', 'restore'],
					callbacks: {
						params_set: function () {
							var on_close = "<div id='on-close' class='true-hidden' data-action='unstash'></div>";
							switch (this.read('context')) {
								case "review":
									this.set_callback("launch", function (e) {
										var data = this.deferal_data;
										var modal = this.modal;
										var modal_content = this.modal_content;
										XBS.layout.dismiss_modal(modal, false);
										setTimeout(function () {
											$(modal_content).html(data);
											$(XSM.modal.on_close).replaceWith(on_close);
											$(XSM.modal.submit_order_button_wrapper).html(
												XSM.generated.order_address_button('review')
											);
											setTimeout(function () { $(modal).removeClass(XSM.effects.slide_up);}, 30);
										}, 600);
									});
									break;
								case "splash":
									this.set_callback("launch", function (e) {
										var data = this.deferal_data;
										var modal = this.modal;
										var modal_content = this.modal_content;
										XBS.layout.dismiss_modal(modal, false);
										setTimeout(function () {
											$(modal_content).html(data);
											$(XSM.modal.submit_order_button_wrapper).html(
												XSM.generated.order_address_button('menu')
											);
											$(XSM.modal.on_close).replaceWith(on_close);
											setTimeout(function () { $(modal).removeClass(XSM.effects.slide_up);}, 30);
										}, 600);
									});
									break;
								case "menu":
									this.url.defer = false;
									break;
								case "submit":
									this.unset('url');
									this.unset('modal');
									var secondary_launch = false;
									if (this.read('restore') == "review") secondary_launch = "order/review";
									if (this.read('context') == "menu") secondary_launch = "menu";
									this.set_callback("launch", function () {
											XBS.validation.submit_address(this, secondary_launch);
										}
									);
									break;
								case "cancel":
									this.unset('url');
									this.unset('launch');
									switch (this.read('restore') ) {
										case 'menu':
											XBS.menu.unstash_menu();
											break;
										default:
											XBS.layout.dismiss_modal(this.modal);
											break;
									}
									break;
							}
						}
					}
				}),
				continue_ordering: new XtremeRoute("continue_ordering", {
					callbacks: {
						launch: function () {
							XBS.menu.show_orb_card_front_face()
							setTimeout(function () {
								XBS.layout.dismiss_modal();
								XBS.menu.reset_orb_card_stage();
							}, 900);
						}
					}
				}),
				flash: new XtremeRoute("flash", {
					params: {type: { url_fragment: false}},
					modal: XSM.modal.flash,
					behavior: C.OL,
					callbacks: {
						launch: function () {
							$(this.modal_content).html("<h5>Oh Noes!</h5><p>Something went awry there. Let us know so we can fix it!</p>");
							$(this.modal).removeClass(XSM.effects.slide_up);
						}
					}
				}),
				favorite: new XtremeRoute("favorite", {
					params: {context: {value: null, url_fragment: false}},
					url: {url: "favorite"},
					data: false,
					callbacks: {
						modal: XSM.modal.flash,
						params: function () { this.data = $(XSM.menu.orb_order_form).serialize(); },
						launch: function (e, resp) { pr(resp);}
					}
				}),
				finish_ordering: new XtremeRoute("finish_ordering", {
					modal: XSM.modal.primary,
					url: {url: "review-order"},
					behavior: C.STASH_STOP
				}),
				footer: new XtremeRoute("footer", {
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
				}),
				login: new XtremeRoute("login", {
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
									this.url = {url:"login/email", defer:false, type: C.GET};
									if (restore) {
										this.set_callback('launch', function() {
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
				}),
				menu: new XtremeRoute("menu", {
					params: ['reveal_method'],
					modal: false,
					url: {url: "menu"},
					callbacks: {
						params_set: function() {
							if (this.read('reveal_method') == 'unstash') {
								this.unset('url');
								this.unset('launch_callback');
								XBS.layout.dismiss_modal(XSM.modal.primary);
								setTimeout(function() { XBS.menu.unstash_menu();}, XBS.data.delays.global_transition);
							}
						},
						launch: function () { XBS.splash.fold();}
					}
				}),
				menuitem: new XtremeRoute("menuitem", {
					url: {url: "menuitem"},
					params: {orb_id: {value: null, url_fragment: true}}
				}),
				order_accepted: new XtremeRoute('order_accepted', {
					url:"order-accepted",
					modal:XSM.modal.primary
				}),
				order_update: new XtremeRoute('order_update', {
					params: ['source'],
					callbacks: {
						params_set: function() {
							switch (this.read('source') ) {
								case 'form':
									XBS.menu.update_orb_configuration_ui();
									break;
								case 'ui':
									XBS.menu.update_orb_form();
									break;
								case 'reset':
									XBS.menu.update_orb_form();
									XBS.menu.update_orb_configuration_ui();
									break;
							}
						}
					}
				}),
				orbcat: new XtremeRoute("orbcat", {
					params: {
						id: {value: null},
						name: { value: null}
					},
					callbacks: {
						params_set: function () {
							XBS.menu.refresh_active_orbcat_menu(this.params.id.value, this.params.name.value);
						}
					}
				}),
				orb: new XtremeRoute("orb", {
					params: { id: {value: null} },
					callbacks: { params_set: function () { XBS.menu.refresh_orb_card_stage(this.params.id.value); } }
				}),
				orb_opt: new XtremeRoute("orb_opt", {
					params: ["context", "element", "title", "weight"],
					behavior: C.STOP,
					callbacks: {
						params_set: function () {
							if (this.read('context') != "form_update") pr(this);
							switch (this.read('context')) {
								case "form_update":
									var weight = -1;
									/*
										1. get the real weighting if the opt has been activated
										2. set the corresponding form field to the opt's weighting

									 #1 */
									var element = this.read('element');
									if (!this.read('weight') ) this.write("params.title.value", title_case(this.read('title')) );

									if ( $(this.read('element')).hasClass(XSM.effects.active)) {
										weight = $($(element).find(XSM.menu.orb_opt_icon_active)[0])
												.data('route').split("/")[4];
										var opt_list_item = XSM.generated.tiny_orb_opt_list_item(this.read('title'), weight);
										XBS.data.tiny_orb_opts_list[element] = opt_list_item;
									} else XBS.data.tiny_orb_opts_list[element] = false;
									/* #2 */
									var form_opt_id = XSM.generated.order_form_opt_id(element);
									$(form_opt_id).val(weight);
									break;
								case "weight":
									var parent_opt = $(this.trigger.element).parents(XSM.menu.orb_opt)[0];
									if ( $(parent_opt).hasClass(XSM.effects.active) ) {
										this.trigger.event.stopPropagation();
									}
									if ($(this).hasClass(XSM.effects.inelligible) ) return;
									XBS.menu.toggle_orb_opt_icon(this.trigger.element, true);
									break;
								case "opt":
									XBS.menu.toggle_orb_opt(this.read('element'), true);
									break;
							}
						}
					}
				}),
				orb_card: new XtremeRoute("orb_card", {
					params: ["method", "channel", "data"],
					stop_propagation: true,
					callbacks: {
						params_set: function () {
							var launch = false;
							var method = this.read('method');
							switch ( method ) {
								case 'configure':
									XBS.menu.configure_orb(this.read('channel'), this.read('data'));
									break;
								case 'add_to_cart':
									switch ( this.read('channel') ) {
										case "confirm":
											this.url = {
												url:"add-to-cart",
												type: C.POST,
												defer:true,
												data: $(XSM.menu.orb_order_form).serialize()
											};
											launch = function () {
												var data = JSON.parse(this.deferal_data);
												if (data.success == true) {
													XBS.cart.add_to_cart();
													$(XSM.modal.orb_card).show('clip');
													$(XSM.topbar.topbar_cart_button).show()
													setTimeout(function () {
														$(XSM.topbar.topbar_cart_button).removeClass(XSM.effects.fade_out);
													}, 300);
												}
											}
											break;
										case 'cancel':
											launch = XBS.menu.reset_orb_card_stage();
											break;
									}
									break;
								default:
									launch = function () { XBS.menu.toggle_orb_card_row_menu(method, null);}
									break;
							}
							if (launch) this.set_callback("launch", launch)
						}
					}
				}),
				order: new XtremeRoute("order", {
					params: ["method", "context"],
					url: { url: "review-order", type: C.GET, defer: false},
					modal: XSM.modal.primary,
					callbacks: {
						params_set: function () {
							switch (this.read('method')) {
								case "clear":
									this.url = { url: "clear-cart", type: C.GET, defer: true};
									this.set_callback("launch", function () {
										if (this.deferal_data) {
											// todo: handle cart-clearance verification
										}
										XBS.menu.clear_cart();
										XBS.layout.dismiss_modal(XSM.modal.primary);
									});
									break;
								case "view":
									this.url.url = "cart";
									this.url.defer = false;
									this.change_behavior(C.STASH_STOP);
									this.unset("launch");
									break;
								case "finalize":
									this.url = {
										url: "orders" + C.DS + "finalize",
										defer:true,
										data:$("#OrderReviewForm").serialize(),
										type:C.POST
									}
									this.set_callback("launch", function() {
										var data = $.parseJSON(this.deferal_data);
										var trigger = this.trigger;
										if (!data.error) {
											if (data.order_id) {
												var route = "pending_order"+ C.DS + data.order_id + C.DS + "launching";
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
										this.__set_behavior(C.STASH_STOP);
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
				}),
				order_method: new XtremeRoute("order_method", {
					modal: XSM.modal.primary,
					modal: false,
					url: {url: "order-method", type: C.POST, defer: true},
					params: {
						context: {value: null, url_fragment: false},
						method: {value: null, url_fragment: true}
					},
					callbacks: {
						launch: function () {
							var route = "flash/We weren't able to reach the store to place a delivery request. But you can always try calling!"
					        var response = $.parseJSON(this.deferal_data);
							var trigger = this.trigger.event;
							if ("success" in response && response.success === true) {
								XBS.data.order.order_method = this.read('method');
								if (this.read('method') == "delivery") {
									route = "confirm_address/" + this.read('context')
								} else {
									switch (this.read('context')) {
										case "splash":
											route = "menu";
											break;
										case "review":
											route = "order/review";
											break;
										case "menu":
											return true;
									}
								}
							} else if ("error" in response) {
								route = "flash/" + response.error;
							}
							$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: route, trigger: trigger});
						}
					}
				}),
				payment_method: new XtremeRoute("payment_method", {
					params: ['context', 'method'],
					callbacks: {
						params_set: function() {
							switch (this.read('context') ) {
								case "review_modal":
									XBS.data.order.payment = this.read('method');
									$(XSM.modal.payment_method_input).val(this.read('method'));
									break;
							}
						}
					}
				}),
				pending_order: new XtremeRoute("pending_order", {
					params:{
						order_id: {value:null, url_fragment:true},
						status: {value:null, url_fragment:true}
					},
					modal: XSM.modal.primary,
					url: {url:"order-confirmation", defer: true, type: C.POST},
					callbacks: {
						params_set: function() {
							switch (this.read('status') ) {
								case "launching":
									this.params.status = {value:true};
									this.url.defer = false;
									var request = {
										request: "pending_order"+ C.DS + this.read('order_id') + C.DS + C.PENDING,
										trigger: this.trigger
									};
									setTimeout(function() { $(XBS.routing).trigger(C.ROUTE_REQUEST, request)}, 3000);
									this.unset("launch_callback");
									break;
							}
						},
						launch: function() {
							var data = $.parseJSON(this.deferal_data);

							switch ( Number(data.status) ) {
								case C.REJECTED:
									break;
								case C.ACCEPTED:
									$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"order_accepted", trigger:this.trigger});
									break;
								default:
									var request = {
										request: "pending_order"+ C.DS + this.read('order_id') + C.DS + C.PENDING,
										trigger: this.trigger
									};
									setTimeout(function() {
										$(XBS.routing).trigger(C.ROUTE_REQUEST, request);
									}, 3000);
									break;

							}

						}
					}
				}),
				print_from_queue: new XtremeRoute("print_from_queue", {
					params:['context'],
					callbacks: {
						params_set: function() {
							var debug_this= 0;
							if (debug_this > 0) pr(XBS.printer.queue, "ROUTE: print_from_queue");
							var response;
							switch (this.read('context') ) {
								case 'vendor_accepted':  // ie. first entry into printing loop
									response = XBS.printer.queued() ? XBS.printer.print_from_queue() : false;
									break;
								case 'line_complete':
									response = XBS.printer.queued() ? XBS.printer.print_from_queue() : false;
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
						launch: function() {
							try {
								print_from_queue_requests++;
//								pr(print_from_queue_requests, "print_from_queue_requests");
								$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"print_from_queue/line_complete", trigger:{}});
							} catch(e) {
								pr(e);
							}
						}
					}
				}),
				register: new XtremeRoute("register", {
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
				}),
				splash_order: new XtremeRoute("splash_order", {
					params: {method: { value: null, url_fragment: false}},
					callbacks: {
						launch: function () {
							$(XSM.splash.order_delivery).removeClass(XSM.effects.slide_left);
							$(XSM.splash.order_pickup).removeClass(XSM.effects.slide_right);
						}
					}
				}),
				submit_registration: new XtremeRoute("submit_registration", {
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
				}),
				submit_order_address: new XtremeRoute("submit_order_address", {
					params: { is_splash: {value: null, url_fragment: false}},
					url: {url: "confirm-address" + C.DS + "session", type: C.GET, defer: false},
					callbacks: {
						launch: function () { XBS.validation.submit_address(this);}
					}
				}),
				vendor_get_pending: new XtremeRoute('vendor_get_pending', {
					url:{url:"pending", type: C.GET, defer:true},
					callbacks: {
						params_set: function() {
							var debug_this = 0;
							if (debug_this > 0) pr("<no_args>", "XBS.routing.vendor_get_pending()::params_set", 2);
							if (!XBS.vendor.last_check) {
								XBS.vendor.last_check = now();
								this.unset('url');
							}
							if ( now() -  XBS.vendor.last_check < 3000 ) this.unset('url');
						},
						launch: function() {
							if (this.url.url) {
								XBS.vendor.last_check = now();
								var data = $.parseJSON(this.deferal_data);
								if (!data.error && data.orders.length > 0) {
									XBS.vendor.post_orders(data.orders);
								}
							}
							setTimeout(function() {
								$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
							}, 500);
						}
					}

				}),
				vendor_reject: new XtremeRoute("vendor_reject", {
					params: ['state'],
					callbacks: {
						params_set: function() {
							switch (this.read('state') ) {
								case 'unconfirmed':
									$(XSM.vendor.order_reject_confirmation).removeClass(XSM.effects.slide_left);
								break;
								case 'confirm':
									this.url = {
										url:"vendor-reject" + C.DS + XBS.vendor.current().id + C.DS + C.REJECTED,
										type: C.POST,
										defer:true
									};
									this.set_callback("launch", function() {
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
				}),
				vendor_accept: new XtremeRoute("vendor_accept", {
					params: ['method', 'context'],
					url: { url:"vendor-accept", type: C.POST, defer:true},
					callbacks: {
						params_set: function() {
							this.url.url += C.DS + XBS.vendor.current().id + C.DS + C.ACCEPTED;
							$(XSM.vendor.accept_acknowledge).show();
							setTimeout(function(){
								$(XSM.vendor.accept_acknowledge).addClass(XSM.effects.exposed);
								setTimeout(function(){
									$(XSM.vendor.accept_acknowledge).removeClass(XSM.effects.exposed).hide();
								}, 300);
							}, 30);
							XBS.vendor.last_check = -100000;
						},
						launch: function() {
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
									request:"print_from_queue/vendor_accepted",
									trigger:{}
								});
								if ( true ) XBS.vendor.next();
								else $(XSM.vendor.error_pane).removeClass(XSM.effects.slide_up);
							}
						}
					}
				}),
				view_order: new XtremeRoute("view_order", {
					params: {context: {value: "default", url_fragment: false}},
					modal: XSM.modal.primary,
					url: {url: "cart"},
					behavior: C.STASH_STOP
				})
			}
			var request = request.split(C.DS);
			if (request[0] in routes) {
				var route = routes[request[0]];
				route.__init(request[0], event);
				route.init(request.slice(1));
				return route;
			}
			return false;
		}
	};
/**
 * launch() creates and runs an instance of the class from passed param array
 * @param params
 * @param event
 * @returns {boolean}
 */

var launch = function (route) {
			var debug_this = 0;
			if (debug_this > 0) pr([parent_route, params], "XBS.routing.launch(parent_route, params, event)", 2);
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
					$(XSM.global.loading).removeClass(XSM.effects.fade_out);
					$.ajax({
						type: route.url.type ? route.url.type : C.POST,
						url: route.url.url,
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
							if (route.overlay) $(XSM.modal.overlay).show().removeClass(XSM.effects.fade_out);
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
					$(XBS.routes).trigger(C.ROUTE_REQUEST, {request: "flash"});
				}
			} else {
				$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "NO_AJAX"});
			}
			delete route;
			return true;
		};
xbs_routing.launch = launch;