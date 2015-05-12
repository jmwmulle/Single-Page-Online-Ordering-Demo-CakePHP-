/**
 * Created by jono on 4/30/15.
 */
var xbs_vendor_menu = {
	data: {
		disabled_orbopts: {}
	},
	init: function () {
		$(XSM.vendor_ui.ui_tabs).tabs();
		XBS.vendor_menu.launch_editor();
	},
	launch_editor: function () {
		/**
		 * DOUBLESTRING INPUT DEFINITION
		 */
//		$.editable.addInputType('DoubleString', {
//			element: function (settings, original) {
//				var id = $(original).attr('id');
//				var hidden = $("<input class='hidden' type='hidden'>");
//				$(this).append(hidden);
//				var visible = $("<div>")
//					.append($("<label>Label</label>"))
//					.append($('<input class="dict" type="text" id="' + id + '-dict">'))
//					.append($('</br>'))
//					.append($('<label>Price</label>'))
//					.append($('<input class="list" type="text" id="' + id + '-list">'));
//				$(this).append(visible);
//				return hidden;
//			},
//			content: function (string, settings, original) {
//				string = $.parseHTML(string);
//				if (string.length > 1) {
//					var value = {PriceDict: null, PriceList: null}
//					for (var i in string) if (string[i].nodeName == "SPAN") value[$(string[i]).data("controller")] = $(string[i]).data('value');
//					$($(this).find("#" + $(original).attr('id') + "-dict")[0]).val(value.PriceDict);
//					$($(this).find("#" + $(original).attr('id') + "-list")[0]).val("$" + value.PriceList.toFixed(2));
//				}
//				return true;
//			},
//			buttons: function (settings, original) {
//				$(this).append( $("<button type='submit' class='tiny expand'>Save</button>") )
//					   .append( $("<button type='cancel' class='tiny expand'>Cancel</button>") )
//			},
//			submit: function (settings, original) {
//				var dict = $(".dict", this).val();
//				var list = $(".list", this).val();
//				var orb = XBS.vendor_menu.orb_id_from_cell(original);
//				$(".hidden", this).val(JSON.stringify({orb: orb, dict: dict, list: list}));
//			}
//		});

		$(XSM.vendor_ui.menu_table).dataTable({ bJQueryUI: true });
//								   .makeEditable(
//			{
//				sUpdateURL: XBS.vendor_menu.update_row,
//				aoColumns: [
//				/** Column: NAME **/
//					{
//						indicator: "Updating Name...",
//						tooltip: "Double click to change the name of this menu item.",
//						submit: "Save"
//					},
//				/** Column: DESCRIPTION **/
//					{
//						tooltip: "Double click to change the description customers see at the top of item card on the website.",
//						width: 200,
//						type: "textarea",
//						submit: "Save",
//					},
//				/** Column: ORBCAT **/
//					{
//						tooltip: "Double click to choose the section of the menu this item belongs under.",
//						width:200,
//						type: "select",
//						data: {1:"APPETIZERS",2:"ASSORTED FINGERS",3:"DRINKS & SAUCES",4:"BURGERS",5:"CHICKEN & CHIPS",6:"DESSERTS",7:"DONAIRS",8:"FISH & CHIPS",9:"FRIES & POUTINES",10:"PANZAROTTIS",11:"PASTA",12:"PITAS & SANDWICHES",13:"PIZZAS",14:"PIZZAS",15:"PIZZAS",16:"PIZZAS",17:"PIZZAS",18:"PIZZAS",19:"PIZZAS",20:"SALADS",21:"SUBS",22:"SUBS",23:"XTREME DEALS"}
//					},
//				/** Column: PRICE_1 **/
//					{
//						type: "DoubleString",
//						width: 100,
//						submit: "Save"
//					},
//				/** Column: PRICE_2 **/
//					{
//						type: "DoubleString",
//						width: 100
//					},
//				/** Column: PRICE_3 **/
//					{
//						type: "DoubleString",
//						width: 100
//					},
//				/** Column: PRICE_4 **/
//					{
//						type: "DoubleString",
//						width: 100
//					},
//				/** Column: PRICE_5 **/
//					{
//						type: "DoubleString",
//						width: 100
//					},
//				    null
//				]
//			}
//		);
	},
	edit_orb: function (orb_id, attribute, target_element) {
		pr({orb_id: orb_id, attribute: attribute, target_element: target_element}, "edit_orb()");
		var replace_with = null;
		var save_button = $("<a>").attr(
			{
				href: "#",
				"data-route": ['update_menu', orb_id, attribute].join(C.DS)
			})
			.html('<span class="text"><tiny>Save</tiny></span>')
			.addClass("modal-button sml bisecting confirm right");
		var cancel_button = $("<a>").attr(
			{
				href: "#",
				"data-route": ['orb_config', 'restore', orb_id, attribute].join(C.DS)
			})
			.html('Cancel')
			.addClass("modal-button sml bisecting cancel left");
		switch (attribute) {
			case 'title':
				var id = [orb_id, "editing", attribute].join("_");
				replace_with = $("<input />").attr({
					type: 'text',
					id: id,
				}).val(target_element.innerHTML);
				$(target_element).html('')
					.data('restore', target_element.innerHTML)
					.append([replace_with, save_button, cancel_button]);
				break;
		}
	},
	toggle_orbopt_group: function (orbcat_id) {
		orbcat_id = Number(orbcat_id);
		$("li.orbopt", XSM.modal.primary).each(function () {
			var data = $(this).data();
			if (in_array(orbcat_id, data.groups)) {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "orbopt_config/set_opt_state/" + data.orbopt + "/active", trigger: {}});
			} else {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "orbopt_config/set_opt_state/" + data.orbopt + "/inactive", trigger: {}});
			}
		});
	},
	set_orbopt_state: function (opt_id, state, label, input) {
		var opt_label_wrapper = label ? label : "#orbopt-" + opt_id + "-label";
		var opt_label = opt_label_wrapper + " span";
		var opt_input = input ? input : "li[data-orbopt='" + opt_id + "'] input";
		if (state == XSM.effects.active) {
			$(opt_label_wrapper, XSM.modal.primary).addClass(XSM.effects.active);
			$(opt_label, XSM.modal.primary).removeClass(XSM.effects.secondary).addClass(XSM.effects.success);
			$(opt_input, XSM.modal.primary).val(1);
		}
		if (state == XSM.effects.inactive) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(XSM.effects.active);
			$(opt_label, XSM.modal.primary).removeClass(XSM.effects.success).addClass(XSM.effects.secondary);
			$(opt_input, XSM.modal.primary).val(0);
		}
	},
	toggle_orbopt: function (opt_id) {
		var opt_label = "#orbopt-" + opt_id + "-label";
		var opt_input = "li[data-orbopt='" + opt_id + "'] input";
		var state = $(opt_label, XSM.modal.primary).hasClass(XSM.effects.active) ? XSM.effects.inactive : XSM.effects.active;
		XBS.vendor_menu.set_orbopt_state(opt_id, state, opt_label, opt_input)
	},
	toggle_filter: function (filter_id) {
		var filter = "#orbopt-flag-" + filter_id;
		if ($(filter, XSM.modal.primary).hasClass(XSM.effects.active)) {
			$(filter).removeClass(XSM.effects.active);
		} else {
			$(filter).addClass(XSM.effects.active);
		}
		XBS.vendor_menu.filter_opts()
	},
	filter_opts: function () {
		var new_list = $("<ul class='large-block-grid-6'>");
		var active_flags = new Array();
		$("dd.orbopt-flag.active").each(function () { active_flags.push(Number($(this).data('id'))); });
		$("li.orbopt", XSM.modal.primary).each(function () {
			var flags = $(this).data('flags');
			var active = false;
			for (var i in flags) {
				if (active_flags.indexOf(flags[i]) > -1) active = true;
			}
			if (active) {
				$(this).removeClass(XSM.effects.hidden);
			} else {
				$(this).addClass(XSM.effects.hidden)
			}
			$(document).foundation();
		});
	},
	orbopt_config: function (orb_id) {

	}
}