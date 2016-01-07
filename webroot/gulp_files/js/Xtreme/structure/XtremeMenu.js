/**
 * Created by jono on 1/24/15.
 */

XtremeMenu = function() {}

XtremeMenu.prototype = {
	constructor: XtremeMenu,
	DOM: {},
	init_DOM: function() {
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
					XT.cart.add_to_cart();
					$(XSM.modal.orb_card).show('clip');
					$(XSM.topbar.topbar_cart_button).show()
					setTimeout(function () {
						$(XSM.topbar.topbar_cart_button).removeClass(FX.fade_out);
					}, 300);
				}
			}
		});
	},
	stash: function () {
			var orb_card_timeout = 0;
			if (XT.orbcard.exposed_face == C.BACK) {
				orb_card_timeout = 960;
				XT.orbcard.show_face(C.FRONT);
			}
			setTimeout(function () { $(XSM.menu.orb_card_wrapper).addClass([FX.slide_left, FX.fade_out].join(" ")) }, 600);
			setTimeout(function () { $(XSM.menu.monthly_content_wrapper).addClass(FX.slide_down) }, orb_card_timeout + 600);
			setTimeout(function () { $(XSM.menu.orbcat_menu).addClass([FX.slide_right, FX.fade_out].join(" ")) }, 300);
			setTimeout(function () { $(XSM.menu.user_activity_panel).addClass(FX.slide_up) }, orb_card_timeout);
		return orb_card_timeout + 900;
		},
	unstash: function () {
		if ( !$(XSM.menu.orbcat_menu).hasClass(FX.slide_right) ) return;
		$(XSM.menu.orbcat_menu).removeClass([FX.slide_right, FX.fade_out].join(" "));
		$(XSM.menu.monthly_content_wrapper).removeClass(FX.slide_down);
		setTimeout(function () { $(XSM.menu.user_activity_panel).removeClass(FX.slide_up) }, 300);
		setTimeout(function () { $(XSM.menu.orb_card_wrapper).removeClass([FX.slide_left, FX.fade_out].join(" ")) }, 600);
		XT.cart.set_order_method();
		XT.orbcard.reset_stage();

		return 600
	},
	specials_view: function() {
		$("#launch-special", XSM.menu.monthly_content_wrapper).addClass(FX.fade_out);

		setTimeout( function() {
			$(XSM.menu.orbcat_menu).addClass(FX.condensed);
			$(XSM.menu.monthly_content_wrapper).addClass(FX.expanded); },
		300);
	},

	update_active_orbcat: function(orbcat_str) {
		$("#active-orbcat-title").addClass(FX.condensed);
		setTimeout(function() { $("#active-orbcat-title").addClass(FX.fade_out); }, 310);
		setTimeout(function() { $("#active-orbcat-title").html(orbcat_str); }, 630);
		setTimeout(function() { $("#active-orbcat-title").addClass(FX.salient); }, 630);
		setTimeout(function() { $("#active-orbcat-title").removeClass(FX.condensed); }, 650);
		setTimeout(function() { $("#active-orbcat-title").removeClass(FX.fade_out); }, 975);
		setTimeout(function() { $("#active-orbcat-title").removeClass(FX.salient); }, 1200);

	}
}
