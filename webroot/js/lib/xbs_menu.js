/**
 * Created by jono on 1/24/15.
 */
var xbs_menu = {
		init: function () {
			var init_ok = true;
			if (XBS.data.cfg.page_name == C.MENU) {
				init_ok = XBS.exec_init_sequence({"XBS.menu.jq_binds": XBS.menu.jq_binds});
				try {
					if (!XBS.data.current_orb_card) XBS.menu.load_from_cart($(XSM.menu.active_orb).data('orb'));
				} catch (e) {
					init_ok.state = false;
					init_ok.message = "current_orb_card not set; nothing loaded from cart";
				}
				XBS.menu.update_orb_opt_filters_list(C.CHECK);

				if (!is_mobile()) {
					$(XSM.global.footer).css({top: $(XSM.global.page_content).innerHeight()});
					XBS.layout.fasten(XSM.menu.self).css({overflow: "hidden"});
				}
			}
			XBS.menu.set_order_method();
			return  init_ok
		},
		jq_binds: {
			has_init_sequence: true,
			hide_orb_card_back_face_elements: function () {
				$(XSM.menu.orb_opt).hide();
				$(XSM.menu.orb_opts_menu_header).hide().removeClass(XSM.effects.hidden);
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
							$(XSM.topbar.topbar_cart_button).removeClass(XSM.effects.fade_out);
						}, 300);
					}
				}
			});
		},
		clear_cart: function () {
			XBS.data.cart = {};
			$(XSM.topbar.topbar_cart_button).addClass(XSM.effects.fade_out);
			setTimeout(function () {
				$(XSM.topbar.topbar_cart_button).hide();
				XBS.menu.unstash_menu();
			}, 300);
			return true;
		},
		configure_orb: function (orb_id, price_rank) {
			var debug_this = 0;
			if (debug_this > 1) pr([orb_id, price_rank], "XBS.menu.configure_orb(orb_id, price_rank)", 2);
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
		enforce_opt_rules: function(orb, weights) {
			var debug_this = 1;
			if ( debug_this > 0 ) pr({orb:orb, weights:weights}, "menu.enforce_opt_rules(orb, weights)", 2);
			// todo: add a layer to check for rules based on orb-type
			$(XSM.menu.sauce).each(function() {
				var children = $(this).find(XSM.menu.orb_opt_icon);
				var is_active = $(this).hasClass(XSM.effects.active)
				switch (weights.sauce) {
					case 0:
						$(this).removeClass(XSM.effects.inelligible);
						$(children).each(function() { $(this).removeClass(XSM.effects.inelligible);});
						break;
						$(this).addClass('lr-only').removeClass('max-of-type');
					case 0.5:
						$(this).removeClass(XSM.effects.inelligible);
						/*  if 'full' is inelligible, left-side = 'active' weight unless right-side was already set */
						var right_side_active = false;
						var left_side_element = null;
						$(children).each(function() {
							if (is_active) {
								/* active & side-weighted implies all other weights are available for this opt */
								$(this).removeClass(XSM.effects.inelligible);
							} else {
								if ( $(this).hasClass(stripCSS(XSM.menu.full)) ) {
									$(this).addClass(XSM.effects.inelligible);
									$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
								} else if ( $(this).hasClass(stripCSS(XSM.menu.double)) ) {
									$(this).addClass(XSM.effects.inelligible);
									$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
								} else {
									$(this).removeClass(XSM.effects.inelligible)
									if ( $(this).hasClass(stripCSS(XSM.menu.right_side)) && $(this).hasClass(XSM.effects.active) ) {
										right_side_active = true;
									}
									if ( $(this).hasClass(stripCSS(XSM.menu.left_side)) ) left_side_element = this;
								}
							}
						});
						if (!right_side_active) {
							pr(left_side_element, "lse");
							$(left_side_element).addClass(XSM.effects.active);
						}
						break;
					case 1:
						var active_icon = false;
						var active_weight = false;
						if (is_active) {
							$(this).removeClass(XSM.effects.inelligible);
							active_icon = $(this).find(XSM.menu.orb_opt_icon_active)[0];
							active_weight = XBS.cart.weight_to_int($(active_icon).data('route').split("/")[4]);
						} else {
							$(this).addClass(XSM.effects.inelligible);
						}
						$(children).each(function() {
							if (is_active) {
								if (active_weight == 1) {
									$(this).removeClass(XSM.effects.inelligible);
								} else {
									var this_weight = XBS.cart.weight_to_int($(this).data('route').split("/")[4]);
									if (this_weight + active_weight > 1) $(this).addClass(XSM.effects.inelligible);
								}
								return;
							}
							$(this).addClass(XSM.effects.inelligible);
						});
						break;
				}
			});

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
		refresh_active_orbcat_menu: function (orbcat_id, orbcat_name) {
			pr([orbcat_id, orbcat_name], "refresh_active_orbcat_menu()");
			// todo: fallback on ajax fail
			var url = "menu" + C.DS + orbcat_id
			if (XBS.data.cfg.root.length != 0) url = C.DS + XBS.data.cfg.root + C.DS + url;


			$.get(url,function (data) {
				var active_orbcat_menu = $.parseHTML(data)[1];
				var orb_route = $($(data).find(XSM.menu.active_orbcat_item)[0]).data('route');
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
			if (XBS.data.cfg.root.length != 0) url = C.DS + XBS.data.cfg.root + C.DS + url

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
				$(as_class(XSM.effects.swap_width)).each(function () {
					replace_time = 800;
					XBS.menu.toggle_orb_card_row_menu($($(this).children(".orb-card-button")[0]).attr('id'), C.HIDE)
				});
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
			var debug_this = 0;
			if (debug_this > 0) pr("<no args>", "menu.reset_orb_card_stage()", 2);
			$(XSM.menu.orb_order_form_price_rank).val(0);
			$(XSM.menu.orb_size_button).each(function () {
				$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
			});
			$(XSM.menu.orb_order_form_quantity).val(1);
			$(XSM.menu.orb_order_form_prep_instrux).val("");
			$(XSM.menu.orb_opt).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
			$(XSM.menu.orb_opt_icon)
				.removeClass([XSM.effects.inelligible, XSM.effects.active, XSM.effects.enabled].join(" "))
				.addClass([XSM.effects.inactive, XSM.effects.disabled].join(" "));
			$(XSM.menu.full).addClass(XSM.effects.active);
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

			if ($(panel).hasClass(XSM.effects.true_hidden)) $(panel).removeClass(XSM.effects.true_hidden);
			if (!state) state = $(row).hasClass(XSM.effects.swap_width) ? C.HIDE : C.SHOW;

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
					if (!$(row).hasClass(XSM.effects.swap_width)) return;
					$(panel).addClass(XSM.effects.fade_out);
					setTimeout(function () {
						$(panel).hide();
						$(button).addClass(XSM.effects.stash);
						setTimeout(function () {
							$(row).removeClass(XSM.effects.swap_width);
							setTimeout(function () { $(button).removeClass(XSM.effects.stash);}, 300);
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
					if ($(row).hasClass(XSM.effects.swap_width)) return;
					$(row).addClass(XSM.effects.swap_width);
					if (!$(panel).hasClass(XSM.effects.fade_out)) $(panel).addClass(XSM.effects.fade_out);
					$(panel).show();
					setTimeout(function () {
						setTimeout(function () {
							$(panel).removeClass(XSM.effects.fade_out);
						}, 300);
						$(XBS).trigger(C.ORB_ROW_ANIMATION_COMPLETE, {menu: menu, finished: C.HIDE});
					}, 300)
				}, wait_for_complete);
			}

			return true;
		},
		set_order_method: function (method) {
			if (method)  {
				XBS.data.order.order_method = method;
			} else {
				method = XBS.data.order.order_method;
			}
			if (!method) {
				XBS.data.order.order_method = C.JUST_BROWSING;
				method = C.JUST_BROWSING;
			}
			$(XSM.menu.user_activity_panel_items).each(function () {
				var route = $($(this).children()[0]).data('route');
				if (route) {
					if (route.split(C.DS)[2] == method) {
						$(this).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
					} else {
						$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
					}
				}
			});

		},
		show_orb_card_front_face: function () {
			var debug_this = 0;
			if (debug_this > 0) pr("<no args>", "XBS.menu.show_orb_card_front_face()", 2);
			XBS.data.orb_card_out_face = C.FRONT_FACE;
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
			XBS.data.orb_card_out_face = C.BACK_FACE;
			var row_menu_hide_time = 0;
			$(as_class(XSM.effects.swap_width)).each(function () {
				row_menu_hide_time = 800;
				XBS.menu.toggle_orb_card_row_menu($($(this).children(".orb-card-button")[0]).attr('id'), C.HIDE)
			});
			setTimeout(function () {
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
			}, row_menu_hide_time);
		},
		stash_menu: function () {
			var orb_card_timeout = 0;
			if (XBS.data.orb_card_out_face == C.BACK_FACE) {
				orb_card_timeout = 960;
				XBS.menu.show_orb_card_front_face();
			}
			setTimeout(function () {
				$(XSM.menu.user_activity_panel).addClass(XSM.effects.slide_up);
				setTimeout(function () {
					$(XSM.menu.orb_card_wrapper).addClass([XSM.effects.slide_left, XSM.effects.fade_out].join(" "));
					setTimeout(function () {
						$(XSM.menu.orbcat_menu).addClass([XSM.effects.slide_right, XSM.effects.fade_out].join(" "));
					}, 300);
				}, 300);
			}, orb_card_timeout);
		},
		unstash_menu: function () {
			$(XSM.menu.orbcat_menu).removeClass([XSM.effects.slide_right, XSM.effects.fade_out].join(" "));
			// todo: this is a bit of a hack; the over-all logic should preclude this next line, but,
			// todo: "activizing" gets toggled during the orbcard flip, and if it's in the wrong state, toggles inversely
			// todo: making orb-opts unselectable
			$(XSM.menu.orb_card_stage_menu).addClass(XSM.effects.activizing);
			setTimeout(function () {
				$(XSM.menu.orb_card_wrapper).removeClass([XSM.effects.slide_left, XSM.effects.fade_out].join(" "));
				setTimeout(function () {
					$(XSM.menu.user_activity_panel).removeClass(XSM.effects.slide_up);
				}, 300);
			}, 300);
			XBS.menu.set_order_method();
		},
		toggle_orb_opt: function (element, trigger_update) {
			var debug_this = 1;
			if (debug_this > 0) pr([element, trigger_update], "XBS.menu.toggle_orb_opt(element, trigger_update)", 2);
			var flags = $(element).data('flags');
			if ( $(element).hasClass(XSM.effects.inelligible) ) return true;
			if ($(element).hasClass(XSM.effects.active)) {
				$(element).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
				$(element).find(XSM.menu.orb_opt_icon).each(function () {
					$(this).removeClass(XSM.effects.enabled).addClass(XSM.effects.disabled);
				});
			} else {
				$(element).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
				$(element).find(XSM.menu.orb_opt_icon).each(function () {
//					if ($(this).hasClass(XSM.effects.inelligible) ) return;
					$(this).removeClass(XSM.effects.disabled).addClass(XSM.effects.enabled);
				});
			}
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
		update_orb_form: function () {
			/*  1. walk the opts ui and map every value onto the orb form (will populate tiny_opts list in XBS.data)
				2. build tiny_opts list
				3. replace the tiny_opts list in the orbcard
			 */
			// #1
			$(XSM.menu.orb_opt).each(function () {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {
					request: ($(this).data("route")).replace("\/opt\/", "\/form_update\/"),
					trigger: {}
				});
			});
			// #2
			var tiny_toppings_list = $("<ul/>").addClass(sel_to_str(XSM.menu.tiny_orb_opts_list));
			for (var el in XBS.data.tiny_orb_opts_list) {
				if (XBS.data.tiny_orb_opts_list[el]) tiny_toppings_list.append(XBS.data.tiny_orb_opts_list[el]);
			}

			// #3
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
		update_orb_opt_filters_list: function (target_state) {
			if (target_state != C.CHECK && target_state != C.UNCHECK) target_state = C.CHECK;
			XBS.data.orb_opt_filters = {}
			$(XSM.menu.orb_opt_filter).each(function () {
				XBS.data.orb_opt_filters[$(this).data('filter')] = target_state;
			});
			return true;
		}
	};