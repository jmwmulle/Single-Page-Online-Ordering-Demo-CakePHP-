/**
 * Created by jono on 8/22/14.
 * (X)treme(S)elector(M)anifest
 *
 */

var XSM = {
	global:{
		activizing_list: "ul.activizing li",
		active_list_item: "li.active",
		ajaxLink:".ajax-link",
		detachable: ".detach",
		footer: "footer",
		imageQueue: "#image-loading-queue",
		loadingScreen: "#loadingScreen",
		loading: "#topbar h4.loading",
		multi_activizing: ".multi-activizing",
		page_content: "#page-content",
		preserve_aspect_ratio: ".preserve-aspect-ratio",
		route: "*[data-route]",
		topbar: "#topbar",
		topbar_cart_button: "#top-bar-view-cart"
	},
	effects: {
		active: "active",
		activizing: "activizing",
		checked: "icon-checked",
		active_by_default: "default",
		disabled: "disabled",
		enabled: "enabled",
		exposed: "exposed",
		inactive: "inactive",
		hidden: "hidden",
		fade_out: "fade-out",
		loading: "loading",
		slide_right: "slide-right",
		slide_left: "slide-left",
		slide_up: "slide-up",
		slide_down: "slide-down",
		flipped_x: "flipped-x",
		flipped_y: "flipped-y",
		float_label: "float-labeled",
		overlay: "overlay",
		solidify: "solidify",
		share_icon: "icon-orb-card-share",
		share_icon_reveal: "icon-orb-card-share-reveal",
		register_icon: "icon-orb-card-register",
		register_reveal: "icon-orb-card-register-reveal",
		stash: "stash",
		success: "success",
		swap_width: "swap-width",
		transitioning: "transitioning",
		true_hidden: "true-hidden",
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
		orb_card_row_1: "#orb-card-row-1",
		orb_card_row_2: "#orb-card-row-2",
		orb_card_row_3: "#orb-card-row-3",
		orb_card_stage: "#orb-card-stage",
		orb_card_stage_menu: "#orb-card-stage-menu",
		orb_card_stage_menu_wrapper: "#orb-card-stage-menu-wrapper",
		orb_card_3d_context: "#orb-card-3d-context",
		orb_card_wrapper: "#orb-card-wrapper",
		orb_order_form: "#orderOrbForm",
		orb_order_form_orb_id: "#OrderOrbId",
		orb_order_form_inputs: "#orderOrbForm input",
		orb_order_form_orb_opts: "#orderOrbForm input.orb-opt-weight",
		orb_order_form_price_rank: "#OrderOrbPriceRank",
		orb_order_form_prep_instrux: "#OrderOrbPreparationInstructions",
		orb_order_form_quantity: "#OrderOrbQuantity",
		orb_size_button: ".orb-size-button",
		orbcat_menu_title_header: "#orbcat-menu-title h1",
		orbcat_menu_title_subtitle: "#orbcat-menu-title h1 span",
		orbcat_menu: "#orbcat-menu",
		orb_opt: ".orb-opt",
		orb_opt_active: ".orb-opt.active",
		orb_opt_container: "#orb-opts-container",
		orb_opt_icon: ".orb-opt-coverage",
		orb_opt_icon_active: ".orb-opt-coverage.active",
		orb_opt_filter: ".orb-opt-filter",
		orb_opt_filters: "#orb-opt-filters",
		orb_opt_filter_span: ".orb-opt-filter span",
		orb_opt_filter_span_checked: ".orb-opt-filter span.icon-checked",
		orb_opt_filter_span_unchecked: ".orb-opt-filter span.icon-unchecked",
		orb_opt_filter_all: ".orb-opt-filter-all",
		orb_opt_weight: ".orb-opt-weight",
		orb_opts_menu_header: "#orb-opts-menu-header",
		registration_panel: "#orb-card-register-panel",
		register_button: "#register.orb-card-button",
		share_button: "#like.orb-card-button",
		self:"main#menu",
		social_panel: "#orb-card-social-panel",
		tiny_orb_opts_list: "tiny-orb-opt-list",
		tiny_orb_opts_list_wrapper:"#tiny-orb-opts-list-wrapper",
		tiny_orb_opts_list_item: ".tiny-orb-opts-list-item",
		user_activity_panel: "#user-activity-panel",
		user_activity_panel_items: "#user-activity-panel li"
	},
	modal: {
		close_modal: "#close-modal",
		default_content: ".default-content",
		deferred_content: ".deferred-content",
		flash: "#flash-modal",
		on_close: "#on-close",
		orb_card:"#orbcard-modal",
		overlay: "#modal-overlay-container",
		primary: "#primary-modal",
		primary_content: "#primary-modal-content",
		primary_deferred_content: "#primary-modal-content .deferred-content",
		social: "#social-modal",
		splash: "#splash-modal",
		submit_order_address: "#submit-order-address"
	},
	splash:{
		self:"#splash",
		bar_contents: "#splash-bar-content",
		content:"#splash > .content",
		circle:"#splash-circle",
		circleWrap:"#splash-circle-wrapper",
		detached: "#splash .detach",
		fastened:"#splash .fastened",
		logo:"#splash-logo",
		logo_wrapper:"#splash-logo-wrapper",
		logoClone:"#splash-logo_fasten-clone",
		menu:"#menu",
		menu_wrapper:"#menu-wrapper",
		menu_spacer:"#menu-wrapper .spacer",
		modal:"#splash-modal",
		modalWrap:"#splash-modal-wrapper",
		modalContent:"#splash-modal .content",
		openingDeal:"grand-opening-deal",
		order:"#order",
		order_delivery: "#splash-order-delivery",
		order_pickup: "#splash-order-pickup",
		order_spacer:"#order-wrapper .spacer",
		preserve_aspect_ratio: "#splash *.preserve-aspect-ratio",
		splash_bar:"#splash-bar",
		splash_bar_wrapper:"#splash-bar-wrapper",
		splash_link: ".splash-link"
	},
	topbar: {
		hover_text_link: ".topbar-social  a",
		hover_text_label: "#topbar-hover-text-label",
		hover_text_label_incoming: "#topbar-hover-text-label span.incoming",
		hover_text_label_outgoing: "#topbar-hover-text-label span.outgoing"
	},
	footer: {
		self:"footer#footer"
	},
	generated: {
		order_form_order_opt: function(opt_id) { return asId("OrderOrbOrbopts" + opt_id) },
		orb_card_row_content: function(row) { return "#orb-card-row-"+row+" div.orb-card-content" },
		orb_opt_id: function(opt_id) { return asId("orb-opt-coverage-" + opt_id); },
		orb_opt_icon: function(opt_id, weight) {
			return $(opt_id).find(XSM.menu.orb_opt_icon + '[data-weight="' + weight + '"]')[0];
		},
		order_address_button: function(route) {
			var message;
			if (route == "menu") message = "OK! TO THE FOOD!";
			if (route == "review") message = "BACK TO CHECKOUT";

			return '<a href="#" id="submit-order-address" data-route="confirm_address/submit/'+route+'"' +
					' class="box downward rel modal-submit">' + message + '</a>';
		}
	}
};

