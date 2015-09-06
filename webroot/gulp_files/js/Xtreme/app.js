/**
 * Created by jono on 8/12/15.
 */
XT.host = XT.data.host_root_dirs[XT.host];

//$(window).on(C.ORBCARD_ANIMATION_COMPLETE, function(e) { pr(e, "orb_anim");});
$(document).ready( function() {
	XT.development_mode = true;
	XT.router = new XtremeRouter();
	XT.layout = new XtremeLayout();
	pr(XT.page_name, "pn");
	switch (XT.page_name) {
		case "Vendor Interface":
			XT.vendor_ui = xt_vendor_ui;
			XT.vendor_ui.init();
			break;
		case "xtreme-pos":
			XT.pos = new XtremePOS();
			break;
		default:
			pr("hi");
			XT.system;
			$.get([XT.host, "system", -1, false, 0].join(C.DS), function(response) {
				pr(response);
				XT.router.cake_ajax_response(response, {
					callback: function(response) {
						XT.system = response.data.system;
						$(XT).trigger(C.SYSTEM_READY);
					}
				}, true, true)
			});
			XT.sauce_id = 4;
			XT.cart = new XtremeCart();
			XT.orbcard = new Orbcard( $(XSM.menu.orb_order_form_orb_id).val() );
			XT.menu = new XtremeMenu();
			XT.orbcard.init_DOM();
			XT.orbcard.menu.init_DOM();
			break;
	}
	if (in_array(XT.page_name, ["Vendor Interface", "xtreme-pos"])) XT.layout.init()
	$(XT).on(C.SYSTEM_READY, function() { pr("hi mom"); XT.layout.init() });

	if (XT.page_name == "menu") {
		if (window.addEventListener) {
			XT.kkeys = [], XT.konami = "38,38,40,40,37,39,37,39,66,65";
			window.addEventListener("keydown", function (e) {
				XT.kkeys.push(e.keyCode);
				if (XT.kkeys.toString().indexOf(XT.konami) >= 0) {
					var nes = $(function () {
						new JSNES({
							'swfPath': 'files/',
							'ui': $('#emulator').text('').JSNESUI({
								"Working": [
									['Dr. Mario', 'files/DrMario.nes']
								]
							})
						});
					});
					XT.kkeys = [];
				}
			}, true);
		}
	}
});
