XT.events = {
		orb_cfg: createCustomEvent(C.ORB_CFG),
		orb_loaded: createCustomEvent(C.ORB_LOADED),
		order_form_update: createCustomEvent(C.ORDER_FORM_UPDATE),
		order_ui_update: createCustomEvent(C.ORDER_UI_UPDATE),
		route_launched: createCustomEvent("route_launched"),
		route_request: createCustomEvent(C.ROUTE_REQUEST),
		orb_row_animation_complete: createCustomEvent(C.ORB_ROW_ANIMATION_COMPLETE),
		orbcard_reset_complete: createCustomEvent(C.ORBCARD_RESET_COMPLETE),
		modal_dismissed: createCustomEvent(C.MODAL_DISMISSED)
};

XT.data = {
		debug:true,
		store_status: null,
		host_root_dirs: {
			xDev: "",
			xProd: "",
			xLoc: "/xtreme"
		},
		delays: {
			global_transition: 300,
			default_js_refresh: 30,
			orb_card_row_toggle: 800,
			menu_stash_delay: 900,
			fold_splash: 3000,
			splash_set_order_method: 3900
		},
		cfg: {
			root: null,
			developmentMode: true,
			minLoadTime: 1,
			store_status_inspection_timeout: 61000  // ie. 61 seconds
		}
};

