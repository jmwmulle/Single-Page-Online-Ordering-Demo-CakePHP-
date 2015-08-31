/**
 * Created by jono on 8/12/15.
 */
XT.host = XT.data.host_root_dirs[XT.host];

//$(window).on(C.ORBCARD_ANIMATION_COMPLETE, function(e) { pr(e, "orb_anim");});
$(document).ready( function() {
	XT.development_mode = true;
	XT.router = new XtremeRouter();
	XT.layout = new XtremeLayout();
	if (XT.is_vendor_ui) {
		XT.vendor_ui = xt_vendor_ui;
		XT.vendor_ui.init();
		return XT.layout.init();
	}
	if (XT.page_name == "xtreme-pos") { XT.pos = new XtremePOS() };
	XT.sauce_id = 4;
	XT.cart = new XtremeCart();
	XT.orbcard = new Orbcard( $(XSM.menu.orb_order_form_orb_id).val() );
	XT.menu = new XtremeMenu();

	XT.orbcard.init_DOM();
	XT.orbcard.menu.init_DOM();

	XT.layout.init();
	if (XT.page_name == "xtreme_menu") {
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
