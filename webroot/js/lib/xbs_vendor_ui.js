/**
 * Created by jono on 4/30/15.
 */
var xbs_vendor_ui = {
	init: function () {
		$(XSM.vendor_ui.ui_tabs).tabs();
		$(XSM.effects.breakout).each(function () {
			$(this).css({
				left: (($(window).width() - $(this).innerWidth()) / 2) + "px",
				top: (($(window).height() - $(this).innerHeight()) / 2) + "px"
			})
		});
		for ( var table in {menu:null, opts:null } ) {
			XBS.vendor_ui.data_tables(table);
		}

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
			bJQueryUI: true,
			bDeferRender: false,
			autoWidth: false,
			columns: tables[table].cols
		});

	},

	edit_orb: function (orb_id, attribute) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		$(XSM.vendor_ui.orb_attr_display, cell_id).addClass(XSM.effects.hidden);
		$(XSM.vendor_ui.orb_attr_edit, cell_id).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
		setTimeout(function () { $(XSM.vendor_ui.orb_attr_edit, cell_id).removeClass(XSM.effects.fade_out); }, 30);
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

		$(XSM.vendor_ui.orb_attr_display, cell_id).removeClass(XSM.effects.hidden);
		$(XSM.vendor_ui.orb_attr_edit, cell_id).addClass(XSM.effects.hidden);
	},

	cancel_editing: function (orb_id, attribute) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		$(XSM.vendor_ui.orb_attr_display, cell_id).removeClass(XSM.effects.hidden);
		$(XSM.vendor_ui.orb_attr_edit, cell_id).addClass(XSM.effects.hidden);
	},


	toggle_orbopt_group: function (orbcat_id) {
		orbcat_id = Number(orbcat_id);
		$("li.orbopt", XSM.modal.primary).each(function () {
			var data = $(this).data();
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


	toggle_filter: function (filter_id) {
		var filter = "#orbopt-flag-" + filter_id;
		if ($(filter, XSM.modal.primary).hasClass(XSM.effects.active)) {
			$(filter).removeClass(XSM.effects.active);
		} else {
			$(filter).addClass(XSM.effects.active);
		}
		XBS.vendor_ui.filter_opts()
	},

	toggle_optflag: function (response, data) {
		var orbopt_id = data.orbopt;
		var optflag_id = data.optflag;
		var cell_id = as_id(["orbopt", orbopt_id, "optflag", optflag_id].join("-"));
		if ($("span", cell_id).hasClass(XSM.effects.active)) {
			$("span", cell_id).removeClass(XSM.effects.active).addClass(XSM.effects.inactive);
		} else {
			$("span", cell_id).removeClass(XSM.effects.inactive).addClass(XSM.effects.active);
		}
	},

	toggle_orbopt: function (opt_id) {
		var opt_label = "#orbopt-" + opt_id + "-label";
		var opt_input = "li[data-orbopt='" + opt_id + "'] input";
		var state = $(opt_label, XSM.modal.primary).hasClass(XSM.effects.active) ? XSM.effects.inactive : XSM.effects.active;
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
				$(this).removeClass(XSM.effects.hidden);
			} else {
				$(this).addClass(XSM.effects.hidden)
			}
			$(document).foundation();
		});
	}

}