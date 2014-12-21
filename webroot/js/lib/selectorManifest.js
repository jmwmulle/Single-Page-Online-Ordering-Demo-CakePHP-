/**
 * Created by jono on 8/22/14.
 * (X)treme(S)elector(M)anifest
 * Organization scheme:
 * globals/
 *      id/
 *      class/
 * templates/
 *      /id
 *      /class
 */

var XSM = {
	global:{
		activizing_list: "ul.activizing li",
		ajaxLink:".ajax-link",
		detachable: ".detach",
		imageQueue: "#image-loading-queue",
		loadingScreen: "#loadingScreen",
		multi_activizing: ".multi-activizing",
		preserve_aspect_ratio: ".preserve-aspect-ratio"

	},
	effects: {
		active: "active",
		checked: "icon-checked",
		disabled: "disabled",
		enabled: "enabled",
		exposed: "exposed",
		inactive: "inactive",
		fade_out: "fade-out",
		flipped_x: "flipped-x",
		flipped_y: "flipped-y",
		float_label: "float-labeled",
		solidify: "solidify",
		transitioning: "transitioning",
		unchecked: "icon-unchecked"
	},
	load: {
		dismissLSButton: "#dismiss-loading-screen",
		pizzaLoaderGIF: "#pizza-loader-gif",
		loadingMessage: "#loading-message",
		readyMessage: "#ready-message"
	},
	menu: {
		active_orb_name_3d_context: "#active-orb-name-3d-context",
		active_orb_name_front_face: "#active-orb-name-front-face",
		active_orb_name_back_face: "#active-orb-name-back-face",
		active_orbs_menu: "#active-orbs-menu",
		active_orb: ".orb-card-refresh.active",
		active_orbs_menu_item: "#active-orbs-menu li a",
		add_to_cart_hook:".add-to-cart",
		cancel_order_button: "#cancel-order-button",
		confirm_order_button: "#confirm-order-button",
		float_label: "#float-label",
		orb_card_back: "#orb-card-back",
		ord_card_back_face: ".ord-card-back-face",
		orb_card_stage: "#orb-card-stage",
		orb_card_stage_menu: "#orb-card-stage-menu",
		orb_card_refresh: ".orb-card-refresh",
		orb_card_3d_context: "#orb-card-3d-context",
		orb_card_wrapper: "#orb-card-wrapper",
		orb_opt_weight: ".orb-opt-weight",
		orb_order_form: "#orderOrbForm",
		orb_order_form_inputs: "#orderOrbForm input",
		orb_order_form_orb_opts: "#orderOrbForm input.orb-opt-weight",
		orb_order_form_price_rank: "#OrderOrbPriceRank",
		orb_order_form_prep_instrux: "#OrderOrbPreparationInstructions",
		orb_order_form_quantity: "#OrderOrbQuantity",
		orb_size_button: ".orb-size-button",
		orbcat_menu_title_header: "#orbcat-menu-title h1",
		orbcat_menu_title_subtitle: "#orbcat-menu-title h1 span",
		orbcat_refresh: ".orbcat-refresh",
		orbcat_menu: "#orbcat-menu",
		toppings_list: "#toppings-list",
		topping: ".topping",
		topping_active: ".topping.active",
		topping_icon: ".topping-coverage",
		topping_icon_active: ".topping-coverage.active",
		topping_filter:".topping-filter",
		tiny_toppings_list: "tiny-topping-list",
		tiny_toppings_list_wrapper:"#tiny-toppings-list-wrapper",
		tiny_topping_list_item: ".tiny_topping_list_item",
		user_activity_panel: "#user-activity-panel"
	},
	modal: {
		order_modal:"#order-modal",
		link: ".modal-link",
		link_order: ".modal-link.order"
	},
	splash:{
		self:"#splash",
		cont:"#splash",
		circle:"#splash-circle",
		circleWrap:"#splash-circle-wrapper",
		detached: "#splash .detach",
		fastened:"#splash .fastened",
		logo:"#splash-logo",
		logoClone:"#splash-logo_fasten-clone",
		menu:"#menu",
		menu_wrapper:"#menu-wrapper",
		menu_spacer:"#menu-wrapper .spacer",
		modal:"#splash-modal",
		modalLoad:"*[data-splash-modal]",
		modalWrap:"#splash-modal-wrapper",
		modalContent:"#splash-modal .content",
		openingDeal:"grand-opening-deal",
		order:"#order",
		order_spacer:"#order-wrapper .spacer",
		preserve_aspect_ratio: "#splash *.preserve-aspect-ratio",
		splash_bar:"#splash-bar",
		splash_link: ".splash-link"
	},
	generated: {
		order_form_order_opt: function(opt_id) { return asId("OrderOrbOrbopts" + opt_id) }
	}
};

