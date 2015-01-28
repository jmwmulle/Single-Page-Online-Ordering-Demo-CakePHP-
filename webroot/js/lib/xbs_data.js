var xbs_events = {
		wakeFromSleep: eCustom("wakeFromSleep"),
		assetsLoaded: eCustom("assetsLoaded"),
		route_launched: eCustom("route_launched"),
		orb_card_refresh: eCustom(C.ORB_CARD_REFRESH),
		order_form_update: eCustom(C.ORDER_FORM_UPDATE),
		order_ui_update: eCustom(C.ORDER_UI_UPDATE),
		route_request: eCustom(C.ROUTE_REQUEST),
		orb_row_animation_complete: eCustom(C.ORB_ROW_ANIMATION_COMPLETE)
};
var xbs_data = {
		store_status: null,
		host_root_dirs: {
			xDev: "",
			xProd: "",
			xLoc: "xtreme"
		},
		orb_card_out_face: C.FRONT_FACE,
		tiny_orb_opts_list: {},
		orb_opts: {
		},
		orb_opt_filters: {
		},
		order: {
			order_method: C.JUST_BROWSING,
			address: {
				address_1: null,
				address_2: null,
				postal_code: null,
				instructions: null
			},
			payment: null
		},
		current_orb_card: null,
		delays: {
			global_transition: 300,
			default_js_refresh: 30,
			orb_card_row_toggle: 800,
			menu_stash_delay: 900,
			fold_splash: 3000,
			splash_set_order_method: 3900
		},
		partial_orb_configs: {
		},
		user: {
			name: {
				first: null,
				last: null
			},
			address: {
				address_1: null,
				address_2: null,
				postal_code: null,
				instructions: null
			}
		},
	cfg: {
		root: null,
		developmentMode: false,
		minLoadTime: 1,
		store_status_inspection_timeout: 61000  // ie. 61 seconds
	}
};

