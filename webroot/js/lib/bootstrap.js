/**
 * J. Mulle, for XtremePizza, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

window.XBS = {
	data: {
		host_root_dirs: {
			xDev: "",
			xProd: "",
			xLoc: "xtreme"
		},
		images: ["..img/splash/bluecircle.png",
		         "..img/splash/deal.png",
		         "..img/splash/email_icon.png",
		         "..img/splash/email_icon_hover.png",
		         "..img/splash/facebook_icon.png",
		         "..img/splash/facebook_icon_hover.png",
		         "..img/splash/twitter_icon.png",
		         "..img/splash/twitter_icon_hover.png",
		         "..img/splash/gplus_icon.png",
		         "..img/splash/gplus_icon_hover.png",
		         "..img/splash/logo.png",
		         "..img/splash/logo_mini.png",
		         "..img/splash/menu.png",
		         "..img/splash/menu_hover.png",
		         "..img/splash/order.png",
		         "..img/splash/order_soon.png",
		         "..img/splash/pizza-bg.jpg",
		         "..img/splash/logo_mini.png"],
		orb_opts: {
		},
		orb_opt_filters: {
		},
		current_orb_card: null,
		delays: {
			menu_stash_delay: 900
		},
		partial_orb_configs: {
		},
		cart: {
		},
		order:{
			method: null,
			address: {
				address_1: null,
				address_2: null,
				postal_code: null,
				instructions: null
			}
		},
		user: {
			name: {
				first:null,
				last: null
			},
			address: {
				address_1: null,
				address_2: null,
				postal_code: null,
				instructions: null
			}
		}
	},
	cfg: {
		root: null,
		developmentMode: true,
		minLoadTime: 1
	},
	evnt: {
		wakeFromSleep: eCustom("wakeFromSleep"),
		assetsLoaded: eCustom("assetsLoaded"),
		route_launched: eCustom("route_launched"),
		orb_card_refresh: eCustom(C.ORB_CARD_REFRESH),
		order_form_update: eCustom(C.ORDER_FORM_UPDATE),
		order_ui_update: eCustom(C.ORDER_UI_UPDATE)
	},
	routes: {
		continue_ordering: new XtremeRoute({
			callbacks:{
				launch:function() {
					XBS.menu.show_orb_card_front_face()
					setTimeout( function() {
						XBS.layout.dismiss_modal();
						XBS.menu.reset_orb_card_stage();
					}, 900);
				}
			}
		}),
		fail_flash: new XtremeRoute({
				modal: XSM.modal.flash,
				behavior: C.OL,
				callbacks: {
					launch: function() {
						$(this.modal_content).html("<h5>Oh Noes!</h5><p>Something went awry there. Let us know so we can fix it!</p>");
						$(this.modal).removeClass(XSM.effects.slide_up);
					}
				}
		}),
		favorite: new XtremeRoute({
			params: {context: {value:null, url:false}},
			url: {url: "favorite"},
			data: false,
			callbacks: {
				modal: XSM.modal.flash,
				params: function() { this.data = $(XSM.menu.orb_order_form).serialize(); },
				launch: function(e, resp) { pr(resp);}
			}
		}),
		finish_ordering: new XtremeRoute({
			modal: XSM.modal.primary,
			url: {url: "finish-ordering"},
			behavior: C.STASH_STOP
		}),
		menu: new XtremeRoute({
			modal: false,
			url: {url:"menu"}
		}),
		menuitem: new XtremeRoute({
			url: {url: "menuitem"},
			params: {orb_id:{value:null, is_url:true}}
		}),
		orbcat: new XtremeRoute({
			params:{
				id:{value: null},
				name:{ value: null}
			},
			callbacks: {
				params_set: function() {
					XBS.menu.refresh_active_orbcat_menu(this.params.id.value, this.params.name.value);
				}
			}
		}),
		orb: new XtremeRoute({
			params:{ id:{value: null} },
			callbacks: { params_set: function() { XBS.menu.refresh_orb_card_stage(this.params.id.value); } }
		}),
		order_method: new XtremeRoute({
			modal: XSM.modal.primary,
			behavior: C.OL,
			params: {
				method: {value:null, is_url:true},
				splash_method: {value:null, is_url:false}},
			callbacks: {
				params_set: function() {
					if (this.params.method.value == C.DELIVERY) {
						this.url.url = "order-method" + C.DS + C.DELIVERY;
					} else if (this.params.method.value == C.SPLASH) {
						this.overlay = false;
						this.modal = XSM.modal.splash;
						switch (this.params.splash_method.value) {
							case "launch":
								this.set_modal(XSM.modal.splash);
								this.url.url = "splash-order"
								break;
							case "delivery":
								delete this.url;
								XBS.layout.dismiss_modal(C.SPLASH);
								setTimeout(function() { XBS.layout.launch_route("order_method/delivery");}, 630);
								break;
							case "pickup":
								delete this.url;
								XBS.layout.dismiss_modal(C.SPLASH);
								XBS.splash.fold("order_method/delivery");
								break;
							}
					} else {
						delete this.url;
					}
				},
				launch: function(e) { XBS.menu.set_order_method(this.params.method.value); }
			}
		}),
		splash_link: new XtremeRoute({
			url:{url:"launch-menu"},
			params: {method: { value: null, url:false}},
			callbacks: {
				launch: function() { XBS.splash.fold(this.params.method == 'order')
				}
			}
		}),
		submit_order_address: new XtremeRoute({
			params: { is_splash:{value:null, url:false}},
			callbacks: {
				launch: function(){
					pr(this.params);
					var is_splash = this.params.is_splash.value;
					 $("#orderAddressForm").validate({
						debug:true,
						rules:{
							"data[orderAddress][firstname]": "required",
							"data[orderAddress][phone]": {required: true, phoneUS: true},
							"data[orderAddress][address]": "required",
							"data[orderAddress][postal_code]": {required: true, minlength:6, maxlength:7}
						},
						messages: {
							"data[orderAddress][firstname]": "Well we have to call you <em>something!</em>",
							"data[orderAddress][phone]": {
								required:"We'll need this in case there's a problem with your order.",
								phoneUS: "Jusst ten little digits, separated by hyphens if you like..."},
							"data[orderAddress][address]":"It's, err, <em>delivery</em> after all...",
							"data[orderAddress][postal_code]": {
								required: "This is how we check if you're in our delivery area!",
								minlength: "Prooooobably something like \"A0A 0A0\"...",
								maxlength: "Prooooobably something like \"A0A 0A0\"..."
							}
						},
						submitHandler: function() {

							$.ajax({
								type:"POST",
								url:"confirm-address/session",
								data: $("#orderAddressForm").serialize(),
								statusCode: {
									403: function() {
										XBS.layout.dismiss_modal(XSM.modal.primary);
										if (is_splash) {
											setTimeout( function() {
											XBS.splash.fold(false);
											set
											}, 300);

									}

//										XBS.layout.launch_route(XBS.routes.fail_flash);
//										if (!launch_triggered) {
//											launch_triggered = true;
//											$(route).trigger("route_launched","403_FORBIDDEN")
//										}
									}
								},
								success: function(data) {
									pr(data);
									try {
										data = $.parseJSON(data);
										XBS.data.user.address = data.address;
									} catch(e) {
									}
									XBS.layout.dismiss_modal(XSM.modal.primary);
									if (this.is_splash.value == true) XBS.splash.fold();
								}
							});
						}
					});
					$("#orderAddressForm").submit();
				}
			}
		}),
		topbar_social: new XtremeRoute({
			params: {channel: {value:null, url:true}},
			modal: XSM.modal.social,
			url: {url:"social"},
			callbacks:{}
		}),
		view_order: new XtremeRoute({
			params: {context:{value:"default", url:false}},
			modal: XSM.modal.primary,
			url: {url:"cart"},
			behavior: C.STASH_STOP
		})
	},

	/*******************************************************************************************************************
	 *                                                                                                                 *
	 *                                                     INIT                                                        *
	 *                                                                                                                 *
	 *******************************************************************************************************************/
	init: function (is_splash, page_name, host, cart) {
		XBS.cfg.page_name = page_name;
		XBS.cfg.is_splash = is_splash === true;
		XBS.fn.setHost(host);
		var init_status = {
			cart: XBS.cart.init(cart),
			layout: XBS.layout.init(),
			splash: XBS.splash.init(),
			menu: XBS.menu.init()
		};
		pr(init_status, "init status");
	},
	layout: {
		init: function () {
//			try {
			var sit_rep = XBS.fn.execInitSequence({"XBS.layout.jq_binds": XBS.layout.jq_binds});
			if (XBS.cfg.is_splash) XBS.layout.detachAnimationTargets();
			var page_content_height = window.innerHeight - ($(XSM.global.topbar).innerHeight() + 3 * C.REM) + C.PX;
			$(XSM.global.page_content).css({minHeight:page_content_height});

//			} catch(e) {
//				pr(e.stack);
//				throw new InitError(e); // todo: list all init exceptions if needed
			return sit_rep;
		},
		detachAnimationTargets: function () {
			$(XSM.global.detachable).each(function () { XBS.layout.detach(this);});
		},
		jq_binds: {
			has_init_sequence: true,
			bind_orb_card_config_archiving: function () {
				$(C.BODY).on(XBS.evnt.orb_card_refresh, null, null, function (e) {
					XBS.menu.archive_orb_card_config(e.data);
				});

				return true;
			},
			init_routing: function() {
				/** bind routes */
				$(C.BODY).on(C.CLK, XSM.global.route, null, function (e) {
					XBS.layout.launch_route($(e.currentTarget).data('route'));
				});
			},
			init_modals: function () {
				/** initially hide overlay & bind dismiss-on-click */
				$(XSM.modal.overlay).addClass(XSM.effects.fade_out).hide().removeClass(XSM.effects.true_hidden);
				$(C.BODY).on(C.CLK, XSM.modal.overlay, null, XBS.layout.dismiss_modal);

				/** bind close-modal button */
				$(C.BODY).on(C.CLK, XSM.modal.close_modal, null, function(e) {
					var modal = $(e.currentTarget).data('modal');
					var action = false;
					if (modal == XSM.modal.primary)  {
						var on_close = $(XSM.modal.primary).find(XSM.modal.on_close)[0];
						if (on_close) action = $(on_close).data('action');
					}

					XBS.layout.dismiss_modal(modal, action);
					return true;
				})
			},
			bind_activizing_lists: function () {
				$("body").on(C.CLK, XSM.global.activizing_list, function (e) {
					XBS.layout.activize(e.currentTarget);
				});

				return true
			},
			bind_multi_activizing_siblings: function () {
				$("body").on(C.CLK, XSM.global.multi_activizing, function (e) {
					XBS.layout.multi_activize(e.currentTarget);
				});

				return true
			},
			bind_ajax_nav: function () {
				$(XSM.global.ajaxLink).each(function () {
					$(this).on(C.CLK, null, $(this).data(), XBS.load);
				});

				return true;
			},
			bind_float_labels: function () {
				$(C.BODY).on(C.MOUSEENTER, asClass(XSM.effects.float_label), null, function (e) {
					XBS.layout.toggle_float_label($(e.currentTarget).attr('id'), C.SHOW);
				});
				$(C.BODY).on(C.MOUSEOUT, asClass(XSM.effects.float_label), null, function (e) {
					XBS.layout.toggle_float_label($(e.currentTarget).attr('id'), C.HIDE);
				});

				return true;
			},
			bind_splash_links: function () {
				$(C.BODY).on(C.CLK, XSM.splash.splash_link, null, function (e) {
					XBS.splash.redirect($(e.currentTarget).data('url'));
				});
				return true;
			},
			bind_topbar_hover_links: function() {
				/** show hover-text */
				$(C.BODY).on(C.MOUSEENTER, XSM.topbar.hover_text_link, null, function(e) {
					if (!$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
						pr("firing!");
						XBS.layout.toggle_topbar_hover_text($(e.currentTarget).data('hover_text'));
					}
				});
				$(C.BODY).on(C.MOUSEOUT, XSM.topbar.hover_text_link, null, function(e) {
					if (!$(e.currentTarget).hasClass(XSM.effects.disabled) ) XBS.layout.decay_topbar_hover();
				});
			},
			window_resize_listener: function () {
				if (XBS.cfg.is_splash) {
					$(window).on("resize", XBS.splash.render);
				}
				return true;
			}
		},
		assert_aspect_ratio: function (targets) {
			$(targets).each(function () {
				$(this).removeAttr("style");
				var data = $(this).data("aspectRatio");
				var ratio = Number(data.x) / Number(data.y);
				var respect = data.respect;

				/* make sure the ratio is indeed a ratio */
				if (!isInt(ratio) && !isFloat(ratio)) {
					var ratioVals = ratio.split("/");
					if (ratioVals.length !== 2) throw "Invalid value for argument 'ratio'; must be int, float or string matching n/m";
					ratio = Number(ratioVals[0]) / Number(ratioVals[1]);
				}

				var dimensions = {width: $(this).innerWidth(), height: $(this).innerHeight()};
				if (respect != "y") {
					dimensions.height = dimensions.width / ratio;
				} else {
					dimensions.width = dimensions.height * ratio;
				}
				$(this).css(dimensions);
			});
			return true;
		},
		activize: function (element) {
			if (isEvent(arguments[0])) element = element.currentTarget;
			if ($(element).hasClass(XSM.effects.inactive)) {
				$(element).removeClass(XSM.effects.inactive)
					.addClass(XSM.effects.active)
					.siblings(XSM.global.active_list_item)
					.each(function () {
						$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
					});
			}
		},
		detach: function (element) {
			var isStatic = $(element).data('static');
			var height = $(element).innerHeight();
			$(element).css({height: height});
			if (isStatic) XBS.layout.fasten(element);
			return $(element);
		},
		dismiss_modal: function(modal, action) {
			$(XSM.modal.primary).addClass(XSM.effects.slide_up);
			$(XSM.modal.flash).addClass(XSM.effects.slide_up);
			$(XSM.modal.splash).addClass(XSM.effects.slide_up);
			$(XSM.modal.order).hide('clip');
			setTimeout(function() {
				$(XSM.modal.overlay).addClass(XSM.effects.fade_out);
				setTimeout(function() { $(XSM.modal.overlay).hide(); }, 300);
			}, 300);
			if (action) {
				switch (action) {
					case "reset-user-activity":
						$(XSM.menu.user_activity_panel).children().each(function() {
							if ( $(this).hasClass(XSM.effects.active) ) {
								$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
							}
							if ($(this).hasClass(XSM.effects.active_by_default) ) $(this).addClass(XSM.effects.active);
						});
						break;
					case "unstash":
						XBS.menu.init();
						XBS.menu.unstash_menu();
						break;
				}
			}

			return true;
		},
		fasten: function (selector) {
			//todo: error handling & logic for objects vs arrays
			var selectors = ( isArray(selector) ) ? selector : [selector];
			for (var i in selectors) {
				var sel = selectors[i];
				var offset = $(sel).offset();

				var dims = {width:Math.floor($(sel).outerWidth() + px_to_int($(sel).css("padding-left"))),
				            height:Math.floor($(sel).outerHeight() + px_to_int($(sel).css("padding-top")))};
				var styles = {position: "fixed", top: offset.top, left: offset.left, height: dims.height, width: dims.width};
				pr(styles)
				$(sel).css(styles).addClass("fastened");
			}
			return  (isArray(selector) ) ? selector : $(selector);
		},
		launch_route: function (route_str) {
			pr(route_str, "launch_route(route)");

			var route_path = route_str.split("/")
			if ( !route_path[0] in XBS.routes ) return false;
			var route = jQuery.extend({}, XBS.routes[route_path[0]], true);
			route.init_instance(route_path.slice(1));
			if (route.stop_propagation) e.stopPropagation();
			var launch_delay;
			var expose_by_removing = false;
			if (route.stash) launch_delay = 900;
			if (route.overlay) launch_delay = 300;
			if (route.modal == XSM.modal.primary) expose_by_removing = XSM.effects.slide_up;
			if (route.modal == XSM.modal.splash) expose_by_removing = XSM.effects.slide_up;

			// >>> RESIZE & POSITION PRIMARY IF NEEDED <<<
			XBS.layout.resize_modal(route.modal)

			// >>> LAUNCH MODALS IF REQUIRED<<<
			if (route.url) {
				var launch_triggered = false;
				try {
				$.ajax({
					type: "type" in route.url ? route.url.type : C.POST,
					url: route.url.url,
					data: "data" in route.url ? route.url.data : null,
					statusCode: {
						403: function() {
							XBS.layout.launch_route(XBS.routes.fail_flash);
							if (!launch_triggered) {
								launch_triggered = true;
								$(route).trigger("route_launched","403_FORBIDDEN")
							}
						}
					},
					success: function (data) {
					// >>> DO PRE-LAUNCH EFFECTS <<<
						if (route.stash) XBS.menu.stash_menu();
						if (route.overlay) $(XSM.modal.overlay).show().removeClass(XSM.effects.fade_out);
						setTimeout( function() {
							pr([route, data]);
							$(route.modal_content).html(data)
							if (expose_by_removing) $(route.modal).removeClass(expose_by_removing);
							$(route).trigger("route_launched", data);
						}, launch_delay);
					},
					fail: function() {
							if ("fail_callback" in route) {
								route.fail_callback();
							} else {
								XBS.layout.launch_route(XBS.routes.fail_flash);
							}
							if (!launch_triggered) {
								launch_triggered = true;
								$(route).trigger("route_launched","FAIL_TRIGGER")
							}
						},
					always: function() {
						if (!launch_triggered) {
							pr("getting heeeere");
							$(route).trigger("route_launched","FALLBACK_TRIGGER")
						}
					}
				});
				} catch (e) {
					XBS.layout.launch_route(XBS.routes.fail_flash);
					if (!launch_triggered) {
						launch_triggered = true;
						$(route).trigger("route_launched","CAUGHT_EXCEPTION")
					}
				}
			} else { $(route).trigger("route_launched", "NO_AJAX"); }

			return true;
		},
		multi_activize: function (element) {
			if ($(element).hasClass('active')) {
				$(element).removeClass('active').addClass('inactive')
					.children(asClass(XSM.effects.checked)).each(function () {
						$(this).removeClass(XSM.effects.checked).addClass(XSM.effects.unchecked);
					});
			} else if ($(element).hasClass('inactive')) {
				$(element).removeClass('inactive').addClass('active')
					.children(asClass(XSM.effects.unchecked)).each(function () {
						$(this).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
					});
			}
		},
		ready_loading_screen: function () {
			if (XBS.cfg.developmentMode) {
				XBS.fn.layout.toggle_loading_screen();
				return true;
			}
			if (XBS.cfg.is_splash) {
				$(XSM.load.pizzaLoaderGIF).fadeOut(500, function () {
					$(XSM.load.loadingMessage).hide("slide", {direction: "right"}, 300, function () {
						$(XSM.load.readyMessage).show("slide", 300, function () {
							$(XSM.load.dismissLSButton).fadeToggle();
						});
					});
				});
			} else {
				$(XSM.load.pizzaLoaderGIF).fadeOut(500, function () {
					XBS.layout.toggle_loading_screen();
				});
			}
		},
		resize_modal: function(modal) {
			if (!modal) return;
			var modal_width;
			var modal_max_height;
			var modal_left;
			var modal_top;
			if (modal == XSM.modal.primary || modal == XSM.modal.splash) {
				modal_width = 1200 / 12 * 8;
				modal_max_height = 0.8 * $(window).innerHeight();
				modal_top = 0.2 * $(window).innerHeight();
				var pm_width = 0.8 * $(window).innerWidth();
				if (pm_width > modal_width) {
					modal_left = ($(window).innerWidth() - modal_width) / 2;
				} else {
					modal_left = 0.1 * $(window).innerWidth();
				}
				if (pm_width < modal_width) modal_width = pm_width;
			}
			if (modal == XSM.modal.flash) {
				modal_width = 40 * C.REM;
				modal_left = (window.innerWidth / 2) - ( modal_width / 2);
				modal_max_height = "default";
				modal_top = 2 * C.REM;
			}

			$(modal).css({
				top: modal_top,
				left: modal_left,
				width: modal_width,
				maxHeight: modal_max_height
			});
		},
		toggle_float_label: function (label, state) {
			if (state == C.SHOW) $(XSM.menu.float_label).html(str_to_upper(label)).addClass(XSM.effects.exposed);
			if (state == C.HIDE) $(XSM.menu.float_label).removeClass(XSM.effects.exposed).html('');

			return true;
		},
		toggle_loading_screen: function () { $(XSM.global.loadingScreen).fadeToggle(); },
		toggle_topbar_hover_text: function(hover_text, state) {
			if ($(XSM.topbar.hover_text_label_outgoing).html() == hover_text) return;
			pr([hover_text, state], "toggle_topbar_hover_text(hover_text, state)");
			$(XSM.topbar.hover_text_label_outgoing).removeClass("decay").addClass(XSM.effects.slide_right);
			$(XSM.topbar.hover_text_label_incoming).html(hover_text).removeClass(XSM.effects.true_hidden);
			setTimeout(function() {
				$(XSM.topbar.hover_text_label_incoming).removeClass(XSM.effects.slide_left);
				setTimeout(function() {
					setTimeout(function() {
						$(XSM.topbar.hover_text_label_outgoing).remove();
						setTimeout(function() {
							$(XSM.topbar.hover_text_label_incoming).removeClass("incoming").addClass("outgoing")
							$(XSM.topbar.hover_text_label).append("<span class='incoming slide-left true-hidden'></span>");
						}, 10);
					}, 280);
				}, 10);
			}, 10);
		},
		decay_topbar_hover: function() {
			var id = (new Date).getTime();
			$(XSM.topbar.hover_text_label_outgoing).addClass("decay "+id);
			setTimeout(function() {
				$(XSM.topbar.hover_text_label_outgoing + ".decay."+id).addClass(XSM.effects.fade_out);
				setTimeout(function() {
					$(XSM.topbar.hover_text_label_outgoing + ".decay."+id).replaceWith(
						"<span class='outgoing'>Halifax loves pizza and we love halifax!</span>");}, 300);
			}, 1000);
			return true;
		}
	},
	menu: {
		init: function () {
			var init_ok = true;
			if (XBS.cfg.page_name == C.MENU) {
				init_ok = XBS.fn.execInitSequence({"XBS.menu.jq_binds": XBS.menu.jq_binds});
				try {
					if (!XBS.data.current_orb_card) XBS.menu.load_from_cart($(XSM.menu.active_orb).data('orb'));
				} catch (e) {
					init_ok.state = false;
					init_ok.message = "current_orb_card not set; nothing loaded from cart";
				}
				XBS.menu.update_orb_opt_filters_list(C.CHECK);

				if (!is_mobile() ) {
					pr("running");
					var page_content_height = $(XSM.global.page_content).innerHeight();
					$(XSM.global.footer).css({
						top:$(XSM.global.page_content).innerHeight(),
					});
					XBS.layout.fasten(XSM.menu.self).css({overflow:"hidden"});
				}
			}

			return  init_ok
		},
		jq_binds: {
			has_init_sequence: true,
			hide_orb_card_back_face_elements: function () {
				$(XSM.menu.orb_opt).hide();
				$(XSM.menu.orb_opts_menu_header).hide().removeClass(XSM.effects.hidden);
				return true;
			},
			bind_order_api: function () {
				pr("there");
				/** add item to order */
				$(C.BODY).on(C.CLK, XSM.menu.add_to_cart, null, function (e) {
					pr("here");
					var data = $(e.currentTarget).data('orbId');
					XBS.menu.configure_orb(data.orbId, data.priceRank)
				});

				/** cancel order button */
				$(C.BODY).on(C.CLK, XSM.menu.cancel_order_button, null, XBS.menu.reset_orb_card_stage);

				/** confirm order button */
				$(C.BODY).on(C.CLK, XSM.menu.confirm_order_button, null, XBS.menu.add_to_cart);
				return true;
			},
			bind_orbsize_update: function () {
				$(C.BODY).on(C.CLK, XSM.menu.orb_size_button, null, function (e) {
					XBS.menu.price_rank_update($(e.currentTarget).data('priceRank'));
				});

				return true;
			},
			bind_topping_methods: function () {
				/** icon toggling */
				$(C.BODY).on(C.CLK, XSM.menu.orb_opt_icon, null, function (e) {
					if ($(e.currentTarget).hasClass(XSM.effects.enabled)) {
						e.stopPropagation();
						XBS.menu.toggle_orb_opt_icon(e.currentTarget, true);
					}
				});

				/** orb_opt toggling */
				$(C.BODY).on(C.CLK, XSM.menu.orb_opt, null, function (e) {
					XBS.menu.toggle_orb_opt(e.currentTarget, true);
				});

				/** orb_opt filtering (check all) */
				$(C.BODY).on(C.CLK, XSM.menu.orb_opt_filter_all, null, function (e) {
					e.stopPropagation();
					XBS.menu.filter_orb_opts(false, $(e.currentTarget).data('all'));
				});

				/** orb_opt filtering (individual) */
				$(C.BODY).on(C.CLK, XSM.menu.orb_opt_filter, null, function (e) {
					e.stopPropagation();
					XBS.menu.filter_orb_opts(e.currentTarget);
				});

				return true;
			},
			order_update_listeners: function () {
				/** order form manual change */
				$(C.BODY).on("change", XSM.menu.orb_order_form, null, XBS.cart.configure);

				/** order form update */
				$(C.BODY).on(C.ORDER_FORM_UPDATE, null, null, XBS.menu.update_orb_configuration_ui);

				/** order UI update */
				$(C.BODY).on(C.ORDER_UI_UPDATE, null, null, XBS.menu.update_orb_form);

				return true;
			}
		},
		add_to_cart: function () {
			// todo: ajax fallbacks
			$.ajax({
				type: 'POST',
				url: "orders/add_to_cart",
				data: $(XSM.menu.orb_order_form).serialize(),
				success: function (data) {
					pr(data);
					data = JSON.parse(data);
					if (data.success == true) {
						XBS.cart.add_to_cart();
						$(C.BODY).append(function () {
							return $("<div/>").addClass([stripCSS(XSM.modal.order), XSM.effects.success].join(" "))
								.html("<h1>Success!</h1>").hide();
						});
						$("#top-bar-view-cart").removeClass(XSM.effects.disabled)
							.data('hover_text', "View Your Cart");
						$(XSM.modal.order).show('clip');
					}
				}
			});
		},
		configure_orb: function (orb_id, price_rank) {
			if (isEvent(arguments[0])) {
				var data = $(arguments[0].currentTarget).data();
				orb_id = data.orbId;
				price_rank = data.priceRank;
			}

			$(XSM.menu.orb_size_button).each(function () {
				if ($(this).data('priceRank') == price_rank) XBS.layout.activize(this);
			});
			XBS.menu.show_orb_card_back_face()
		},
		reset_orb_opt_filters: function () {
			$(XSM.menu.orb_opt_filter).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
			$(XSM.menu.orb_opt_filter_span).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
			XBS.menu.update_orb_opt_filters_list(C.CHECK);
			return true;
		},
		filter_orb_opts: function (target, all) {
			pr([target, all], "filter_orb_opts(target, all)");
			if (all == C.CHECK) {
				$(XSM.menu.orb_opt_filter).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
				$(XSM.menu.orb_opt_filter_span).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
				XBS.menu.update_orb_opt_filters_list(C.CHECK);
			}

			if (all == C.UNCHECK) {
				$(XSM.menu.orb_opt_filter).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
				$(XSM.menu.orb_opt_filter_span).removeClass(XSM.effects.checked).addClass(XSM.effects.unchecked);
				XBS.menu.update_orb_opt_filters_list(C.UNCHECK);
				pr(XBS.data.orb_opt_filters, "filters post uncheck all");
			}

			if (target) {
				var filter = $(target).data('filter');
				if (XBS.data.orb_opt_filters[filter] == C.CHECK) {
					XBS.data.orb_opt_filters[filter] = C.UNCHECK;
				} else {
					XBS.data.orb_opt_filters[filter] = C.CHECK;
				}
				var checkbox = $(target).children(XSM.menu.orb_opt_filter_span)[0];
				if ($(target).hasClass(XSM.effects.active)) {
					$(target).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
					$(checkbox).removeClass(XSM.effects.checked).addClass(XSM.effects.unchecked);
				} else {
					$(target).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
					$(checkbox).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
				}
			}
			$(XSM.menu.orb_opt).addClass(XSM.effects.fade_out);
			setTimeout(function () {
				$(XSM.menu.orb_opt).show();
				$.map($(XSM.menu.orb_opt), function (orb_opt, index) {
					$.map($(orb_opt).data('flags'), function (flag) {
						if (flag in XBS.data.orb_opt_filters) {
							if (XBS.data.orb_opt_filters[flag] == C.UNCHECK) {
								$(orb_opt).hide();
							}
						}
					});
				});
				setTimeout(function () { $(XSM.menu.orb_opt).removeClass(XSM.effects.fade_out); }, 30);
			}, 300);

			return true;
		},
		/**
		 *
		 * @param orb_id
		 * @returns {boolean}
		 */
		load_from_cart: function (orb_id) {
			if (!XBS.cart.initialized) return false;

			if (XBS.cart.has_configuration_started(orb_id)) {
				$(XSM.menu.orb_order_form_inputs).each(function () {
					$(this).val(XBS.cart.orb_attr(orb_id, $(this).attr('id'), true));
				});
				$(C.BODY).trigger(C.ORDER_FORM_UPDATE);
			}

			return true;
		},
		price_rank_update: function (price_rank) {
			$(XSM.menu.orb_order_form_price_rank).val(price_rank);
			return true;
		},
		/**
		 * Updates bottom menu to reflect choices of clicked orbcat in top menu.
		 *
		 * @param orbcat_id
		 * @param orbcat_name
		 */
		refresh_active_orbcat_menu: function (orbcat_id, orbcat_name) {
			pr([orbcat_id, orbcat_name], "refresh_active_orbcat_menu()");
			// todo: fallback on ajax fail
			var url = "menu" + C.DS + orbcat_id
			if (XBS.cfg.root.length != 0) url = C.DS + XBS.cfg.root + C.DS + url;

			$.get(url,function (data) {
				var active_orbcat_menu = $.parseHTML(data)[1];
				var orb_route = $($(active_orbcat_menu).find(XSM.menu.active_orbcat_item)[0]).data('route');
				var active_orb_id = orb_route.split(C.DS)[1];


				// >>> TOGGLE MENU HEADER; alternates rotating front-to-back or back-to-front <<<
				if ($(XSM.menu.active_orb_name_3d_context).hasClass(XSM.effects.flipped_x)) {
					$(XSM.menu.active_orb_name_front_face).html(orbcat_name);
					$(XSM.menu.active_orb_name_3d_context).removeClass(XSM.effects.flipped_x);
				} else {
					$(XSM.menu.active_orb_name_back_face).html(orbcat_name);
					$(XSM.menu.active_orb_name_3d_context).addClass(XSM.effects.flipped_x);
				}

				// >>> HIDE INCOMING ORBCATS; hide new data so they fade-in when shown <<<
				$(active_orbcat_menu).find(XSM.menu.active_orbcat_item).each(function () {
					$(this).addClass(XSM.effects.fade_out);
				});
				// >>> HIDE OUTGOING ORBCATS; hide all active orbcats in orb card stage menu before replacing them <<<
				$(XSM.menu.active_orbcat_item).addClass(XSM.effects.fade_out);
				$(XSM.menu.orbcat_menu_title_subtitle).addClass(XSM.effects.fade_out);

				XBS.menu.refresh_orb_card_stage(active_orb_id);

				setTimeout(function () {
					$(XSM.menu.active_orbcat_item).remove()
					setTimeout(function () {
						$(XSM.menu.orbcat_menu_title_subtitle).html(orbcat_name).removeClass(XSM.effects.fade_out);
						$(active_orbcat_menu).find(XSM.menu.active_orbcat_item).each(function () {
							$(this).appendTo(XSM.menu.orb_card_stage_menu);
						});
						setTimeout(function () {$(XSM.menu.active_orbcat_item).removeClass(XSM.effects.fade_out);}, 30);
					}, 300);
				}, 300);
			}).then(function () { $(C.BODY).trigger(C.ORB_CARD_REFRESH); });
		},
		/**
		 * Updates orb card when an orb is clicked in the bottom menu.
		 *
		 * @param orb_card_id
		 */
		refresh_orb_card_stage: function (orb_card_id) {
			pr(orb_card_id, "refresh_orb_card_stage");
			// todo: fallback on ajax fail
			XBS.data.current_orb_card = orb_card_id;
			var url = "menuitem" + C.DS + orb_card_id
			if (XBS.cfg.root.length != 0) url = C.DS + XBS.cfg.root + C.DS + url

			$.get(url, function (data) {
				var orb_card_stage = $.parseHTML(data)[0];
				// >>> EXTRACT ORB OPTS <<<
				var orb_opts = $($(orb_card_stage).find(XSM.menu.orb_opt_container)[0]).find(XSM.menu.orb_opt);
				var filters = $(orb_card_stage).find(XSM.menu.orb_opt_filters)[0];
				var replace_time = 0;

				// >>> REMOVE ORB OPTS BEFORE EXTRACTION <<<
				$(orb_card_stage).find(XSM.menu.orb_opt_container)[0].remove();

				// >>> HIDE INCOMING ORBOPTS <<<
				$(orb_opts).each(function () { $(this).hide().addClass(XSM.effects.fade_out);});

				// >>> HIDE INCOMING ORBCARD SPECIFICS & ORB OPTS <<<
				$(orb_card_stage).find(XSM.menu.orb_card_content_container).each(function () {
					$(this).addClass(XSM.effects.fade_out);
				});
				$($(orb_card_stage).find(XSM.menu.orb_opt_container)[0]).find(XSM.menu.orb_opt).each(function () {
					$(this).addClass([XSM.effects.fadeOut, XSM.effects.true_hidden].join(" "));
				});
				// >>> UNFLIP ORBCARD IF FLIPPED <<<
				if ($(XSM.menu.orb_card_3d_context).hasClass(XSM.effects.flipped_y)) {
					replace_time = 950;
					XBS.menu.show_orb_card_front_face();
				}
				setTimeout(function () {
					// >>> FADE OUT OUTGOING ORBCARD CONTENT <<<
					$(XSM.menu.orb_card_content_container).addClass(XSM.effects.fade_out);
					setTimeout(function () {
						// >>> REPLACE ORBCARD <<<
						$(XSM.menu.orb_card_stage).replaceWith(orb_card_stage);
						setTimeout(function () { $(XSM.menu.orb_card_content_container).removeClass(XSM.effects.fade_out);}, 30);
						// >>> REPLACE ORBOPTS (REMAIN HIDDEN/ FADED) <<<
						$(XSM.menu.orb_opt).remove();
						$(XSM.menu.orb_opt_filters).replaceWith(filters);
						XBS.menu.update_orb_opt_filters_list(C.CHECK);
						$(orb_opts).each(function () { $(this).appendTo(XSM.menu.orb_card_stage_menu); });
						XBS.menu.load_from_cart(orb_card_id);
					}, 300);
				}, replace_time);
			});
		},
		reset_orb_card_stage: function () {
			pr("<no args>", "reset_orb_card_stage()");
			$(XSM.menu.orb_order_form_price_rank).val(0);
			$(XSM.menu.orb_size_button).each(function () {
				$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
			});
			$(XSM.menu.orb_order_form_quantity).val(1);
			$(XSM.menu.orb_order_form_prep_instrux).val("");
			XBS.cart.cancel_config( $(XSM.menu.orb_order_form_orb_id).val() );
			XBS.menu.show_orb_card_front_face();
			$(C.BODY).trigger(C.ORDER_FORM_UPDATE);
			$(C.BODY).trigger(C.ORDER_UI_UPDATE);
		},
		set_order_method: function(method) {
			XBS.data.order_method = method;
			$(XSM.menu.user_activity_panel_items).each(function() {
				var route = $(this).data('route');
				if (route) {
					if (route.split(C.DS)[2] == method)  {
						$(this).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
					} else {
						$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
					}
				}
			});
		},
		show_orb_card_front_face: function () {
			pr("<no args>", "show_orb_card_front_face");
			// >>> START FLIP & FADE OUT ORBOPTS TOGETHER <<<
			$(XSM.menu.orb_card_3d_context).removeClass(XSM.effects.flipped_y);
			$(XSM.menu.orb_opt).addClass(XSM.effects.fade_out);
			setTimeout(function () {
				// >>> SLIDE OUT FILTER HEADER, HIDE ORBOPTS <<<
				$(XSM.menu.orb_opts_menu_header).addClass(XSM.effects.slide_right);
				$(XSM.menu.orb_opt).hide();
				setTimeout(function () {
					// >>> RESET FILTERS & SHOW ORBCAT ITEMS <<<
					XBS.menu.reset_orb_opt_filters();
					$(XSM.menu.active_orbcat_item).show()
					setTimeout(function () { $(XSM.menu.active_orbcat_item).removeClass(XSM.effects.fade_out);}, 30);
					setTimeout(function () {
						// >>> SLIDE IN ORBCAT ITEMS HEADER; RESTORE 'ACTIVIZING' <<<
						$(XSM.menu.orb_opts_menu_header).hide()
						$(XSM.menu.active_orbcat_menu_header).show();
						setTimeout(function () { $(XSM.menu.active_orbcat_menu_header).removeClass(XSM.effects.slide_left);}, 30);
						$(XSM.menu.orb_card_stage_menu).addClass(XSM.effects.activizing);
					}, 300);
				}, 300);
			}, 300);

			return true;
		},
		show_orb_card_back_face: function () {
			// >>> START FLIP & FADE OUT ORBOPTS TOGETHER <<<
			$(XSM.menu.orb_card_3d_context).addClass(XSM.effects.flipped_y);
			$(XSM.menu.active_orbcat_item).addClass(XSM.effects.fade_out);
			setTimeout(function () {
				// >>> SLIDE OUT ORBCAT ITEMS HEADER, HIDE ORBCAT ITEMS <<<
				$(XSM.menu.active_orbcat_menu_header).addClass(XSM.effects.slide_left);
				$(XSM.menu.active_orbcat_item).hide();
				setTimeout(function () {
					// >>> SHOW & FADE IN ORBOPTS<<<
					$(XSM.menu.orb_opt).show()
					setTimeout(function () {$(XSM.menu.orb_opt).removeClass(XSM.effects.fade_out);}, 30);
					setTimeout(function () {
						// >>> SLIDE IN FILTER HEADER; REMOVE 'ACTIVIZING' <<<
						$(XSM.menu.active_orbcat_menu_header).hide();
						$(XSM.menu.orb_opts_menu_header).show();
						setTimeout(function () { $(XSM.menu.orb_opts_menu_header).removeClass(XSM.effects.slide_right);}, 30);
						$(XSM.menu.orb_card_stage_menu).removeClass(XSM.effects.activizing);
					}, 300);
				}, 300);
			}, 300);
		},
		stash_menu: function () {
			$(XSM.menu.user_activity_panel).addClass(XSM.effects.slide_up);
			setTimeout(function() {
				$(XSM.menu.orb_card_wrapper).addClass([XSM.effects.slide_left, XSM.effects.fade_out].join(" "));
				setTimeout(function() {
					$(XSM.menu.orbcat_menu).addClass([XSM.effects.slide_right, XSM.effects.fade_out].join(" "));
				}, 300);
			}, 300);
		},
		unstash_menu: function () {
			$(XSM.menu.orbcat_menu).removeClass([XSM.effects.slide_right, XSM.effects.fade_out].join(" "));
			setTimeout(function() {
				$(XSM.menu.orb_card_wrapper).removeClass([XSM.effects.slide_left, XSM.effects.fade_out].join(" "));
				setTimeout(function() {
					$(XSM.menu.user_activity_panel).removeClass(XSM.effects.slide_up);
				}, 300);
			}, 300);
		},
		toggle_orb_opt: function (element, trigger_update) {

			if ($(element).hasClass(XSM.effects.active)) {
				$(element).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
				$(element).find(XSM.menu.orb_opt_icon).each(function () {
					$(this).removeClass(XSM.effects.enabled).addClass(XSM.effects.disabled);
				});
			} else {
				pr($(element));
				$(element).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
				$(element).find(XSM.menu.orb_opt_icon).each(function () {
					$(this).removeClass(XSM.effects.disabled).addClass(XSM.effects.enabled);
				});
			}
			// let animations complete before walking DOM for smoothness
			if (trigger_update === true) setTimeout(function () { $(C.BODY).trigger(C.ORDER_UI_UPDATE);}, 300);
			return true;
		},
		toggle_orb_opt_icon: function (element, trigger_update) {
			XBS.layout.activize(element); // wraps activize so event propagation can be stopped
			if (trigger_update === true) $(C.BODY).trigger(C.ORDER_UI_UPDATE);
			return true;
		},
		update_orb_form: function () {
			var tiny_toppings_list = $("<ul/>").addClass(selToStr(XSM.menu.tiny_orb_opts_list));

			//$(XSM.menu.orb_opt_weight).each(function () { $(this).val(-1);});
			$(XSM.menu.orb_opt).each(function () {
				var topping_name = $(this).data("name");
				var weight = -1;
				if ($(this).hasClass(XSM.effects.active)) {
					weight = $($(this).find(XSM.menu.orb_opt_icon_active)[0]).data(C.WEIGHT);
					$("<li/>").addClass(stripCSS(XSM.menu.tiny_orb_opts_list_item))
						.append("<span class='tiny-opt-label'>" + topping_name + "</span>")
						.append(C[weight])
						.appendTo(tiny_toppings_list);
				}

				$(XSM.generated.order_form_order_opt($(this).data('id'))).val(weight);
			});

			$(XSM.menu.tiny_orb_opts_list_wrapper).html(tiny_toppings_list);
			XBS.cart.configure();
		},

		/**
		 *  updates visual state of orb card toppings & icons
		 *  to respect current state of the underlying form
		 */
		update_orb_configuration_ui: function () {
			$(XSM.menu.orb_order_form_orb_opts).each(function () {
				var weight_dict = {D: true, F: true, L: true, R: true};
				var weight = $(this).val();
				var opt_id = XSM.generated.orb_opt_id( XBS.cart.from_attribute_id_str($(this).attr('id')).opt_id );
				if (weight in weight_dict) {
					XBS.menu.toggle_orb_opt(opt_id, false);
					var icon = XSM.generated.orb_opt_icon(opt_id, weight);
					XBS.menu.toggle_orb_opt_icon(icon, false);
				}
			});
			var price_rank = $(XSM.menu.orb_order_form_price_rank).val()
			XBS.layout.activize($(XSM.menu.orb_size_button + "[data-price-rank=" + price_rank + "]"));
			return true;
		},
		update_orb_opt_filters_list: function (target_state) {
			if (target_state != C.CHECK && target_state != C.UNCHECK) target_state = C.CHECK;
			XBS.data.orb_opt_filters = {}
			$(XSM.menu.orb_opt_filter).each(function () {
				XBS.data.orb_opt_filters[$(this).data('filter')] = target_state;
			});
			return true;
		}
	},
	cart: {
		orbs: {}, // CONFIRMED BY SERVER
		configuring: {orbopts:{}}, // ORB_ID: {CONFIG}
		initialized: false,
		empty_config: {
			quantity: null,
			orbopts: {},
			preparation_instructions: null,
			price_rank: null
		},
		attributes_object: {
			str: null,
			is_id: false,
			is_quanity: false,
			is_prep_instrux: false,
			is_orbopt: false,
			opt_id: null
		},

		init: function (cart_details) {
			pr(cart_details, "cart details");
			try {
				XBS.cart.orbs = Object.keys(cart_details.OrderItem).length > 0 ? cart_details.OrderItem : {};
				XBS.cart.initialized = true;
				XBS.cart.configuring = {};
				return true;
			} catch (e) {
				return null
			}
		},

		tally: function () {
		},
		cancel_config: function(orb_id) { XBS.cart.configuring[orb_id] = jQuery.extend({}, XBS.cart.empty_config); },
		has_configuration_started: function(orb_id) { return XBS.cart.has_orb(orb_id, true); },
		has_orb: function (orb_id, in_configuring) {
			return in_configuring ? orb_id in XBS.cart.configuring : orb_id in XBS.cart.orbs;
		},
		from_attribute_id_str: function (attribute_str) {
			var attr_ob = jQuery.extend({}, XBS.cart.attributes_object);
			attribute_str = camelcase_to_pep8(attribute_str.replace("OrderOrb", ""));
			if (attribute_str == "id") {
				attr_ob.is_id = true;
				attr_ob.str = "id";
			}
			if (attribute_str == "quantity") {
				attr_ob.is_quantity = true;
				attr_ob.str = "quantity";
			}
			if (attribute_str == "preparation_instructions") {
				attr_ob.is_prep_instrux = true;
				attr_ob.str = "preparation_instructions";
			}
			if (attribute_str.substr(0, 6) == "orbopt") {
				attr_ob.is_orbopt = true;
				attr_ob.opt_id = attribute_str.split("_")[1];
				attr_ob.str = "orbopt";
			}
			return attr_ob;
		},
		add_to_cart: function(orb_id) {
			if ( !XBS.cart.has_orb(orb_id, true) ) {
				XBS.cart.configuring[orb_id] =jQuery.extend({}, XBS.cart.empty_config);
			}
			return true;
		},
		orb_attr: function (orb_id, attribute, in_configuration) {
			if (!XBS.cart.has_orb(orb_id, in_configuration) ) return false;
			if (!attribute) return false;
			attribute = XBS.cart.from_attribute_id_str(attribute)
			if (attribute.is_id) return orb_id;
			var context = in_configuration ? XBS.cart.configuring : XBS.cart.orbs;
			if (attribute.str in context[orb_id]) return context[orb_id][attribute.str]

			if (attribute.is_orbopt) {
				if (attribute.opt_id in context[orb_id]["orbopts"]) {
					try {
						if ('weight' in  context[orb_id].orbopts[attribute.opt_id]) {
							return context[orb_id].orbopts[attribute.opt_id]['weight']; // orbs
						}
					} catch(e) {
						return context[orb_id].orbopts[attribute.opt_id]; // configuring
					}
				} else {
					return -1;
				}
			}
			return false;
		},
		configure: function () {
//			try {
				var orb_id = $(XSM.menu.orb_order_form_orb_id).val();
				if ( !(orb_id in XBS.cart.configuring) ) XBS.cart.configuring[orb_id] = jQuery.extend({}, XBS.cart.empty_config);
				XBS.cart.configuring[orb_id].quantity = $(XSM.menu.orb_order_form_quantity).val();
				XBS.cart.configuring[orb_id].price_rank = $(XSM.menu.orb_order_form_price_rank).val();
				XBS.cart.configuring[orb_id].preparation_instructions = $(XSM.menu.orb_order_form_prep_instrux).val();
				$(XSM.menu.orb_order_form_orb_opts).each(function () {
					var opt_id = XBS.cart.from_attribute_id_str($(this).attr('id')).opt_id;
					XBS.cart.configuring[orb_id].orbopts[opt_id] = $(this).val();
				});
			pr("ok");
				return true;
//			} catch(e) {
//				pr(e.message, "XBS.cart.configure", true)
//				return false;
//			}
		}
	},
	splash: {
		init: function () {
			if (!XBS.cfg.is_splash) return true;
			XBS.fn.execInitSequence({"XBS.splash.jq_binds": XBS.splash.jq_binds});
			$(XSM.splash.self).on(C.MOUSEOVER, function () {
				$(XSM.splash.openingDeal).slideDown();
				$(this).unbind(C.HOVER);
			});
			XBS.splash.render();
			return true;
		},
		jq_binds: {
			has_init_sequence: true,
			splash_modal_listener: function () {
				$(XSM.splash.modalLoad).each(function () {
					$(this).on(C.CLK, null, $(this).data(), XBS.splash.modal);
				});

				return true;
			}
		},
		render: function () {

			$(".fastened").attr('style', '').removeClass('fastened');
			$(".detach").attr('style', '');
			$(XSM.splash.order_spacer).css({height: $(XSM.splash.menu_wrapper).innerHeight() * C.ORDER_SPACER_FACTOR});
			$(XSM.splash.menu_spacer).css({height: $(XSM.splash.menu_wrapper).innerHeight() * C.MENU_SPACER_FACTOR});
			XBS.layout.assert_aspect_ratio(XSM.splash.preserve_aspect_ratio);
			return true;
		},
		redirect: function (route) {
			XBS.splash.fold(route);
		},
		modal: function (modalSource) {
			if (isEvent(arguments[0])) modalSource = arguments[0].data.source;

			//window.location.replace(modalSource);
			$(XSM.splash.modalContent).load(modalSource, function () {
				$(XSM.splash.modalWrap).fadeIn(500,function () {
					$(XSM.splash.modal).show('slide');
				}).on(C.CLK, function () {
					$(XSM.splash.modal).slideUp(300, function () {
						$(this).html('');
						$(XSM.splash.modalWrap).fadeOut();
					})
				});
			});
		},
		fold: function (route) {
			$.get("launch-menu", function(data) {
				$(XSM.splash.self).addClass(XSM.effects.stash);
				data = $.parseHTML(data);
				$($(data).find(XSM.menu.self)[0]).addClass(XSM.effects.true_hidden);
				$(data).appendTo(XSM.global.page_content);
				XBS.cfg.page_name = C.MENU;
				XBS.menu.stash_menu();
				$(XSM.menu.self).removeClass(XSM.effects.true_hidden);
				setTimeout(function() {
					$(XSM.splash.self).remove();
					XBS.menu.init();
					XBS.menu.unstash_menu();
//					if (route) setTimeout(function() { XBS.layout.launch_route(route); }, XBS.data.delays.menu_stash_delay);
				}, 1600);
			});

			return true;
		}
	},
	fn: {
		execInitSequence: function (init_list) {
			var meta_sit_rep = {state: true, report: {}};
			for (init_ob_name in init_list) {
				meta_sit_rep.report[init_ob_name] = {};
				var init_ob = init_list[init_ob_name];
				if (!exists(init_ob.has_init_sequence)) {
					throw new TypeError("Object passed to XBS.fn.execInitSequence() does not contain 'has_init_sequence' property.");
				}
				for (fn in init_ob) {
					if (fn != "has_init_sequence") {
						meta_sit_rep.report[init_ob_name][fn] = init_ob[fn]();
						if (meta_sit_rep.report[init_ob_name][fn] !== true) meta_sit_rep.state = false;
					}
				}
			}
			return meta_sit_rep;
		},
		route: function (controller, action) {
			if (controller in XBS.routes) {
				if (action in XBS.routes[controller]) {
					var url = XBS.routes[controller][action] + C.DS + orb_card_id
					if (XBS.cfg.root.length != 0) url = C.DS + XBS.cfg.root + C.DS + url
					return url;
				}
			}
			return false;
		},

		/**
		 * setHost method
		 * @desc  Sets the root directory for AJAX references given <host>
		 * @host <str> string key for host in XBS.data.host_root_dirs
		 * @throws
		 * @returns <boolean>
		 */
		setHost: function (host) {
			if (XBS.data.host_root_dirs[host] == C.UNDEF) {
				throw new ReferenceError("Error: host '{0}' not found in XBS.data.host_root_dirs.".format(host));
			} else {
				XBS.cfg.root = XBS.data.host_root_dirs[host];
			}
			return true;
		},

		/**
		 * sleep method
		 *
		 * @period {integer} duration (ms) for which program should wait
		 * @wakeEvent {mixed} Either an event object or a string name of standard event
		 * @onWake {function} Function to be executed after sleep period has elapsed
		 * @target {mixed} CSS selector string or DOM element to be target of wakeEvent
		 * @returns {boolean}
		 */
		sleep: function (period, wakeEvent, onWake, target) {
			if (wakeEvent == C.UNDEF) wakeEvent = XBS.evnt.wakeFromSleep;
			if (isFunction(onWake)) {
				// confirmed code reaches these lines and all vars are as expected
				if (target == C.UNDEF) target = window;
				$(target).on(wakeEvent.type, onWake);
			}
			setTimeout(function () {$(target).trigger(wakeEvent) }, period);

			return target;
		}
	}
};