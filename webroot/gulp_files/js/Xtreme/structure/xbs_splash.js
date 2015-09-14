/**
 * Created by jono on 1/24/15.
 */
var xbs_splash = {
		init: function () {
			var debug_this = 0;
			if (debug_this > 0) pr("<no_args>", "splash.init()", 2);
			if (!XBS.data.cfg.is_splash) return true;
			XBS.splash.render();
			return true;
		},
		render: function () {
			$(as_class(XSM.effects.fastened)).attr('style', '').removeClass(XSM.effects.fastened);
			$(as_class(XSM.effects.detach)).attr('style', '');
			$(XSM.splash.order_spacer).css({height: $(XSM.splash.menu_wrapper).innerHeight() * C.ORDER_SPACER_FACTOR});
			$(XSM.splash.menu_spacer).css({height: $(XSM.splash.menu_wrapper).innerHeight() * C.MENU_SPACER_FACTOR});
			XBS.layout.assert_aspect_ratio(XSM.splash.preserve_aspect_ratio);

			var logo_width = $(XSM.splash.logo).innerWidth();
			var height = $(XSM.splash.order_delivery).innerHeight();
			var line_height = 1.25 * (height - 2 * 15) + C.PX;
			var max_height = $(XSM.splash.logo_wrapper).innerHeight();
			$(XSM.splash.order_delivery).css({width: logo_width, lineHeight: line_height});
			$(XSM.splash.order_pickup).css({width: logo_width, lineHeight: line_height});
			$(XSM.splash.order_delivery_wrapper).css({maxHeight: max_height});
			$(XSM.splash.order_pickup_wrapper).css({maxHeight: max_height});
			return true;
		},
		fold: function (route) {
			$.get("launch-menu", function (data) {
				$(XSM.splash.self).addClass(XSM.effects.stash);
				data = $.parseHTML(data);
				$($(data).find(XSM.menu.self)[0]).addClass(XSM.effects.true_hidden);
				$(data).appendTo(XSM.global.page_content);
				XBS.data.cfg.page_name = C.MENU;
				XBS.menu.stash_menu();
				$(XSM.menu.self).removeClass(XSM.effects.true_hidden);
				setTimeout(function () {
					$(XSM.splash.self).remove();
					XBS.menu.init();
					XBS.menu.unstash_menu();
				}, 1600);
			});
			return true;
		}
	};