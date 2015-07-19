/**
 * Created by jono on 4/30/15.





 * TODO: limit menu_options tab so that "premium", "cheese" & "sauce" are mutually exclusive




 *
 */
var xbs_vendor_ui = {
	init: function () {
		XBS.vendor_ui.loading_screen(0);
		XBS.vendor_ui.fix_breakouts();
		$(XSM.vendor_ui.ui_tabs).tabs();
		for ( var table in {menu:null, opts:null } ) {
			XBS.vendor_ui.data_tables(table);
		}
	},
	loading_screen: function(last_height) {
		var ui_height = $(XSM.vendor_ui.ui_tabs).innerHeight();
		if (last_height == ui_height) {
			$("#loading-screen").addClass(FX.fade_out);
			setTimeout(function(){ $("#loading-screen").addClass(FX.hidden); }, 300);
		} else {
			setTimeout( function() {
				pr("height: " + ui_height);
				XBS.vendor_ui.loading_screen(ui_height)}, 300);
		}
	},
	fix_breakouts: function() {
		$(FX.breakout).each(function () {
			$(this).css({
				left: (($(window).width() - $(this).innerWidth()) / 2) + "px",
				top: (($(window).height() - $(this).innerHeight()) / 2) + "px"
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
		$(tables[table].id, C.BODY).dataTable({
				bJQueryUI: true,
				bDeferRender: false,
				autoWidth: false,
				columns: tables[table]
		});

	},

	edit_orb: function (orb_id, attribute) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		$(XSM.vendor_ui.orb_attr_display, cell_id).addClass(FX.hidden);
		$(XSM.vendor_ui.orb_attr_edit, cell_id).addClass(FX.fade_out).removeClass(FX.hidden);
		setTimeout(function () { $(XSM.vendor_ui.orb_attr_edit, cell_id).removeClass(FX.fade_out); }, 30);
	},
	save_orb: function (orb_id, attribute, replacement) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		var saved_value = null;
		switch (attribute) {
			case 'title':
				saved_value = $("input[name='Orb[title]']", cell_id).val();
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
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'prices':
				$(cell_id).html(replacement);
				break;
		}
		XBS.vendor_ui.cancel_editing(orb_id, attribute)
	},
	cancel_editing: function (orb_id, attribute) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		$(XSM.vendor_ui.orb_attr_display, cell_id).removeClass(FX.hidden);
		$(XSM.vendor_ui.orb_attr_edit, cell_id).addClass(FX.hidden);
	},
	edit_orbopt: function (orbopt_id, attribute) {
		var cell_id = as_id(["orbopt", orbopt_id, attribute].join("-"));
		if (attribute == "pricing") {
			var select_id = as_id(["orbopt", orbopt_id, "pricelist"].join("-"));
			var route = ["orbopt_edit", orbopt_id, 'save', 'pricing'].join(C.DS);
			return $(XBS.routing).trigger(C.ROUTE_REQUEST, {request:route, trigger:{}});
		} else {
			$(XSM.vendor_ui.orbopt_attr_display, cell_id).addClass(FX.hidden);
			$(XSM.vendor_ui.orbopt_attr_edit, cell_id).removeClass(FX.hidden);
			setTimeout(function () { $(XSM.vendor_ui.orbopt_attr_edit, cell_id).removeClass(FX.fade_out); }, 30);
		}
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
		XBS.vendor_ui.cancel_orbopt_edit(data.id, data.attribute);
	},
	cancel_orbopt_edit: function (orbopt_id, attribute) {
		var cell_id = as_id(["orbopt", orbopt_id, attribute].join("-"));
		if (attribute == "pricing") {
			$("input.pricelist", XSM.vendor_ui.orbopt_pricelist_add).each(function() { $(this).val('');});
			$(XSM.vendor_ui.orbopt_pricelist_add, C.BODY).addClass([FX.hidden, FX.fade_out].join(" "));
		} else {
			$(XSM.vendor_ui.orbopt_attr_display, cell_id).removeClass(FX.hidden);
			$(XSM.vendor_ui.orbopt_attr_edit, cell_id).addClass(FX.hidden);
		}
	},
	toggle_orbopt_group: function (orbcat_id) {
		orbcat_id = Number(orbcat_id);
		$("li.orbopt", XSM.modal.primary).each(function () {
			var data = $(this).data();
			pr([orbcat_id, data]);
			if (in_array(orbcat_id, data.groups)) {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt , "set_opt_state", "active"].join(C.DS), trigger: {}});
			} else {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt, "set_opt_state", "inactive"].join(C.DS), trigger: {}});
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
		XBS.vendor_ui.filter_opts()
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
		XBS.vendor_ui.set_orbopt_state(opt_id, state, opt_label, opt_input)
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
			$(XSM.vendor_ui.menu_options_tab).load(["vendor-ui", "opts"].join(C.DS), function() {
																	XBS.vendor_ui.data_tables(tab);
																	XBS.vendor_ui.fix_breakouts();
																});
		}
		$(XSM.vendor_ui.menu_tab).load(["vendor-ui", "menu"].join(C.DS), function() {
														XBS.vendor_ui.data_tables(tab);
														XBS.vendor_ui.fix_breakouts();
													});
	}

}