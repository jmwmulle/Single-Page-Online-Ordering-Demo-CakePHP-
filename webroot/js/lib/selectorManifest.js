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
		jsLink:".js-link",
		preserveAS: ".preserve-aspect-ratio",
		scrollable: "*[data-scrolling-target]",
		activizing_list: "ul.activizing li",
		multi_activizing: ".multi-activizing"
	},
	effects: {
		flipped: ".flipped",
		float_label: ".float-labeled",
		solidify: ".solidify"
	},
	load: {
		dismissLSButton: "#dismiss-loading-screen",
		pizzaLoaderGIF: "#pizza-loader-gif",
		loadingMessage: "#loading-message",
		readyMessage: "#ready-message"
	},
	menu: {
		orb_card_stage: "#orb-card-stage",
		orb_card_refresh: ".orb-card-refresh",
		add_to_cart_hook:".add-to-cart",
		orb_card_3d_pane_selector: "#orb-card-3d-pane"
	},
	main: {
		primaryContent: "#primary-content",
		subNav:"#subnav",
		toc: "#subnav-toc",
		tocListItem:"#subnav-toc li",
	},
	splash:{
		self:"#splash",
		cont:"#splash-pane",
		circle:"#splash-circle",
		circleWrap:"#splash-circle-wrapper",
		detached: "#splash .detach",
		fastened:"#splash .fastened",
		logo:"#splash-logo",
		logoClone:"#splash-logo_fasten-clone",
		menu:"#menu",
		menuWrap:"#menu-wrapper",
		menuSpacer:"#menu-wrapper .spacer",
		modal:"#splash-modal",
		modalLoad:"*[data-splash-modal]",
		modalWrap:"#splash-modal-wrapper",
		modalContent:"#splash-modal .content",
		openingDeal:"grand-opening-deal",
		order:"#order",
		orderSpacer:"#order-wrapper .spacer",
		preserveAS: "#splash *.preserve-aspect-ratio",
		splashBar:"#splash-bar"
	}
};

