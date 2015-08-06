/**
 * Created by jono on 1/24/15.
 */
var xbs_menu = {
		init: function () {
			var init_ok = true;
			if (XBS.data.cfg.page_name == C.MENU) {
				init_ok = XBS.exec_init_sequence({"XBS.menu.jq_binds": XBS.menu.jq_binds});
				try {
					if (!XBS.data.current_orb) XBS.menu.load_from_cart($(XSM.menu.active_orb).data('orb'));
				} catch (e) {
					init_ok.state = false;
					init_ok.message = "current_orb not set; nothing loaded from cart";
				}
				XBS.menu.rebuild_optflag_filters(C.CHECK);

				if (!is_mobile()) {
					$(XSM.global.footer).css({top: $(XSM.global.page_content).innerHeight()});
					XBS.layout.fasten(XSM.menu.self).css({overflow: "hidden"});
				}
			}
			if (XBS.data.cfg.page_name != XSM.page_name.vendor_ui) XBS.menu.set_order_method();
			return  init_ok
		},
		jq_binds: {
			has_init_sequence: true,
			hide_orb_card_back_face_elements: function () {
				$(XSM.menu.orb_opt).hide();
				$(XSM.menu.optflag_filter_header).hide().removeClass(FX.hidden);
				return true;
			},
			bind_orbsize_update: function () {
				$(C.BODY).on(C.CLK, XSM.menu.orb_size_button, null, function (e) {
					XBS.menu.price_rank_update($(e.currentTarget).data('priceRank'));
				});

				return true;
			},
			bind_topping_methods: function () {
				/** orb_opt filtering (check all) */
				$(C.BODY).on(C.CLK, XSM.menu.orb_opt_filter_all, null, function (e) {
					e.stopPropagation();
					XBS.menu.toggle_optflag(false, $(e.currentTarget).data('all'));
				});

//				/** orb_opt filtering (individual) */
//				$(C.BODY).on(C.CLK, XSM.menu.orb_opt_filter, null, function (e) {
//					pr(e);
//					e.stopPropagation();
//					XBS.menu.toggle_optflag(e.currentTarget);
//				});

				return true;
			},
			order_update_listeners: function () {
				/** order form manual change */
				$(C.BODY).on("change", XSM.forms.orb_order_form, null, XBS.cart.configure);
				return true;
			}
		},
		add_to_cart: function () {
			// todo: ajax fallbacks
			$.ajax({
				type: C.POST,
				url: "orders/add_to_cart",
				data: $(XSM.menu.orb_order_form).serialize(),
				success: function (data) {
					data = JSON.parse(data);
					if (data.success == true) {
						XBS.cart.add_to_cart();
						$(XSM.modal.orb_card).show('clip');
						$(XSM.topbar.topbar_cart_button).show()
						setTimeout(function () {
							$(XSM.topbar.topbar_cart_button).removeClass(FX.fade_out);
						}, 300);
					}
				}
			});
		},
		clear_cart: function () {
			XBS.data.cart = {};
			$(XSM.topbar.topbar_cart_button).addClass(FX.fade_out);
			setTimeout(function () {
				$(XSM.topbar.topbar_cart_button).hide();
				XBS.menu.unstash_menu();
			}, 300);
			return true;
		},
		configure_orb: function (orb_id, price_rank) {
			var debug_this = 2;
			if (debug_this > 1) pr([orb_id, price_rank], "XBS.menu.configure_orb(orb_id, price_rank)", 2);
			XBS.menu.show_orb_card_back_face(); // if this doesn't happen first the rank-assignment is reset
			$(XSM.menu.orb_size_button).each(function () {
				pr($(this).data('priceRank'));
				if ( Number($(this).data('priceRank')) == Number(price_rank) ) XBS.layout.activize(this);
			});
		},
		reset_orb_opt_filters: function () {
			$(XSM.menu.orb_opt_filter).removeClass(FX.inactive).addClass(FX.active);
			$(XSM.menu.orb_opt_filter_span).removeClass(FX.unchecked).addClass(FX.checked);
			XBS.menu.rebuild_optflag_filters(C.CHECK);
			return true;
		},
		enforce_opt_rules: function(orb, weights) {
			var debug_this = 1;
			if ( debug_this > 0 ) pr({orb:orb, weights:weights}, "menu.enforce_opt_rules(orb, weights)", 2);
			// todo: add a layer to check for rules based on orb-type
			$(XSM.menu.sauce).each(function() {
				var children = $(this).find(XSM.menu.orb_opt_icon);
				var is_active = $(this).hasClass(FX.active)
				switch (weights.sauce) {
					case 0:
						$(this).removeClass(FX.inelligible);
						$(children).each(function() { $(this).removeClass(FX.inelligible);});
						break;
						$(this).addClass('lr-only').removeClass('max-of-type');
					case 0.5:
						$(this).removeClass(FX.inelligible);
						/*  if 'full' is inelligible, left-side = 'active' weight unless right-side was already set */
						var right_side_active = false;
						var left_side_element = null;
						$(children).each(function() {
							if (is_active) {
								/* active & side-weighted implies all other weights are available for this opt */
								$(this).removeClass(FX.inelligible);
							} else {
								if ( $(this).hasClass(stripCSS(XSM.menu.full)) ) {
									$(this).addClass(FX.inelligible);
									$(this).removeClass(FX.active).addClass(FX.inactive);
								} else if ( $(this).hasClass(stripCSS(XSM.menu.double)) ) {
									$(this).addClass(FX.inelligible);
									$(this).removeClass(FX.active).addClass(FX.inactive);
								} else {
									$(this).removeClass(FX.inelligible)
									if ( $(this).hasClass(stripCSS(XSM.menu.right_side)) && $(this).hasClass(FX.active) ) {
										right_side_active = true;
									}
									if ( $(this).hasClass(stripCSS(XSM.menu.left_side)) ) left_side_element = this;
								}
							}
						});
						if (!right_side_active) {
							pr(left_side_element, "lse");
							$(left_side_element).addClass(FX.active);
						}
						break;
					case 1:
						var active_icon = false;
						var active_weight = false;
						if (is_active) {
							$(this).removeClass(FX.inelligible);
							active_icon = $(this).find(XSM.menu.orb_opt_icon_active)[0];
							active_weight = XBS.cart.weight_to_int($(active_icon).data('route').split("/")[4]);
						} else {
							$(this).addClass(FX.inelligible);
						}
						$(children).each(function() {
							if (is_active) {
								if (active_weight == 1) {
									$(this).removeClass(FX.inelligible);
								} else {
									var this_weight = XBS.cart.weight_to_int($(this).data('route').split("/")[4]);
									if (this_weight + active_weight > 1) $(this).addClass(FX.inelligible);
								}
								return;
							}
							$(this).addClass(FX.inelligible);
						});
						break;
				}
			});

		},
		toggle_optflag: function (optflag_id, all) {
			pr([optflag_id, all], "toggle_optflag(optflag_id, all)");
			// next two if statements toggle all optflags on or off
			if (all == C.CHECK) {
				$(XSM.menu.orb_opt_filter).removeClass(FX.inactive).addClass(FX.active);
				$(XSM.menu.orb_opt_filter_span).removeClass(FX.unchecked).addClass(FX.checked);
				XBS.menu.rebuild_optflag_filters(C.CHECK);
			}

			if (all == C.UNCHECK) {
				$(XSM.menu.orb_opt_filter).removeClass(FX.active).addClass(FX.inactive);
				$(XSM.menu.orb_opt_filter_span).removeClass(FX.checked).addClass(FX.unchecked);
				XBS.menu.rebuild_optflag_filters(C.UNCHECK);
				pr(XBS.data.orb_opt_filters, "filters post uncheck all");
			}

			// if passed an optflag id, filter only that optflag
			if (optflag_id) {
				var list_el_id = "#optflag-" + optflag_id;
				if (XBS.data.orb_opt_filters[optflag_id] == C.CHECK) {
					XBS.data.orb_opt_filters[optflag_id] = C.UNCHECK;
				} else {
					XBS.data.orb_opt_filters[optflag_id] = C.CHECK;
				}
				var checkbox = $(list_el_id).children(XSM.menu.orb_opt_filter_span)[0];
				if ($(list_el_id).hasClass(FX.active)) {
					$(list_el_id).removeClass(FX.active).addClass(FX.inactive);
					$(checkbox).removeClass(FX.checked).addClass(FX.unchecked);
				} else {
					$(list_el_id).removeClass(FX.inactive).addClass(FX.active);
					$(checkbox).removeClass(FX.unchecked).addClass(FX.checked);
				}
			}
			XBS.menu.filter_orb_opts(optflag_id);
		},

	/**
	 * Apply current set of optflags filters to visible orbopts list
	 *
	 * @param optflag_id
	 * @returns {boolean}
	 */
	filter_orb_opts: function(optflag_id) {
			$(XSM.menu.orb_opt).addClass(FX.fade_out);
			setTimeout(function () {
				$(XSM.menu.orb_opt).show();
				$.map($(XSM.menu.orb_opt), function (orb_opt, index) {
					$.map($(orb_opt).data('optflags'), function (optflag_id) {
						if (optflag_id in XBS.data.orb_opt_filters) {
							if (XBS.data.orb_opt_filters[optflag_id] == C.UNCHECK) {
								$(orb_opt).hide();
							}
						}
					});
				});
				setTimeout(function () { $(XSM.menu.orb_opt).removeClass(FX.fade_out); }, 30);
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
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"order_update/form", trigger:this});
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
		refresh_active_orbcat_menu: function (data) {
			pr([data], "refresh_active_orbcat_menu()");
			var active_orbcat_menu = $.parseHTML(data);
			var orb_route = $($(data).find(XSM.menu.active_orbcat_item)[0]).data('route');
			var active_orb_id = orb_route.split(C.DS)[1];
			var orbcat_name = $($("h2", data)[0]).html();


			// >>> TOGGLE MENU HEADER; alternates rotating front-to-back or back-to-front <<<
			if ($(XSM.menu.active_orb_name_3d_context).hasClass(FX.flipped_x)) {
				$(XSM.menu.active_orb_name_front_face).html(orbcat_name);
				$(XSM.menu.active_orb_name_3d_context).removeClass(FX.flipped_x);
			} else {
				$(XSM.menu.active_orb_name_back_face).html(orbcat_name);
				$(XSM.menu.active_orb_name_3d_context).addClass(FX.flipped_x);
			}

			// >>> HIDE INCOMING ORBCATS; hide new data so they fade-in when shown <<<
			$(active_orbcat_menu).find(XSM.menu.active_orbcat_item).each(function () {
				$(this).addClass(FX.fade_out);
			});

			// >>> HIDE OUTGOING ORBCATS; hide all active orbcats in orb card stage menu before replacing them <<<
			$(XSM.menu.active_orbcat_item).addClass(FX.fade_out);
			$(XSM.menu.orbcat_menu_title_subtitle).addClass(FX.fade_out);

			$(XBS.routing).trigger(C.ROUTE_REQUEST, {'request': 'orb/'+active_orb_id, trigger: {}});
//			XBS.menu.refresh_orb_card_stage(active_orb_id);

			setTimeout(function () {
				$(XSM.menu.active_orbcat_item).remove()
				setTimeout(function () {
					$(XSM.menu.orbcat_menu_title_subtitle).html(orbcat_name).removeClass(FX.fade_out);
					$(active_orbcat_menu).find(XSM.menu.active_orbcat_item).each(function () {
						$(this).appendTo(XSM.menu.orb_card_stage_menu);
					});
					setTimeout(function () {
						$(XSM.menu.active_orbcat_item).removeClass(FX.fade_out);
						$(C.BODY).trigger(C.ORB_CARD_REFRESH)},
						30);
				}, 300);
			}, 300);
		},
		/**
		 * Updates orb card when an orb is clicked in the bottom menu.
		 *
		 * @param orb_id
		 */
		refresh_orb_card_stage: function (orb_id, data) {
			if (!data) throw "JSON from server was malformed.";
			pr({orb_id:orb_id, data:data}, "refresh_orb_card_stage");
			// todo: fallback on ajax fail
			XBS.data.current_orb = orb_id;
			var orb_card_stage = $.parseHTML(data.orb_card_stage);
			var orbopts_list = $.parseHTML(data.orbopts_list.list);
			var optflag_header = data.optflag_header;
			var replace_time = 0;
			pr(data.orbopts_list.portionable, "portionable");


//			>>> REMOVE ORB OPTS BEFORE EXTRACTION <<<
//			$(orb_card_stage).find(XSM.menu.orb_opt_container)[0].remove();

			// >>> HIDE INCOMING ORBOPTS <<<
			$(orbopts_list).each(function () { $(this).hide().addClass(FX.fade_out);});

			// >>> HIDE INCOMING ORBCARD SPECIFICS & ORB OPTS <<<
			$(orb_card_stage).find(XSM.menu.orb_card_content_container).each(function () {
				$(this).addClass(FX.fade_out);
			});
			$($(orb_card_stage).find(XSM.menu.orb_opt_container)[0]).find(XSM.menu.orb_opt).each(function () {
				$(this).addClass([FX.fadeOut, FX.true_hidden].join(" "));
			});
			// >>> UNFLIP ORBCARD IF FLIPPED <<<
			if ($(XSM.menu.orb_card_3d_context).hasClass(FX.flipped_y)) {
				replace_time = 950;
				XBS.menu.show_orb_card_front_face();
			}
			$(as_class(FX.swap_width)).each(function () {
				replace_time = 800;
				XBS.menu.toggle_orb_card_row_menu($($(this).children(".orb-card-button")[0]).attr('id'), C.HIDE)
			});
			setTimeout(function () {
				// >>> FADE OUT OUTGOING ORBCARD CONTENT <<<
				$(XSM.menu.orb_card_content_container).addClass(FX.fade_out);
				setTimeout(function () {
					// >>> REPLACE ORBCARD <<<
					$(XSM.menu.orb_card_stage).replaceWith(orb_card_stage);
					setTimeout(function () { $(XSM.menu.orb_card_content_container).removeClass(FX.fade_out);}, 30);
					// >>> REPLACE ORBOPTS (REMAIN HIDDEN/ FADED) <<<
					$(XSM.menu.orb_opt).remove();
					$(XSM.menu.optflag_filter_header).replaceWith(optflag_header);
					XBS.menu.rebuild_optflag_filters(C.CHECK);
					$(orbopts_list).each(function () { $(this).appendTo(XSM.menu.orb_card_stage_menu); });
					// >>> UPDATE ORBCARD STAGE 'PORTIONABLE' CLASS <<<
					if (data.orbopts_list.portionable != 0) {
						$(XSM.menu.orb_card_stage_menu).addClass('portionable');
					} else {
						$(XSM.menu.orb_card_stage_menu).removeClass('portionable');
					}
					XBS.menu.load_from_cart(orb_id);
				}, 300);
			}, replace_time);
		},
		reset_orb_card_stage: function () {
			var debug_this = 0;
			if (debug_this > 0) pr("<no args>", "menu.reset_orb_card_stage()", 2);
			$(XSM.menu.orb_order_form_price_rank).val(0);
			$(XSM.menu.orb_size_button).each(function () {
				$(this).removeClass(FX.active).addClass(FX.inactive);
			});
			$(XSM.menu.orb_order_form_quantity).val(1);
			$(XSM.menu.orb_order_form_prep_instrux).val("");
			$(XSM.menu.orb_opt).removeClass(FX.active).addClass(FX.inactive);
			$(XSM.menu.orb_opt_icon)
				.removeClass([FX.inelligible, FX.active, FX.enabled].join(" "))
				.addClass([FX.inactive, FX.disabled].join(" "));
			$(XSM.menu.full).addClass(FX.active);
			$(XSM.menu.orb_opt_weight).val(-1);

			XBS.cart.cancel_config($(XSM.menu.orb_order_form_orb_id).val());
			XBS.menu.show_orb_card_front_face();

			$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"order_update/reset", trigger:this});
		},
		toggle_orb_card_row_menu: function (menu, state) {
			var row;
			var button;
			var panel;
			if (menu == "register") {
				row = XSM.menu.orb_card_row_1;
				button = XSM.menu.register_button;
				panel = XSM.menu.registration_panel;
			} else {
				row = XSM.menu.orb_card_row_3;
				button = XSM.menu.share_button;
				panel = XSM.menu.social_panel;
			}

			if ($(panel).hasClass(FX.true_hidden)) $(panel).removeClass(FX.true_hidden);
			if (!state) state = $(row).hasClass(FX.swap_width) ? C.HIDE : C.SHOW;

			if (state == C.HIDE) {
				var wait_for_complete = 0;
				if (!(XBS.data.orb_card_animation_queue.animating === false)) {
					if (XBS.data.orb_card_animation_queue.queued > 1) {
						return;
					}
					XBS.data.orb_card_animation_queue.queued += 1;
					wait_for_complete = new Date().getTime() - XBS.data.orb_card_animation_queue.start;
				}
				setTimeout(function () {
					XBS.data.orb_card_animation_queue.start = new Date().getTime();
					if (!$(row).hasClass(FX.swap_width)) return;
					$(panel).addClass(FX.fade_out);
					setTimeout(function () {
						$(panel).hide();
						$(button).addClass(FX.stash);
						setTimeout(function () {
							$(row).removeClass(FX.swap_width);
							setTimeout(function () { $(button).removeClass(FX.stash);}, 300);
							$(XBS).trigger(C.ORB_ROW_ANIMATION_COMPLETE, {menu: menu, finished: C.SHOW});
						}, 500)
					}, 300);
				}, wait_for_complete);
			}

			if (state == C.SHOW) {
				var wait_for_complete = 0;
				if (!(XBS.data.orb_card_animation_queue.animating === false)) {
					if (XBS.data.orb_card_animation_queue.queued > 1) {
						return;
					}
					XBS.data.orb_card_animation_queue.queued += 1;
					wait_for_complete = new Date().getTime() - XBS.data.orb_card_animation_queue.start;
				}
				setTimeout(function () {
					XBS.data.orb_card_animation_queue.start = new Date().getTime();
					if ($(row).hasClass(FX.swap_width)) return;
					$(row).addClass(FX.swap_width);
					if (!$(panel).hasClass(FX.fade_out)) $(panel).addClass(FX.fade_out);
					$(panel).show();
					setTimeout(function () {
						setTimeout(function () {
							$(panel).removeClass(FX.fade_out);
						}, 300);
						$(XBS).trigger(C.ORB_ROW_ANIMATION_COMPLETE, {menu: menu, finished: C.HIDE});
					}, 300)
				}, wait_for_complete);
			}

			return true;
		},
		set_order_method: function (method) {
			if (method)  {
				XBS.data.Service.order_method = method;
			} else {
				method = XBS.data.Service.order_method;
			}
			if (!method) {
				XBS.data.Service.order_method = C.JUST_BROWSING;
				method = C.JUST_BROWSING;
			}
			$(XSM.menu.user_activity_panel_items).each(function () {
				var route = $($(this).children()[0]).data('route');
				if (route) {
					if (route.split(C.DS)[2] == method) {
						$(this).removeClass(FX.inactive).addClass(FX.active);
					} else {
						$(this).removeClass(FX.active).addClass(FX.inactive);
					}
				}
			});

		},
		show_orb_card_front_face: function () {
			var debug_this = 0;
			if (debug_this > 0) pr("<no args>", "XBS.menu.show_orb_card_front_face()", 2);
			XBS.data.orb_card_out_face = C.FRONT_FACE;
			// >>> START FLIP & FADE OUT ORBOPTS TOGETHER <<<
			$(XSM.menu.orb_card_3d_context).removeClass(FX.flipped_y);
			$(XSM.menu.orb_opt).addClass(FX.fade_out);
			setTimeout(function () {
				// >>> SLIDE OUT FILTER HEADER, HIDE ORBOPTS <<<
				$(XSM.menu.optflag_filter_header).addClass(FX.slide_right);
				$(XSM.menu.orb_opt).hide();
				setTimeout(function () {
					// >>> RESET FILTERS & SHOW ORBCAT ITEMS <<<
					XBS.menu.reset_orb_opt_filters();
					$(XSM.menu.active_orbcat_item).show()
					setTimeout(function () { $(XSM.menu.active_orbcat_item).removeClass(FX.fade_out);}, 30);
					setTimeout(function () {
						// >>> SLIDE IN ORBCAT ITEMS HEADER; RESTORE 'ACTIVIZING' <<<
						$(XSM.menu.optflag_filter_header).hide()
						$(XSM.menu.active_orbcat_menu_header).show();
						setTimeout(function () { $(XSM.menu.active_orbcat_menu_header).removeClass(FX.slide_left);}, 30);
						$(XSM.menu.orb_card_stage_menu).addClass(FX.activizing);
					}, 300);
				}, 300);
			}, 300);

			return true;
		},
		show_orb_card_back_face: function () {
			XBS.menu.update_orb_form();
			XBS.menu.update_orb_configuration_ui();
			/**
			 *
			 *
			 * TODO: If you choose an orb, flip to back face, flip back, and return to backface all default options have been cleared.
			 * might be a better id to keep all the defaults in a data-default on the orb itself in addition to the server-side formatting
			 *
			 */
			XBS.data.orb_card_out_face = C.BACK_FACE;
			var row_menu_hide_time = 0;
			$(as_class(FX.swap_width)).each(function () {
				row_menu_hide_time = 800;
				XBS.menu.toggle_orb_card_row_menu($($(this).children(".orb-card-button")[0]).attr('id'), C.HIDE)
			});
			setTimeout(function () {
				// >>> START FLIP & FADE OUT ORBOPTS TOGETHER <<<
				$(XSM.menu.orb_card_3d_context).addClass(FX.flipped_y);
				$(XSM.menu.active_orbcat_item).addClass(FX.fade_out);
				setTimeout(function () {
					// >>> SLIDE OUT ORBCAT ITEMS HEADER, HIDE ORBCAT ITEMS <<<
					$(XSM.menu.active_orbcat_menu_header).addClass(FX.slide_left);
					$(XSM.menu.active_orbcat_item).hide();
					setTimeout(function () {
						// >>> SHOW & FADE IN ORBOPTS<<<
						$(XSM.menu.orb_opt).show()
						setTimeout(function () {$(XSM.menu.orb_opt).removeClass(FX.fade_out);}, 30);
						setTimeout(function () {
							// >>> SLIDE IN FILTER HEADER; REMOVE 'ACTIVIZING' <<<
							$(XSM.menu.active_orbcat_menu_header).hide();
							$(XSM.menu.optflag_filter_header).show();
							setTimeout(function () { $(XSM.menu.optflag_filter_header).removeClass(FX.slide_right);}, 30);
							$(XSM.menu.orb_card_stage_menu).removeClass(FX.activizing);
						}, 300);
					}, 300);
				}, 300);
			}, row_menu_hide_time);
		},
		stash_menu: function () {
			var orb_card_timeout = 0;
			if (XBS.data.orb_card_out_face == C.BACK_FACE) {
				orb_card_timeout = 960;
				XBS.menu.show_orb_card_front_face();
			}
			setTimeout(function () {
				$(XSM.menu.user_activity_panel).addClass(FX.slide_up);
				setTimeout(function () {
					$(XSM.menu.orb_card_wrapper).addClass([FX.slide_left, FX.fade_out].join(" "));
					setTimeout(function () {
						$(XSM.menu.orbcat_menu).addClass([FX.slide_right, FX.fade_out].join(" "));
					}, 300);
				}, 300);
			}, orb_card_timeout);
		},
		unstash_menu: function () {
			$(XSM.menu.orbcat_menu).removeClass([FX.slide_right, FX.fade_out].join(" "));
			// todo: this is a bit of a hack; the over-all logic should preclude this next line, but,
			// todo: "activizing" gets toggled during the orbcard flip, and if it's in the wrong state, toggles inversely
			// todo: making orb-opts unselectable
			$(XSM.menu.orb_card_stage_menu).addClass(FX.activizing);
			setTimeout(function () {
				$(XSM.menu.orb_card_wrapper).removeClass([FX.slide_left, FX.fade_out].join(" "));
				setTimeout(function () {
					$(XSM.menu.user_activity_panel).removeClass(FX.slide_up);
				}, 300);
			}, 300);
			XBS.menu.set_order_method();
			XBS.menu.reset_orb_card_stage();
		},
		toggle_orb_opt: function (element, trigger_update) {
			var debug_this = 1;
			if (debug_this > 0) pr([element, trigger_update], "XBS.menu.toggle_orb_opt(element, trigger_update)", 2);
			var flags = $(element).data('optflags');
			if ( $(element).hasClass(FX.inelligible) ) return true;

			var remove = $(element).hasClass(FX.active) ? [FX.active, FX.enabled] : [FX.inactive, FX.disabled]
			var add = $(element).hasClass(FX.active) ? [FX.inactive, FX.disabled] : [FX.active, FX.enabled]
			$(element).removeClass(remove[0]).addClass(add[0]);
			$(XSM.menu.orb_opt_icon, element).each(function () { $(this).removeClass(remove[1]).addClass(add[1])});

			if (trigger_update === true) {
				setTimeout(function () {
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"order_update/ui", trigger:this});
				}, 300);
			}
			return true;
		},
		toggle_orb_opt_icon: function (element, trigger_update) {
			var debug_this = 0;
			if (debug_this > 0) pr([element, trigger_update], "XBS.menu.toggle_orb_opt_icon(element, trigger_update)", 2);
			XBS.layout.activize(element); // wraps activize so event propagation can be stopped
			if (trigger_update === true) $(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"order_update/ui", trigger:this});
			return true;
		},

		set_orbopt_form_state: function(element, opt_title) {
			var weight = -1;
			// get the real weighting if the opt has been activated
			if ( $(element).hasClass(FX.active)) {
				weight = $(XSM.menu.orb_opt_icon_active, element).data('weight');
				XBS.data.tiny_orb_opts_list[element] = XSM.generated.tiny_orb_opt_list_item(opt_title, weight);
			} else XBS.data.tiny_orb_opts_list[element] = false;

			// set the corresponding form field to the opt's weighting
			var form_opt_id = XSM.generated.order_form_opt_id(element);
			$(form_opt_id).val(weight);
		},

		update_orb_form: function () {
			// walk the orbopts list [ui] and map every value onto the orb form (will populate tiny_opts list in XBS.data)
			$(XSM.menu.orb_opt).each(function () {
				var tail = XBS.routing.route_split($(this).data('route'), 2);
				$(XBS.routing).trigger(C.ROUTE_REQUEST,     {
					request: ["orb_opt", "form_update", tail[0], tail[1]].join(C.DS),
					trigger: {}
				});
			});
			// build tiny_opts list
			var tiny_toppings_list = $("<ul/>").addClass(sel_to_str(XSM.menu.tiny_orb_opts_list));
			for (var el in XBS.data.tiny_orb_opts_list) {
				if (XBS.data.tiny_orb_opts_list[el]) tiny_toppings_list.append(XBS.data.tiny_orb_opts_list[el]);
			}

			// replace the tiny_opts list in the orbcard
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
				var opt_id = XSM.generated.orb_opt_id(XBS.cart.from_attribute_id_str($(this).attr('id')).opt_id);
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

	/**
	 * resets the optgroup filters to all active or all inactive (ie. state)
	 * @param target_state
	 * @returns {boolean}
	 */
		rebuild_optflag_filters: function (target_state) {
			if (target_state != C.CHECK && target_state != C.UNCHECK) target_state = C.CHECK;
			XBS.data.orb_opt_filters = {}
			$(XSM.menu.orb_opt_filter).each(function () {
				XBS.data.orb_opt_filters[Number($(this).data('optflag-id'))] = target_state;
			});
			return true;
		}
	};