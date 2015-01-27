/**
 * Created by jono on 1/24/15.
 */
var xbs_layout = {
		init: function () {
			var sit_rep = XBS.exec_init_sequence({"XBS.layout.jq_binds": XBS.layout.jq_binds});
			if (XBS.data.cfg.is_splash) XBS.layout.detachAnimationTargets();
			var page_content_height = window.innerHeight - ($(XSM.global.topbar).innerHeight() + 3 * C.REM) + C.PX;
			$(XSM.global.page_content).css({minHeight: page_content_height});
			setTimeout(function () {
				$(XSM.topbar.social_loading).addClass(XSM.effects.fade_out);
				setTimeout(function () {
					$(XSM.topbar.social_loading).hide(XSM.effects.fade_out);
					setTimeout(function () {
						$(XSM.topbar.icon_row).removeClass(XSM.effects.true_hidden)
						setTimeout(function () {
							$(XSM.topbar.icon_row).removeClass(XSM.effects.fade_out);
						}, 30);
					}, 0);
				}, 0);
			}, 0);

			return sit_rep;
		},
		detachAnimationTargets: function () {
			$(as_class(XSM.effects.detach)).each(function () { XBS.layout.detach(this);});
		},
		jq_binds: {
			has_init_sequence: true,
			bind_orb_card_config_archiving: function () {
				$(C.BODY).on(xbs_events.orb_card_refresh, null, null, function (e) {
					XBS.menu.archive_orb_card_config(e.data);
				});

				return true;
			},
			init_modals: function () {
				/** initially hide overlay & bind dismiss-on-click */
				$(XSM.modal.overlay).addClass(XSM.effects.fade_out).hide().removeClass(XSM.effects.true_hidden);
//				$(C.BODY).on(C.CLK, XSM.modal.overlay, null, XBS.layout.dismiss_modal);
			},
			bind_activizing_lists: function () {
				$("body").on(C.CLK, XSM.global.activizing_list, function (e) {
					XBS.layout.activize(e.currentTarget);
				});

				return true
			},
			bind_multi_activizing_siblings: function () {
				$(C.BODY).on(C.CLK, XSM.global.multi_activizing, function (e) {
					XBS.layout.multi_activize(e.currentTarget);
				});

				return true
			},
			bind_float_labels: function () {
				$(C.BODY).on(C.MOUSEENTER, as_class(XSM.effects.float_label), null, function (e) {
					XBS.layout.toggle_float_label($(e.currentTarget).data('float-label'), C.SHOW);
				});
				$(C.BODY).on(C.MOUSEOUT, as_class(XSM.effects.float_label), null, function (e) {
					XBS.layout.toggle_float_label($(e.currentTarget).data('float-label'), C.HIDE);
				});

				return true;
			},
			bind_splash_links: function () {
				$(C.BODY).on(C.CLK, XSM.splash.splash_link, null, function (e) {
					XBS.splash.redirect($(e.currentTarget).data('url'));
				});
				return true;
			},
			bind_topbar_hover_links: function () {
				var debug_this = false;
				/** show hover-text */
				$(C.BODY).on(C.MOUSEENTER, XSM.topbar.hover_text_link, null, function (e) {
					if (!$(e.currentTarget).hasClass(XSM.effects.disabled)) {
						if (debug_this) pr("firing!");
						XBS.layout.toggle_topbar_hover_text($(e.currentTarget).data('hover_text'));
					}
				});
				$(C.BODY).on(C.MOUSEOUT, XSM.topbar.hover_text_link, null, function (e) {
					if (!$(e.currentTarget).hasClass(XSM.effects.disabled)) XBS.layout.decay_topbar_hover();
				});
			},
			window_resize_listener: function () {
				if (XBS.data.cfg.is_splash) {
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
			var is_static = $(element).data('static');
			var height = $(element).innerHeight();
			$(element).css({height: height});
			if (is_static) XBS.layout.fasten(element);
			return $(element);
		},
		dismiss_modal: function (modal, action) {
			var debug_this = 0;
			if (debug_this > 0) pr([modal, action], "XBS.layout.dismiss_modal(modal, action)", 2);
			$(XSM.modal.primary).addClass(XSM.effects.slide_up);
			$(XSM.modal.flash).addClass(XSM.effects.slide_up);
			$(XSM.modal.splash).addClass(XSM.effects.slide_up);
			$(XSM.modal.orb_card).hide('clip');
			setTimeout(function () {
				$(XSM.modal.overlay).addClass(XSM.effects.fade_out);
				setTimeout(function () { $(XSM.modal.overlay).hide(); }, 300);
			}, 300);
			if (action) {
				switch (action) {
					case "reset-user-activity":
						$(XSM.menu.user_activity_panel).children().each(function () {
							if ($(this).hasClass(XSM.effects.active)) {
								$(this).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
							}
							if ($(this).hasClass(XSM.effects.active_by_default)) $(this).addClass(XSM.effects.active);
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
			var debug_this = 0;
			//todo: error handling & logic for objects vs arrays
			var selectors = ( is_array(selector) ) ? selector : [selector];
			for (var i in selectors) {
				var sel = selectors[i];
				var offset = $(sel).offset();

				var dims = {width: Math.floor($(sel).outerWidth() + px_to_int($(sel).css("padding-left"))),
					height: Math.floor($(sel).outerHeight() + px_to_int($(sel).css("padding-top")))};
				var styles = {position: "fixed", top: offset.top, left: offset.left, height: dims.height, width: dims.width};
				if (debug_this > 1) pr(styles);
				$(sel).css(styles).addClass(XSM.effects.fastened);
			}
			return  (is_array(selector) ) ? selector : $(selector);
		},
		multi_activize: function (element) {
			if ($(element).hasClass('active')) {
				$(element).removeClass('active').addClass('inactive')
					.children(as_class(XSM.effects.checked)).each(function () {
						$(this).removeClass(XSM.effects.checked).addClass(XSM.effects.unchecked);
					});
			} else if ($(element).hasClass('inactive')) {
				$(element).removeClass('inactive').addClass('active')
					.children(as_class(XSM.effects.unchecked)).each(function () {
						$(this).removeClass(XSM.effects.unchecked).addClass(XSM.effects.checked);
					});
			}
		},
		resize_modal: function (modal) {
			if (!modal) return;
			var modal_width;
			var modal_max_height;
			var modal_left;
			var modal_top;
			if (modal == XSM.modal.primary) {
				modal_width = 1200 / 12 * 8;
				modal_max_height = 0.8 * $(window).innerHeight();
				modal_top = 0.1 * $(window).innerHeight();
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
		toggle_topbar_hover_text: function (hover_text, state) {
			var debug_this = false;
			if ($(XSM.topbar.hover_text_label_outgoing).html() == hover_text) return;
			if (debug_this) pr([hover_text, state], "toggle_topbar_hover_text(hover_text, state)");
			$(XSM.topbar.hover_text_label_outgoing).removeClass("decay").addClass(XSM.effects.slide_right);
			$(XSM.topbar.hover_text_label_incoming).html(hover_text).removeClass(XSM.effects.true_hidden);
			setTimeout(function () {
				$(XSM.topbar.hover_text_label_incoming).removeClass(XSM.effects.slide_left);
				setTimeout(function () {
					setTimeout(function () {
						$(XSM.topbar.hover_text_label_outgoing).remove();
						setTimeout(function () {
							$(XSM.topbar.hover_text_label_incoming).removeClass("incoming").addClass("outgoing")
							$(XSM.topbar.hover_text_label).append("<span class='incoming slide-left true-hidden'></span>");
						}, 10);
					}, 280);
				}, 10);
			}, 10);
		},
		decay_topbar_hover: function () {
			var id = (new Date).getTime();
			$(XSM.topbar.hover_text_label_outgoing).addClass("decay " + id);
			setTimeout(function () {
				$(XSM.topbar.hover_text_label_outgoing + ".decay." + id).addClass(XSM.effects.fade_out);
				setTimeout(function () {
					$(XSM.topbar.hover_text_label_outgoing + ".decay." + id).replaceWith(
						"<span class='outgoing'>Halifax loves pizza and we love halifax!</span>");
				}, 300);
			}, 1000);
			return true;
		}
	};