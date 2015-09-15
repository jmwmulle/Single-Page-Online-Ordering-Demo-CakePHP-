/**
 * Created by jono on 8/12/15.
 */
XT.host = XT.data.host_root_dirs[XT.host];

//$(window).on(C.ORBCARD_ANIMATION_COMPLETE, function(e) { pr(e, "orb_anim");});
$(document).ready( function() {
	XT.development_mode = true;
	XT.router = new XtremeRouter();
	XT.layout = new XtremeLayout();
	switch (XT.page_name) {
		case "countdown":
			var date = new Date(2015, 8, 19);
		    var now = new Date();
		    var diff = (date.getTime()/1000) - (now.getTime()/1000);
			var clock = $('#countdown-clock').FlipClock(diff, {
				autostart:true,
				clockFace: "DailyCounter",
				countdown: true
			});
			setTimeout(function() {
				$("#countdown").on(C.MOUSEOVER, function() {
					$(".make-snazzy").addClass("snazzy");
					$("#countdown").off(C.MOUSEOVER);
				});
			}, 1000);
			$("#special-box").on(C.MOUSEENTER, function () {
				$(".make-snazzy").removeClass("snazzy");
				setTimeout(function () { $(".make-snazzy").addClass("snazzy"); }, 30);
			});


			break;
		case "Vendor Interface":
			XT.vendor_ui = xt_vendor_ui;
			XT.vendor_ui.init();
			break;
		case "xtreme-pos":
			XT.pos = new XtremePOS();
			break;
		default:
			XT.system;
			$.get([XT.host, "system", -1, 0, 0].join(C.DS), function(response) {
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
	if (in_array(XT.page_name, ["Vendor Interface", "xtreme-pos", 'countdown'])) XT.layout.init()
	$(XT).on(C.SYSTEM_READY, function() { XT.layout.init() });

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
