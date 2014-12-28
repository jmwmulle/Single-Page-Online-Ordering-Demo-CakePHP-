/**
 * J. Mulle, for XtremePizza, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
var constants = {
	PX: "px",
	CLK: "click",
	CHECK: "check",
	UNCHECK: "uncheck",
	UNDEF: "undefined",
	MOUSEOVER: "mouseover",
	HOVER: "hover",
	MOUSEENTER: "mouseenter",
	MOUSEOUT: "mouseout",
	HIDE: 0,
	SHOW: 1,
	DS: "/",
	BODY: "body",
	ORDER_SPACER_FACTOR: 0.45,
	MENU_SPACER_FACTOR: 0.15,
	MENU: "menu",
	DATABASE: "database",
	SESSION: "session",
	UPDATE_DB: "update_database",
	UPDATE_SESSION: "update_session",
	OPT: "opt",
	CANCEL: "cancel",
	ORB_CARD_REFRESH: "orb_card_refresh",
	ORDER_FORM_UPDATE: "order_form_update",
	ORDER_UI_UPDATE: "order_ui_update",
	F: "",
	D: "&nbsp<span class='icon-double'></span>",
	R: "&nbsp<span class='icon-right-side'></span>",
	L: "&nbsp<span class='icon-left-side'></span>",
	ORDER_MODAL: "order_modal",
	WEIGHT: "weight"
};
var C = constants;


function InitError(raisedError, message) {
	this.prototype = Error.prototype;
	this.name = "InitError";
	this.message = message ? message : "No additional message provided";
	this.raisedError = raisedError;
	this.read = function () {
		if (raisedError.stackHistory != C.UNDEF) {
			this.raisedError.stack = this.raisedError.stackHistory;
		}
		//todo: make this not rely on pr() being defined!!
		pr(this.raisedError.message + ". \n\tWith trace: {0}".format(this.raisedError.stack), "InitError > {0}".format(this.raisedError.name), true);
	};
}


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
		partial_orb_configs: {
		},
		cart: {
		}
	},
	cfg: {
		root: null,
		developmentMode: false,
		minLoadTime: 1
	},
	evnt: {
		wakeFromSleep: eCustom("wakeFromSleep"),
		assetsLoaded: eCustom("assetsLoaded"),
		orb_card_refresh: eCustom(C.ORB_CARD_REFRESH),
		order_form_update: eCustom(C.ORDER_FORM_UPDATE),
		order_ui_update: eCustom(C.ORDER_UI_UPDATE)
	},
	stopwatch: {
		__sw: new Date(),
		reset: function () { XBS.stopwatch.startTime = 0},
		start: function () {
			var now = XBS.stopwatch.__sw.getTime();
			XBS.stopwatch.startTime = now;
			return now;
		},
		read: function () { return XBS.stopwatch.__sw.getTime() - XBS.stopwatch.start;},
		now: function () { return XBS.stopwatch.__sw.getTime();},
		elapsed: function (period) {
			var diff = XBS.stopwatch.now() - XBS.stopwatch.startTime;
			return (period !== C.UNDEF) ? diff > period : diff;
		},
		startTime: 0
	},
	routes: {
		menu: "menu",
		menuitem: "menuitem"
	},

	/*******************************************************************************************************************
	 *                                                                                                                 *
	 *                                                     INIT                                                        *
	 *                                                                                                                 *
	 *******************************************************************************************************************/
	init: function (is_splash, page_name, host, cart) {
		XBS.data.cart = Cart(cart);
		XBS.cfg.page_name = page_name;
		XBS.cfg.is_splash = is_splash === true;
		XBS.fn.setHost(host);
//		try {
		var init_status = { layout: XBS.layout.init(),
							splash: XBS.splash.init(),
							menu: XBS.menu.init()
		};
		pr(init_status, "init status");
		//XBS.cache.init();
//		} catch(e) {
//			if (e instanceof InitError) {
//				e.read();
//			} else {
//				pr(e.message, e.name, true);
//			}
//		}
	},
	cache: {
		preloadDone: false,
		cbDone: false,
		failed: [],
		init: function () {
			return true;
			//todo: dynamically tie load-screen behavior to template/caching situation
			$("img.image-loader.preload").each(function () {
				$(this).cacheImages({
					debug: true,
					url: $(this).data("src"),
					storagePrefix: "xtreme",
					fail: function () {
						XBS.cache.failed.push($(this).data("src"));
					}
				});
			});
			$(window).trigger(XBS.evnt.assetsLoaded);
		}
	},
	layout: {
		init: function () {
//			try {
			var sit_rep = XBS.fn.execInitSequence({"XBS.layout.jq_binds": XBS.layout.jq_binds});
			if (XBS.cfg.is_splash) XBS.layout.detachAnimationTargets();
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
			bindAssetsLoadedListener: function () {
				if (XBS.cfg.developmentMode) return true;
				$(window).on(XBS.evnt.assetsLoaded.type, function () {
//						if (XBS.cfg.execInitSequenced = true) {
					XBS.layout.ready_loading_screen();
//						}
				});
				return true;
			},
			bind_float_menus: function () {
				$(C.BODY).on(C.MOUSEENTER, asClass(XSM.effects.float_label), null, function (e) {
					XBS.layout.toggle_float_label($(e.currentTarget).attr('id'), C.SHOW);
				});
				$(C.BODY).on(C.MOUSEOUT, asClass(XSM.effects.float_label), null, function (e) {
					XBS.layout.toggle_float_label($(e.currentTarget).attr('id'), C.HIDE);
				});

				return true;
			},
			bind_modal_links: function () {
				$(C.BODY).on(C.CLK, XSM.modal.link_order, null, function (e) {
					XBS.layout.modal_link(C.ORDER_MODAL, $(e.currentTarget).data('action'));
				});

				return true;
			},
			bind_splash_links: function () {
				$(C.BODY).on(C.CLK, XSM.splash.splash_link, null, function (e) {
					XBS.splash.redirect($(e.currentTarget).data('url'));
				});
				return true;
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
			if ($(element).hasClass("inactive")) {
				$(element).removeClass('inactive')
					.addClass('active')
					.siblings("li.active")
					.each(function () {
						$(this).removeClass('active').addClass('inactive');
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
		fasten: function (selector) {
			//todo: error handling & logic for objects vs arrays
			var selectors = ( isArray(selector) ) ? selector : [selector];
			for (var i in selectors) {
				var sel = selectors[i];
				var offset = $(sel).offset();
				var dims = [$(sel).outerWidth() + $(sel).css("padding"),
				            $(sel).outerHeight() + $(sel).css("padding")];

				$(sel).css({position: "fixed", top: offset.top, left: offset.left, height: dims.height, width: dims.width}).addClass("fastened");
			}
			return  (isArray(selector) ) ? selector : $(selector);
		},
		modal_link: function (modal_type, action) {
			modal_actions = {
				order_modal: {
					continue_ordering: function () {
						$(XSM.modal.order_modal).hide("clip", 500, "easeInQuart");
					},
					view_order: function () {
						XBS.menu.stash_menu();
						var url = "orders/review"
						setTimeout(function() { XBS.layout.launch_modal(url)}, 500);
					},
					finish_ordering: function () {
						XBS.menu.stash_menu();
					}
				},
				primary_modal: {

				}
			};
			return modal_actions[modal_type][action]();
		},
		launch_modal: function(route) {
			var pm_width = 0.8 * $(window).innerWidth();
			var pm_height = 0.6 * $(window).innerHeight();
			var pm_left = pm_width > 1200 ? ($(window).innerWidth() - 1200) / 2 : 0.1 * $(window).innerWidth();
			if (pm_width > 1200) pm_width = 1200;
			if (pm_height < 600) pm_height = 600;
			$(XSM.global.primary_modal).css({
					top: 0.2 * $(window).innerHeight(),
					left: pm_left,
					width: pm_width,
					height: pm_height
				});
			if (route) {
				$.get(route, function(data) {
					$(XSM.global.primary_modal).html(data).removeClass("up-slid");
				});
			} else {
				$(XSM.global.primary_modal).html(XBS.data.cart).removeClass("up-slid");
			}

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
		toggle_float_label: function (label, state) {
			if (state == C.SHOW) $(XSM.menu.float_label).html(str_to_upper(label)).addClass(XSM.effects.exposed);
			if (state == C.HIDE) $(XSM.menu.float_label).removeClass(XSM.effects.exposed).html('');

			return true;
		},

		toggle_loading_screen: function () { $(XSM.global.loadingScreen).fadeToggle(); }
	},
	menu: {
		init: function () {
			var init_ok = true;
			if (XBS.cfg.page_name == C.MENU) {
				init_ok = XBS.fn.execInitSequence(
					{"XBS.menu.jq_binds": XBS.menu.jq_binds,
					"XBS.menu.data_structures": XBS.menu.data_structures});
				try {
					if (!XBS.data.current_orb_card) XBS.menu.load_from_cart($(XSM.menu.active_orb).data('orb'));
				} catch (e) {
					init_ok.state = false;
					init_ok.message = "current_orb_card not set; nothing loaded from cart";
				}
			}
			return  init_ok
		},
		data_structures: {
			has_init_sequence: true,
			update_orb_opt_filters_list: function(target_state) {
				if (target_state != C.CHECK && target_state != C.UNCHECK) target_state = C.CHECK;
				XBS.data.orb_opt_filters = {}
				$(XSM.menu.orb_opt_filter).each(function() {
					XBS.data.orb_opt_filters[$(this).data('filter')] = target_state;
				});
				return true;
			}
		},
		jq_binds: {
			has_init_sequence: true,
			hide_orb_card_back_face_elements: function() {
				$(XSM.menu.orb_opt).hide();
				$(XSM.menu.orb_opts_menu_header).hide().removeClass(XSM.effects.hidden);
				return true;
			},
			bind_order_api: function () {
				/** add item to order */
				$(C.BODY).on(C.CLK, XSM.menu.add_to_cart, null, function (e) {
					var data = $(e.currentTarget).data('orbId');
					XBS.menu.configure_orb(data.orbId, data.priceRank)
				});

				/** cancel order button */
				$(C.BODY).on(C.CLK, XSM.menu.cancel_order_button, null, XBS.menu.reset_orb_card_stage);

				/** confirm order button */
				$(C.BODY).on(C.CLK, XSM.menu.confirm_order_button, null, XBS.menu.add_to_cart);
				return true;
			},
			bind_content_refreshing: function () {
				/** orb card stage refreshing */
				$(C.BODY).on(C.CLK, XSM.menu.active_orbcat_item, null, function (e) {
					XBS.menu.refresh_orb_card_stage($(e.currentTarget).data('orb'));
				});

				/** orbcat menu refreshing */
				$(C.BODY).on(C.CLK, XSM.menu.orbcat, null, function (e) {
					var data = $(e.currentTarget).data();
					XBS.menu.refresh_active_orbcat_menu(data.orbcat, data.name);
				});
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
					data = JSON.parse(data);
					if (data.success == true) {
						if (XBS.data.current_orb_card) XBS.menu.reset_orb_card_stage();
						$(C.BODY).append(function() {
							return $("<div/>").addClass([stripCSS(XSM.modal.xtreme_modal), XSM.effects.success].join(" "))
										.html("<h1>Success!</h1>").hide();
						});
						$(XSM.modal.order_modal).show('clip');
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
		reset_orb_opt_filters: function() {
			$(XSM.menu.orb_opt_filter).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
			$(XSM.menu.orb_opt_filter_span).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
			XBS.menu.data_structures.update_orb_opt_filters_list(C.CHECK);
			return true;
		},
		filter_orb_opts: function (target, all) {
			if (all == C.CHECK) {
				$(XSM.menu.orb_opt_filter).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
				$(XSM.menu.orb_opt_filter_span).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
				XBS.menu.data_structures.update_orb_opt_filters_list(C.CHECK);
			}

			if (all == C.UNCHECK ) {
				$(XSM.menu.orb_opt_filter).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
				$(XSM.menu.orb_opt_filter_span).removeClass(XSM.effects.checked).addClass(XSM.effects.unchecked);
				XBS.menu.data_structures.update_orb_opt_filters_list(C.UNCHECK);
				pr(XBS.data.orb_opt_filters, "filters post uncheck all");
			}

			if (target) {
				var filter = $(target).data('filter');
				XBS.data.orb_opt_filters[filter] = flip(XBS.data.orb_opt_filters[filter]);
				var checkbox = $(target).children(XSM.menu.orb_opt_filter_span)[0];
				if ( $(target).hasClass(XSM.effects.active) ) {
					$(target).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
					$(checkbox).removeClass(XSM.effects.checked).addClass(XSM.effects.unchecked);
				} else {
					$(target).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
					$(checkbox).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
				}
			}
			$(XSM.menu.orb_opt).addClass(XSM.effects.fade_out);
			setTimeout( function() {
				$(XSM.menu.orb_opt).show();
				$.map($(XSM.menu.orb_opt), function (orb_opt, index) {
					$.map($(orb_opt).data('flags'), function(flag) {
						if ( flag in XBS.data.orb_opt_filters) {
						if (XBS.data.orb_opt_filters[flag] == C.UNCHECK) {
							$(orb_opt).hide();
						}}
					});
				});
				setTimeout(function() { $(XSM.menu.orb_opt).removeClass(XSM.effects.fade_out); }, 30);
			},300);

			return true;
		},
		 load_from_cart: function (orb_id) {
			if (!XBS.data.cart.initialized) return false;

			if (XBS.data.cart.has_orb(orb_id)) {
				$(XSM.menu.orb_order_form_inputs).each(function () {
					var val = XBS.data.cart.orb_attr(orb_id, $(this).attr('id'));
					$(this).val(val);
				});
				$(C.BODY).trigger(C.ORDER_FORM_UPDATE);
			}

			return true;
		},
		price_rank_update: function (price_rank) {
			$(XSM.menu.orb_order_form_price_rank).val(price_rank);
			return true;
		},
		rebuild_orb_opt_filters: function() {
			var filter_flags = {};
			$(XSM.menu.orb_opt_filter).remove();
			$(XSM.menu.orb_opt).each(function() {
				$.map($(this).data('flags'), function(flag, index) {
					if (!filter_flags[flag]) {
						filter_flags[flag] = true;
						$(XSM.menu.orb_opt_filters).append(
							$("<li/>").addClass([stripCSS(XSM.menu.orb_opt_filter), XSM.effects.active].join(" "))
										.data("filter", flag)
										.attr("data-filter", flag)
										.html("<span class='icon-checked'></span>" + str_to_upper(flag))
						);
					}
				});
			});
			return true;
		},
		refresh_orb_card_stage: function (orb_card_id) {
			pr(orb_card_id, "refresh_orb_card_stage");
			// todo: fallback on ajax fail
			XBS.data.current_orb_card = orb_card_id;
			var url = XBS.routes.menuitem + C.DS + orb_card_id
			if (XBS.cfg.root.length != 0) url = C.DS + XBS.cfg.root + C.DS + url

			$.get(url, function (data) {
				var orb_card_stage = $.parseHTML(data)[0];
				var orb_opts = $($(orb_card_stage).find(XSM.menu.orb_opt_container)[0]).find(XSM.menu.orb_opt);
				var replace_time = 0;

				// remove orb-opts once extracted so they can be moved to menu
				$(orb_card_stage).find(XSM.menu.orb_opt_container)[0].remove();

				// hide current orb-card-row contents & orb-card-stage-menu items before replacing
				$(orb_card_stage).find(XSM.menu.orb_card_content_container).each(
					function() { $(this).addClass(XSM.effects.fade_out); });
				$(orb_opts).each(function() {$(this).addClass(XSM.effects.fadeOut).hide();});

				// do replacements and fade items back in
				if ($(XSM.menu.orb_card_3d_context).hasClass(XSM.effects.flipped_y) ) {
					replace_time = 950;
					XBS.menu.show_orb_card_front_face();
				}
				setTimeout(function() {
						$(XSM.menu.orb_card_content_container).addClass(XSM.effects.fade_out);
						setTimeout( function() {
							$(XSM.menu.orb_card_stage).replaceWith(orb_card_stage);
							// this timeout just buys the browser a moment to catch up, otherwise CSS transition doesn't fire
							setTimeout(function() { $(XSM.menu.orb_card_content_container).removeClass(XSM.effects.fade_out);}, 30);
							// remove all the orb-opts from the previous item and replace them with the new set
							$(XSM.menu.orb_opt).remove();
							$(orb_opts).find(XSM.menu.orb_opt).each(function() {
								$(this).appendTo(XSM.menu.orb_card_stage_menu);
							});
						}, 300);
				}, replace_time);
			}).then( function () {
				XBS.menu.rebuild_orb_opt_filters();
				XBS.fn.execInitSequence({"XBS.menu.data_structures": XBS.menu.data_structures})
				XBS.menu.load_from_cart(orb_card_id);
			});
		},
		refresh_active_orbcat_menu: function (orbcat_id, orbcat_name) {
			pr([orbcat_id, orbcat_name], "refresh_active_orbcat_menu()");
			// todo: fallback on ajax fail
			var url = XBS.routes.menu + C.DS + orbcat_id
			if (XBS.cfg.root.length != 0) url = C.DS + XBS.cfg.root + C.DS + url;

			$.get(url,function (data) {
				var active_orbcat_menu = $.parseHTML(data)[1];
				var active_orb_id = $($(active_orbcat_menu).find(XSM.menu.active_orbcat_item)[0]).data('orb');

				// toggle the header; alternates rotating front-to-back or back-to-front
				if ($(XSM.menu.active_orb_name_3d_context).hasClass(XSM.effects.flipped_x)) {
					$(XSM.menu.active_orb_name_front_face).html(orbcat_name);
					$(XSM.menu.active_orb_name_3d_context).removeClass(XSM.effects.flipped_x);
				} else {
					$(XSM.menu.active_orb_name_back_face).html(orbcat_name);
					$(XSM.menu.active_orb_name_3d_context).addClass(XSM.effects.flipped_x);
				}

				// hide all active orbcats in new data so they fade-in when shown
				$(active_orbcat_menu).find(XSM.menu.active_orbcat_item).each(function() {
					$(this).addClass(XSM.effects.fade_out);
				});
				// hide all active orbcats in orb card stage menu before replacing them
				$(XSM.menu.active_orbcat_item).addClass(XSM.effects.fade_out);
				$(XSM.menu.orbcat_menu_title_subtitle).addClass(XSM.effects.fade_out);

				XBS.menu.refresh_orb_card_stage(active_orb_id);
				setTimeout(function () {
					$(XSM.menu.active_orbcat_item).remove()
					setTimeout(function () {
						$(XSM.menu.orbcat_menu_title_subtitle).html(orbcat_name).removeClass(XSM.effects.fade_out);
						$(active_orbcat_menu).find(XSM.menu.active_orbcat_item).each(function() {
							$(this).appendTo(XSM.menu.orb_card_stage_menu);
						});
						setTimeout(function() {$(XSM.menu.active_orbcat_item).removeClass(XSM.effects.fade_out);}, 30);
						XBS.menu.filter_orb_opts(false, C.CHECK);
					}, 300);
				}, 300);
			}).then( function () { $(C.BODY).trigger(C.ORB_CARD_REFRESH); });
		},
		reset_orb_card_stage: function () {
			pr("<no args>", "reset_orb_card_stage()");
			$(XSM.menu.orb_order_form_price_rank).val(0);
			$(XSM.menu.orb_size_button).each(function () {
				$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
			});
			$(XSM.menu.orb_order_form_quantity).val(1);
			$(XSM.menu.orb_order_form_prep_instrux).val("");
			XBS.menu.show_orb_card_front_face();
		},
		show_orb_card_front_face: function() {
			$(XSM.menu.orb_card_3d_context).removeClass(XSM.effects.flipped_y);
			$(XSM.menu.orb_opt).addClass(XSM.effects.fade_out);
			setTimeout( function() {
				$(XSM.menu.orb_opts_menu_header).addClass(XSM.effects.slide_right);
				$(XSM.menu.orb_opt).hide();
				setTimeout(function() {
					XBS.menu.reset_orb_opt_filters();
					$(XSM.menu.active_orbcat_item).show()
					setTimeout(function() { $(XSM.menu.active_orbcat_item).removeClass(XSM.effects.fade_out);}, 30);
					setTimeout(function() {
						$(XSM.menu.orb_opts_menu_header).hide()
						$(XSM.menu.active_orbcat_menu_header).show();
						setTimeout(function() { $(XSM.menu.active_orbcat_menu_header).removeClass(XSM.effects.slide_left);}, 30);
						$(XSM.menu.orb_card_stage_menu).addClass(XSM.effects.activizing);
					}, 300);
				}, 300);
			}, 300);

			return true;
		},
		show_orb_card_back_face: function() {
			$(XSM.menu.orb_card_3d_context).addClass(XSM.effects.flipped_y);
			$(XSM.menu.active_orbcat_item).addClass(XSM.effects.fade_out);
			setTimeout( function() {
				$(XSM.menu.active_orbcat_menu_header).addClass(XSM.effects.slide_left);
				$(XSM.menu.active_orbcat_item).hide();
				setTimeout(function() {
					$(XSM.menu.orb_opt).show()
					setTimeout(function() {$(XSM.menu.orb_opt).removeClass(XSM.effects.fade_out);}, 30);
					setTimeout( function() {
						$(XSM.menu.active_orbcat_menu_header).hide();
						$(XSM.menu.orb_opts_menu_header).show();
						setTimeout(function() { $(XSM.menu.orb_opts_menu_header).removeClass(XSM.effects.slide_right);}, 30);
						$(XSM.menu.orb_card_stage_menu).removeClass(XSM.effects.activizing);
					}, 300);
				}, 350);
			}, 300);
		},
		stash_menu: function () {
			//XBS.layout.fasten([XSM.menu.orb_card_wrapper, XSM.menu.orbcat_menu]);
			$(XSM.menu.user_activity_panel).removeClass("show-for-large-up")
				.hide("slide", {direction: "up"}, 300, function () {
					$(XSM.menu.orb_card_wrapper).hide("slide", {
						direction: "left",
						easing: "easeInQuad"}, 500, null);
					$(XSM.menu.orbcat_menu).hide("slide", {
						direction: "right",
						easing: "easeInQuad"}, 500, function () {
					});
				});
		},
		toggle_orb_opt: function (element, trigger_update) {
			if ($(element).hasClass(XSM.effects.active)) {
				$(element).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
				$(element).find(XSM.menu.orb_opt_icon).each(function () {
					$(this).removeClass(XSM.effects.enabled).addClass(XSM.effects.disabled);
				});
			} else {
				$(element).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
				$(element).find(XSM.menu.orb_opt_icon).each(function () {
					$(this).removeClass(XSM.effects.disabled).addClass(XSM.effects.enabled);
				});
			}
			// let animations complete before walking DOM for smoothness
			if (trigger_update === true) setTimeout(function() { $(C.BODY).trigger(C.ORDER_UI_UPDATE);}, 300);
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
				if ($(this).hasClass(XSM.effects.active) ) {
					weight = $($(this).find(XSM.menu.orb_opt_icon_active)[0]).data(C.WEIGHT);
					$("<li/>").addClass(stripCSS(XSM.menu.tiny_orb_opts_list_item))
						.append("<span>" + topping_name + "</span>")
						.append(C[weight])
						.appendTo(tiny_toppings_list);
				}

				$(XSM.generated.order_form_order_opt($(this).data('id'))).val(weight);
			});

			$(XSM.menu.tiny_orb_opts_list_wrapper).html(tiny_toppings_list);
		},
		/**
		 *  updates visual state of orb card toppings & icons
		 *  to respect current state of the underlying form
		 */
		update_orb_configuration_ui: function () {
			$(XSM.menu.orb_order_form_orb_opts).each(function () {
				var weight_dict = {D: true, F: true, L: true, R: true};
				var weight = $(this).val();
				var topping_id = asId("orb_opt-coverage-" + $(this).attr('id').replace("OrderOrbOrbopts", ""));
				if (weight in weight_dict) {
					XBS.menu.toggle_orb_opt($(topping_id), false);
					var icon = $(topping_id).find(XSM.menu.orb_opt_icon + "[data-weight=" + weight+"]")[0];
					XBS.menu.toggle_orb_opt_icon(icon, false);
				}
			});
			var price_rank = $(XSM.menu.orb_order_form_price_rank).val()
			XBS.layout.activize($(XSM.menu.orb_size_button+"[data-price-rank=" + price_rank +"]"));
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
			var splashbarTop = $(XSM.splash.splash_bar).offset().top;
			var scaleFactor = 570 / splashbarTop;
			var dealDim = [splashbarTop, scaleFactor * 400];
			var dealLeft = String((window.innerWidth / 2) + (.8 * $(XSM.splash.order).innerWidth())) + "px";

			/* ---------------- opening deal temp code ------------------*/
			$(XSM.splash.openingDeal).css({
				height: String(1.08 * dealDim[0]) + "px",
				width: String(1.08 * dealDim[1]) + "px",
				left: dealLeft
			});
			$(XSM.splash.fastened).attr('style', '').removeClass('fastened');
			$(XSM.splash.detach).attr('style', '');
			/* ---------------- opening deal temp code ------------------*/
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
			XBS.layout.fasten([XSM.splash.splash_bar, XSM.splash.logo]);
			var logo = $(XSM.splash.logo).clone().attr('id', stripCSS(XSM.splash.logoClone));
			var logoLoc = $(XSM.splash.logo).offset();
			$(logo).css({position: "fixed", top: logoLoc.top, left: logoLoc.left, zIndex: "9999999"});
			$(XSM.splash.self).append(logo);
			$(XSM.splash.logo).remove();
			$(XSM.splash.splash_bar).animate({left: String(-1.1 * window.innerWidth) + "px"}, 300, "easeInCubic",
				function () {
					$(this).hide();
					$(XSM.splash.circleWrap).css({});
					$(XSM.splash.circle).addClass("flipped");
					//todo: use modernizr to do this more effectively
					//		$(XSM.splash.circle).animate({transform:"rotafdteY(640deg)"}, 1000,"linear", function() {
					$(XSM.splash.logoClone).hide("puff", function () {window.location.replace(route)});
					//								$("#splash").fadeOut();
					//		});
				});
			return true;
		}
	},
	fn: {
		execInitSequence: function (init_list) {
			var meta_sit_rep = {state: true, report:{}};
			for (init_ob_name in init_list) {
				meta_sit_rep.report[init_ob_name] = {};
				var init_ob = init_list[init_ob_name];
				if (!exists(init_ob.has_init_sequence) ) {
					throw new TypeError("Object passed to XBS.fn.execInitSequence() does not contain 'has_init_sequence' property.");
				}
				for (fn in init_ob) {
					if (fn != "has_init_sequence")  {
						meta_sit_rep.report[init_ob_name][fn] = init_ob[fn]();
						if (meta_sit_rep.report[init_ob_name][fn] !== true) meta_sit_rep.state = false;
					}
				}
			}
			return meta_sit_rep;
		},
		route: function(controller, action) {
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