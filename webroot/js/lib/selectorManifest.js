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
		activizing: "activizing",
		checked: "icon-checked",
		disabled: "disabled",
		enabled: "enabled",
		exposed: "exposed",
		inactive: "inactive",
		hidden: "hidden",
		fade_out: "fade-out",
		slide_right: "slide-right",
		slide_left: "slide-left",
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
		active_orbcat_menu_header: "#active-orbcat-menu-header",
		active_orb: ".active-orbcat-item.active",
		active_orbcat_item: ".active-orbcat-item",
		active_orbs_menu_item: "#active-orbs-menu li a",
		add_to_cart:".add-to-cart",
		cancel_order_button: "#cancel-order-button",
		confirm_order_button: "#confirm-order-button",
		float_label: "#float-label",
		orb_card_back: "#orb-card-back",
		ord_card_back_face: ".ord-card-back-face",
		orb_card_content_container: ".orb-card-content-container",
		orb_card_stage: "#orb-card-stage",
		orb_card_stage_menu: "#orb-card-stage-menu",
		orb_card_stage_menu_wrapper: "#orb-card-stage-menu-wrapper",
		orb_card_3d_context: "#orb-card-3d-context",
		orb_card_wrapper: "#orb-card-wrapper",
		orb_opt_weight: ".orb-opt-weight",
		orb_opt_container: "#orb-opts-container",
		orb_opts_menu_header: "#orb-opts-menu-header",
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
		orb_opt_filters: "#orb-opt-filters",
		orb_opt: ".orb-opt",
		orb_opt_active: ".orb-opt.active",
		orb_opt_icon: ".orb-opt-coverage",
		orb_opt_icon_active: ".orb-opt-coverage.active",
		orb_opt_filter:".orb-opt-filter",
		tiny_orb_opts_list: "tiny-orb-opt-list",
		tiny_orb_opts_list_wrapper:"#tiny-orb-opts-list-wrapper",
		tiny_orb_opts_list_item: ".tiny-orb-opts-list-item",
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

