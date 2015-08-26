/**
 * Created by jono on 8/12/15.
 */
XT.host = XT.data.host_root_dirs[XT.host];

//$(window).on(C.ORBCARD_ANIMATION_COMPLETE, function(e) { pr(e, "orb_anim");});
$(document).ready( function() {
	if (XT.is_vendor_ui) {
		XT.vendor_ui = xt_vendor_ui;
		XT.vendor_ui.init();
	}
	XT.sauce_id = 4;
	XT.cart = new XtremeCart();
	XT.router = new XtremeRouter();
	XT.layout = new XtremeLayout()
	XT.orbcard = new Orbcard( $(XSM.menu.orb_order_form_orb_id).val() );
	XT.menu = new XtremeMenu();

	XT.orbcard.init_DOM();
	XT.orbcard.menu.init_DOM();
});
