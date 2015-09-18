/**
 * Created by jono on 1/24/15.
 */
XtremeLayout = function() {};

XtremeLayout.prototype = {
	constructor: XtremeLayout,
	page_height: undefined,
	loader: {
		element: undefined,
		init: function (self) {
			self.loader.element = $("#loading-img")[0];
			self.loader.position = function() {
				if (XT.page_name == "xtreme-pos" ) return;
				$(self.loader.element).css("left", (window.innerWidth - $(self.loader.element).innerWidth()) / 2)
			};
			self.loader.hide = function () {
				$(self.loader.element).addClass(FX.fade_out);
				setTimeout(function () {$(self.loader.element).addClass(FX.hidden)}, 120);
			};
			self.loader.show = function () {
				$(self.loader.element).removeClass(FX.hidden);
				setTimeout(function () {$(self.loader.element).removeClass(FX.fade_out)}, 10);
			};
			self.loader.position();
		},
		position: undefined,
		hide: undefined,
		show: undefined
	},
	init: function () {
		var self = this;
		this.loader.init(this);
		this.jq_binds();
		if (this.page_name == XT.page_name.splash) $(as_class(FX.detach)).each(function () { XBS.layout.detach(this);});
		this.page_height = window.innerHeight - ($(XSM.global.topbar).innerHeight() + 3 * C.REM) + C.PX;
		$(XSM.global.page_content).css({minHeight: self.page_height});
		$(FX.fill_parent).each(function() { self.match_parent_dimensions(this)});

		// TODO: this should be an effect chain
		setTimeout(function () {
			$(XSM.topbar.social_loading).addClass(FX.fade_out);
			setTimeout(function () {
				$(XSM.topbar.social_loading).hide();
				setTimeout(function () {
					$(XSM.topbar.icon_row).show();
					setTimeout(function () {
						$(XSM.topbar.icon_row).removeClass(FX.fade_out);
						$(XSM.topbar.icon_row).removeClass(FX.true_hidden);
					}, 30);
				}, 30);
			}, 300);
		}, 800);

		if ( is_mobile() ) return;
		$(XSM.global.footer).css({top: $(XSM.global.page_content).innerHeight()});
		pr(XT.page_name);
//		if (XT.page_name != "xtreme-pos" && XT.page_name != "Vendor Interface" && XT.page_name != "countdown") this.fasten(XSM.menu.self).css({overflow: "hidden"});

		// both of these pertain to the countdown page from launch
		setTimeout(function() {
			$("p.banner").removeClass(FX.fade_out);
			$("#special-box").removeClass(FX.fade_out);
		}, 1000);
	},
	jq_binds: function() {
		var self = this;
		// bind_activizing_lists
		$(C.BODY).on(C.CLK, XSM.global.activizing_list, function (e) {
			e.stopPropagation();
			self.activize(e.currentTarget) });

		// bind_multi_activizing_siblings
		$(C.BODY).on(C.CLK, XSM.global.multi_activizing, function (e) { self.multi_activize(e.currentTarget) });

		// bind_float_labels:
		$(C.BODY).on(C.MOUSEENTER, as_class(FX.float_label), null, function (e) {
			self.toggle_float_label($(e.currentTarget).data('float-label'), C.SHOW);
		});
		$(C.BODY).on(C.MOUSEOUT, as_class(FX.float_label), null, function (e) {
			self.toggle_float_label($(e.currentTarget).data('float-label'), C.HIDE);
		});

		// bind_splash_links
		$(C.BODY).on(C.CLK, XSM.splash.splash_link, null, function (e) {
			XBS.splash.redirect($(e.currentTarget).data('url'));
		});

		// bind_topbar_hover_links
		$(C.BODY).on(C.MOUSEENTER, XSM.topbar.hover_text_link, null, function (e) {
			if (!$(e.currentTarget).hasClass(FX.disabled)) {
				self.toggle_topbar_hover_text($(e.currentTarget).data('hover_text'));
			}
		});
		$(C.BODY).on(C.MOUSEOUT, XSM.topbar.hover_text_link, null, function (e) {
			if (!$(e.currentTarget).hasClass(FX.disabled)) self.decay_topbar_hover();
		});
		// window_resize_listener
		if (window.page_name == XSM.page_name.splash) $(window).on("resize", XBS.splash.render);
	},

	/**
	 *  Uses 'respect' (ie. X or y) data-attribute of an element to force it's height/width to satisfy ratio given in
	 *  aspect-ratio data attribute.
	 *
	 * @param targets
	 * @returns {boolean}
	 */
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
	activize: function (element, kill) {
		if (isEvent(arguments[0])) element = element.currentTarget;
		if ($(element).hasClass(FX.inactive)) {
			$(element).removeClass(FX.inactive)
				.addClass(FX.active)
				.siblings(XSM.global.active_list_item)
				.each(function () {
					$(this).removeClass(FX.active).addClass(FX.inactive);
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
	// this was just for launch day! delete if after sept.19/2015
	resolve_countdown: function() {
		$.get("deferred-menu", function(resp) {
			resp = $.parseHTML(resp);
			$("#desktop-content").append(resp);
			$("#countdown", "body").addClass(FX.slide_down);
			$("#count-down-wrapper").append(
				$("<h1>").addClass(FX.slide_up).attr('id', "launch-time-banner").html("OK... Here we go!").css({
					fontSize:"96px",
					width:"100%",
					texAlign: "center",
				}));
			setTimeout(function() { $("#launch-time-banner", "body").removeClass(FX.slide_up)}, 500);
			setTimeout(function() { $("#count-down-wrapper").addClass(FX.fade_out) }, 1500);
			setTimeout(function() { $("#topbar").removeClass(FX.fade_out) }, 1800);
			setTimeout(function() { $("#menu").removeClass(FX.fade_out) }, 2000);
			setTimeout(function() { $("#count-down-wrapper").remove(); }, 2000);


		});
	},

	fasten: function (selector) {
		var debug_this = 0;
		//todo: error handling & logic for objects vs arrays
		var selectors = ( is_array(selector) ) ? selector : [selector];
		for (var i in selectors) {
			var sel = selectors[i];
			var offset = $(sel).offset();

			var dims = {width: Math.floor( $(sel).outerWidth() + px_to_int($(sel).css("padding-left"))),
				height: Math.floor( $(sel).outerHeight() + px_to_int($(sel).css("padding-top")))};
			var styles = {position: "fixed", top: offset.top, left: offset.left, height: dims.height, width: dims.width};
			if (debug_this > 1) pr(styles);
			$(sel).css(styles).addClass(FX.fastened);
		}
		return  (is_array(selector) ) ? selector : $(selector);
	},
	hovertext_switch: function(target) {
		var incoming = $("span.text.inactive", target)[0];
		var outgoing = $("span.text.active", target)[0];
		$(outgoing, target).addClass(FX.fade_out);
		setTimeout(function() {
			$(incoming).removeClass([FX.hidden, FX.inactive].join(" ")).addClass(FX.active);
			$(outgoing).removeClass(FX.active).addClass([FX.hidden, FX.inactive].join(" "));
			setTimeout( function() {
				$(incoming).removeClass(FX.fade_out);
			}, 10)
		}, 300);
	},
	match_parent_dimensions: function(element) {
		pr("matching parent dimensions");
		var parent = $(element).parent();
		$(element).css({height: $(parent).innerHeight(), width: $(parent).innerWidth()});
	},
	multi_activize: function (element) {
		if ($(element).hasClass('active')) {
			$(element).removeClass('active').addClass('inactive')
				.children(as_class(FX.checked)).each(function () {
					$(this).removeClass(FX.checked).addClass(FX.unchecked);
				});
		} else if ($(element).hasClass('inactive')) {
			$(element).removeClass('inactive').addClass('active')
				.children(as_class(FX.unchecked)).each(function () {
					 $(this).removeClass(FX.unchecked).addClass(FX.checked);
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
		if (state == C.SHOW) $(XSM.menu.float_label).html(str_to_upper(label)).addClass(FX.exposed);
		if (state == C.HIDE) $(XSM.menu.float_label).removeClass(FX.exposed).html('');

		return true;
	},
	toggle_loading_screen: function () { $(XSM.global.loadingScreen).fadeToggle(); },
	toggle_topbar_hover_text: function (hover_text, state) {
		var debug_this = false;
		if ($(XSM.topbar.hover_text_label_outgoing).html() == hover_text) return;
		if (debug_this) pr([hover_text, state], "toggle_topbar_hover_text(hover_text, state)");
		$(XSM.topbar.hover_text_label_outgoing).removeClass("decay").addClass(FX.slide_right);
		$(XSM.topbar.hover_text_label_incoming).html(hover_text).removeClass(FX.true_hidden);
		setTimeout(function () {
			$(XSM.topbar.hover_text_label_incoming).removeClass(FX.slide_left);
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
			$(XSM.topbar.hover_text_label_outgoing + ".decay." + id).addClass(FX.fade_out);
			setTimeout(function () {
				$(XSM.topbar.hover_text_label_outgoing + ".decay." + id).replaceWith(
					"<span class='outgoing'>Halifax loves pizza and we love halifax!</span>");
			}, 300);
		}, 1000);
		return true;
	}
};
