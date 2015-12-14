/**
 * Created by jono on 4/30/15.
 * TODO: limit menu_options tab so that "premium", "cheese" & "sauce" are mutually exclusive
 *
 */
var xt_vendor_ui = {
	orbopt_pricelist_id:-1,

	init: function () {
		XT.vendor_ui.loading_screen(0);
		XT.vendor_ui.fix_breakouts();
		$(XSM.vendor_ui.ui_tabs).tabs();
		for ( var table in {menu:null, opts:null } ) {
			XT.vendor_ui.data_tables(table);
		}
	},
	loading_screen: function(last_height) {
		var ui_height = $(XSM.vendor_ui.ui_tabs).innerHeight();
		if (last_height == ui_height) {
			$("#loading-screen").addClass(FX.fade_out);
			setTimeout(function(){ $("#loading-screen").addClass(FX.hidden); }, 300);
		} else {
			setTimeout( function() { XT.vendor_ui.loading_screen(ui_height)}, 300);
		}
	},
	fix_breakouts: function() {
		$(FX.breakout).each(function () {
			$(this).css({
				left: (0.5 * $(window).width() - 400) + "px", // all breakouts are 800px wide, vendor.scss ~L514
				top: "300px"
			})
		});
	},
	data_tables: function (table) {
		var tables = {
			menu: {
				id: XSM.vendor_ui.menu_table,
				cols: [
					{ width: 200},
					{ width: 400},
					{ width: 100},
					{ width: 350},
					{ width: 50}
				]
			},
			opts: {
				id: XSM.vendor_ui.orbopts_table,
				cols: [
					{ width: 100 },
					{ width: 100 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 }
				]
			}
		};
		$(tables[table].id).dataTable({
				bDestroy: true,
				bJQueryUI: true,
				bDeferRender: false,
				autoWidth: false,
				columns: tables[table]
		});
	},

	save_orb: function (orb_id, attribute, replacement) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		var saved_value = null;
		switch (attribute) {
			case 'title':
				saved_value = $("input[name='Orb[title]']", cell_id).val();
				if (!saved_value) saved_value = "Click to Add";
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'orbcat':
				var orbcat_id = $("select", cell_id).val();
				$($("select", cell_id).find("option")).each(function () {
					if (Number($(this).attr('value')) == orbcat_id) {
						saved_value = $(this).text();
						$(this).attr('selected', 'selected');
					} else {
						$(this).removeAttr('selected');
					}
				});
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'description':
				saved_value = $("textarea", cell_id).val();
				if (!saved_value) saved_value = "Click to Add";
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'prices':
				$(cell_id).html(replacement);
				break;
		}
		this.fix_breakouts();
		XT.vendor_ui.cancel_cell_editing('orb', orb_id, attribute);
	},
	set_orbopt_pricelist_focus: function() {
		$("#orbopt-pricelist-add input[name='Pricelist[id]']", C.BODY).val($("#orbopt-pricelist-select").val());
		XT.vendor_ui.toggle_pricelist_add("stow");
		$( "#orbopt-pricelist-buttons .modal-button.disabled", C.BODY).removeClass(FX.disabled).addClass(FX.enabled);
	},
	edit_orbopt_pricelist: function() {
		var url = ["edit-orbopt-pricing", $("#orbopt-pricelist-select").val()].join(C.DS);
		$("#orbopt-pricelist-add-container").load([XT.host,url].join(C.DS), function() { XT.vendor_ui.toggle_pricelist_add("reveal", true); });
	},
	toggle_pricelist_add: function(state, preserve_fields) {
		if (!preserve_fields || state != "reveal") {
			$("input", "#orbopt-pricelist-add-container").each(function() { $(this).val("");});
		}
		if (state == "reveal") {
			$( ".modal-button.enabled", "#orbopt-pricelist-buttons").removeClass(FX.enabled).addClass(FX.disabled);
			$("#orbopt-pricelist-add-container").removeClass(FX.hidden);
			setTimeout(function() { $("#orbopt-pricelist-add-container").removeClass(FX.fade_out); }, 10);
		}
		if (state == "stow") {
			$("#orbopt-pricelist-add-container").addClass(FX.fade_out);
			setTimeout(function() { $("#orbopt-pricelist-add-container").addClass(FX.hidden); }, 300);
		}
	},
	delete_orbopt_pricelist: function(action) {
		var pricelist_id = $("#orbopt-pricelist-select").val();
		switch (action) {
			case "confirm":
				$(XT.router).trigger(C.ROUTE_REQUEST, {request:["orbopt_pricelist", "delete","delete", pricelist_id].join(C.DS), trigger:{}});
				break;
			case "warn":
				$("#delete-orbopt-pricelist-confirmation").removeClass(FX.hidden);
				setTimeout(function() { $("#delete-orbopt-pricelist-confirmation").removeClass(FX.fade_out); }, 10);
				break;
			case "cancel":
				$("#delete-orbopt-pricelist-confirmation").addClass(FX.fade_out);
				setTimeout(function() { $("#delete-orbopt-pricelist-confirmation").addClass(FX.hidden); }, 300);
				break;
			case "print_opts":
				XT.vendor_ui.delete_orbopt_pricelist("cancel"); // close the cancellation warning
				XT.vendor_ui.reload_orbopt_pricelist_config();
				break;
		};
	},
	reload_orbopt_pricelist_config: function() {
		var opt_id = $("#orbopt-pricelist-select-form", C.BODY).data('opt');
		$(XT.router).trigger(C.ROUTE_REQUEST, {request:["orbopt_pricelist","launch","false",opt_id].join(C.DS), trigger:{}});
	},
	edit_cell: function (table, id, attribute) {
		var display_element = XSM.vendor_ui.orb_attr_display;
		var edit_element = XSM.vendor_ui.orb_attr_edit;
		var cell_id = as_id([table, id, attribute].join("-"));
		if (table == "orbopt") {
			display_element = XSM.vendor_ui.orbopt_attr_display;
			edit_element = XSM.vendor_ui.orbopt_attr_edit;
		}
		pr([table, id, attribute, cell_id], "edit_cell");
		$(display_element, cell_id).addClass(FX.fade_out);
		setTimeout( function() {
			$(display_element, cell_id).addClass(FX.hidden);
			$(edit_element, cell_id).removeClass(FX.hidden);
			setTimeout(function () { $(edit_element, cell_id).removeClass(FX.fade_out); }, 30);
		}, 300);
	},
	cancel_cell_editing: function (table, id, attribute) {
		var display_element = XSM.vendor_ui.orb_attr_display;
		var edit_element = XSM.vendor_ui.orb_attr_edit;
		var cell_id = as_id([table, id, attribute].join("-"));
		if (table == "orbopt") {
			display_element = XSM.vendor_ui.orbopt_attr_display;
			edit_element = XSM.vendor_ui.orbopt_attr_edit;
		}
		pr([table, id, attribute, cell_id], "cancel_cell_editing");
		$(edit_element, cell_id).addClass(FX.fade_out);
		setTimeout(function() { $(display_element, cell_id).removeClass(FX.fade_out);}, 30)
		setTimeout(function() {
			$(edit_element, cell_id).addClass(FX.hidden);
			$(display_element, cell_id).removeClass(FX.hidden);
			new Modal(C.PRIMARY).hide();
		}, 330);
	},
	save_orbopt: function(response, data) {
		var cell_id;
		if (data.attribute == "pricing") {
			cell_id = XSM.vendor_ui.orbopt_pricelist_add;
			$(cell_id, C.BODY).addClass([FX.hidden, FX.fade_out].join(" "));
		} else {
			cell_id = as_id(["orbopt", data.id, data.attribute].join("-"));
		}
		switch (data.attribute) {
			case "title":
				$(XSM.vendor_ui.orbopt_attr_display, cell_id).html(response.submitted_data.data.Orbopt.title);
				break;
			case "vendor-title":
				$(XSM.vendor_ui.orbopt_attr_display, cell_id).html(response.submitted_data.data.Orbopt.vendor_title);
				break;
		}
		if ("replacement" in data) $(XSM.vendor_ui.orbopt_attr_display, cell_id).html(data.replacement);
		XT.vendor_ui.cancel_cell_editing("orbopt", data.id, data.attribute);
	},
	toggle_orbopt_group: function (orbcat_id) {
		orbcat_id = Number(orbcat_id);
		$("li.orbopt", XSM.modal.primary).each(function () {
			var data = $(this).data();
			if (in_array(orbcat_id, data.groups)) {
				$(XT.router).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt , "set_opt_state", "active"].join(C.DS), trigger: {}});
			} else {
				$(XT.router).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt, "set_opt_state", "inactive"].join(C.DS), trigger: {}});
			}
		});
	},


	set_orbopt_state: function (opt_id, state, label, input) {
		var opt_label_wrapper = label ? label : "#orbopt-" + opt_id + "-label";
		var opt_label = opt_label_wrapper + " span";
		var opt_input = input ? input : "li[data-orbopt='" + opt_id + "'] input";
		if (state == FX.active) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active_plus).removeClass(FX.inactive).addClass(FX.active);
			$(opt_input, XSM.modal.primary).val(1);
		}
		if (state == FX.inactive) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active).removeClass(FX.active_plus).addClass(FX.inactive);
			$(opt_input, XSM.modal.primary).val(0);
		}
		if (state == FX.active_plus) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active).removeClass(FX.inactive).addClass(FX.active_plus);
			$(opt_input, XSM.modal.primary).val(2);
		}
	},


	toggle_filter: function (filter_id) {
		var filter = "#orbopt-flag-" + filter_id;
		if ($(filter, XSM.modal.primary).hasClass(FX.active)) {
			$(filter).removeClass(FX.active);
		} else {
			$(filter).addClass(FX.active);
		}
		XT.vendor_ui.filter_opts()
	},

	toggle_optflag: function (response, data) {
		var orbopt_id = data.orbopt;
		var optflag_id = data.optflag;
		var cell_id = as_id(["orbopt", orbopt_id, "optflag", optflag_id].join("-"));
		if ($("span", cell_id).hasClass(FX.active)) {
			$("span", cell_id).removeClass(FX.active).addClass(FX.inactive);
		} else {
			$("span", cell_id).removeClass(FX.inactive).addClass(FX.active);
		}
	},

	toggle_orbopt: function (opt_id) {
		var opt_label = "#orbopt-" + opt_id + "-label";
		var opt_input = "li[data-orbopt='" + opt_id + "'] input";
		var state = null;
		if ( $(opt_label, XSM.modal.primary).hasClass(FX.active) ) {
			state = FX.active_plus;
		} else if ($(opt_label, XSM.modal.primary).hasClass(FX.active_plus) ) {
			state =  FX.inactive;
		} else {
			state = FX.active;
		}
		XT.vendor_ui.set_orbopt_state(opt_id, state, opt_label, opt_input)
	},


	filter_opts: function () {
		var active_flags = new Array();
		$("dd.orbopt-flag.active").each(function () { active_flags.push(Number($(this).data('id'))); });
		$("li.orbopt", XSM.modal.primary).each(function () {
			var flags = $(this).data('flags');
			var active = false;
			for (var i in flags) {
				if (active_flags.indexOf(flags[i]) > -1) active = true;
			}
			if (active) {
				$(this).removeClass(FX.hidden);
			} else {
				$(this).addClass(FX.hidden)
			}
			$(document).foundation();
		});
	},

	reload_tab: function( tab ) {
		if (tab == "opts") {
			$(XSM.vendor_ui.menu_options_tab).html('');
			$(XSM.vendor_ui.menu_options_tab).load(["vendor-ui", "opts"].join(C.DS), function() {
																	XT.vendor_ui.data_tables(tab);
																	XT.vendor_ui.fix_breakouts();
																});
		}
		$(XSM.vendor_ui.menu_tab).load(["vendor-ui", "menu"].join(C.DS), function() {
														XT.vendor_ui.data_tables(tab);
														XT.vendor_ui.fix_breakouts();
													});
	},

	toggle_menu_options_breakout: function( id ) {
		if ( $(id, XSM.vendor_ui.menu_options_tab).hasClass(FX.hidden) ) {
			$(id, XSM.vendor_ui.menu_options_tab).removeClass(FX.hidden);
		} else {
			$(id, XSM.vendor_ui.menu_options_tab).addClass(FX.hidden);
		}
	},

	overflagged: function(opt_id, optflag_id) {
		var true_count = 0;
		var flag_map = {0: null, 1: null, 2: null, 3: null, 4: null, 5: null, 6: null, 7: null};
		for (var  flag_id in flag_map) {
			var cell_id = as_id(["orbopt", opt_id, "optflag", flag_id].join("-"));
			var active = $("span", cell_id).hasClass(FX.active);
			if (active === true) true_count++;
			flag_map[flag_id] = active;
		}
		if (true_count == 0 || flag_map[optflag_id] == true /*ie. deselecting*/) return false;
		flag_map[optflag_id] = true; // test turning it on
		true_count++;
		// premium & meat or premium & veg
		if ( true_count == 2 ) {
			if (flag_map[6]) return !(flag_map[1] || flag_map[2])
		}
		return true;
	},

	overflagging_alert: function(action) {
		var method, time, class_1, class_2;
		if (action == C.SHOW) {
			method = "removeClass";
			time = 300;
			class_1 = FX.hidden;
			class_2 = FX.fade_out;
		} else {
			method = "addClass";
			time = 10;
			class_1 = FX.fade_out;
			class_2 = FX.hidden;
		}
		$("#overflagging-alert")[method](class_1);
		setTimeout( function() { $("#overflagging-alert")[method](class_2); }, time);
	},

	specials_orbcat_filter: function() {
		var orbcat_id = $( $("#special-orbcats-list-select").find(":selected")[0] ).val();
		$("option", "#special-orbs-list-select").each( function() {
			var action = $(this).data('orbcat') == orbcat_id ? "removeClass" : "addClass";
			$(this)[action](FX.hidden);
		});
	},

	specials_add_orb: function() {
		var orb_count = Number($("#specials-orbs").data('orbCount'));
		$("#specials-orbs").data('orbCount', orb_count + 1);
//		var orbcat_id = $( $("#special-orbcats-list-select").find(":selected")[0] ).val();
		var orbcat_title = $( $("#special-orbcats-list-select").find(":selected")[0] ).text();
		var orb_id = $( $("#special-orbs-list-select").find(":selected")[0] ).val();
		var orb_title = $( $("#special-orbs-list-select").find(":selected")[0] ).text();
		var quantity = $( $("#special-orbs-quantity-select").find(":selected")[0] ).val();
		$("#SpecialMenuStatus").val( $("#menu-active").hasClass(FX.active) );
		$("#SpecialAjaxAddForm").append([
			$("<input/>").attr({
						type: "hidden",
						name:"data[SpecialsOrb]["+orb_count+"][orb_id]",
						value:orb_id}),
			$("<input/>").attr({
				type: "hidden",
				name:"data[SpecialsOrb]["+orb_count+"][quantity]",
				value:quantity})]);

		$("tbody", "#specials-orbs").append(
			$("<tr/>").attr('id', 'orb-'+ orb_count +'-table-row').append([
				$("<td />").text(orb_title),
				$("<td />").text(orbcat_title),
				$("<td />").text(quantity)],
				$("<td />").append(
					$("<a />").attr({
						href: "#",
						"data-route": ['specials_add_delete_orb', orb_count].join(C.DS)
						}).
						addClass("tiny modal-button delete full-width text-center").append(
						$("<span />").addClass("icon-cancel textless")
					)
				)
			)
		);

	}
}
