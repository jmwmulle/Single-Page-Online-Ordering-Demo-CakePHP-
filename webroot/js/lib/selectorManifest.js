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
		ajaxLink:".ajax-link",
		autoScrollers: "*[data-scroll-to]",
		detachable: ".detach",
		imageQueue: "#image-loading-queue",
		loadingScreen: "#loadingScreen",
		preserve_aspect_ratio: ".preserve-aspect-ratio",
		scrollable: "*[data-scrolling-target]",
		activizing_list: "ul.activizing li",
		multi_activizing: ".multi-activizing"
	},
	effects: {
		transitioning: "transitioning",
		flipped_x: "flipped-x",
		flipped_y: "flipped-y",
		float_label: ".float-labeled",
		solidify: ".solidify",
		exposed: "exposed",
		active: "active",
		inactive: "inactive",
		enabled: "enabled",
		disabled: "disabled",
		checked: "icon-checked",
		unchecked: "icon-unchecked"
	},
	load: {
		dismissLSButton: "#dismiss-loading-screen",
		pizzaLoaderGIF: "#pizza-loader-gif",
		loadingMessage: "#loading-message",
		readyMessage: "#ready-message"
	},
	menu: {
		active_orb_name_front_face: "#active-orb-name-front-face",
		active_orb_name_back_face: "#active-orb-name-back-face",
		active_orbs_menu: "#active-orbs-menu",
		active_orbs_menu_item: "#active-orbs-menu li a",
		add_to_cart_hook:".add-to-cart",
		orb_card_stage: "#orb-card-stage",
		orb_card_refresh: ".orb-card-refresh",
		orbcat_menu_title_header: "#orbcat-menu-title h1",
		orbcat_menu_title_subtitle: "#orbcat-menu-title h1 span",
		orbcat_refresh: ".orbcat-refresh",
		orb_card_3d_context: "#orb-card-3d-context",
		active_orb_name_3d_context: "#active-orb-name-3d-context",
		toppings_list: "#toppings-list",
		topping: ".topping",
		topping_active: ".topping.active",
		topping_icon: ".topping-coverage",
		topping_icon_active: ".topping-coverage.active",
		topping_filter:".topping-filter",
		orb_opt_weight: ".orb-opt-weight"
	},
	main: {
		primaryContent: "#primary-content",
		subNav:"#subnav",
		toc: "#subnav-toc",
		tocListItem:"#subnav-toc li",
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
	}
};

