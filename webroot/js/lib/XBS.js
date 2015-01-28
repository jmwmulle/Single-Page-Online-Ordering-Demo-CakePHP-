/**
 * J. Mulle, for XtremePizza, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

var printer_ip = "192.168.1.129";

window.XBS = {
	init: function (is_splash, page_name, host, cart) {
		XBS.cart = xbs_cart;
		XBS.data = xbs_data;
		XBS.event = xbs_events;
		XBS.layout = xbs_layout;
		XBS.menu = xbs_menu;
		XBS.printer = new XtremePrinter();
		XBS.routing = xbs_routing;
		XBS.splash = xbs_splash;
		XBS.validation = xbs_validation;
		XBS.vendor = xbs_vendor;
		XBS.setHost(host);
		XBS.data.store_status = store_status;
		XBS.data.cfg.page_name = page_name;
		XBS.data.cfg.is_splash = is_splash === true;
		var init_status = {
			cart: XBS.cart.init(cart),
			layout: XBS.layout.init(),
			splash: XBS.splash.init(),
			menu: XBS.menu.init(),
			printer: XBS.printer.is_xtreme_tablet() ? XBS.printer.init() : 'not_tablet',
			routing: XBS.routing.init(),
			vendor: XBS.vendor.init()
		};

		if (XBS.data.debug) pr(init_status, "init status");

	},
	exec_init_sequence: function (init_list) {
		var meta_sit_rep = {state: true, report: {}};
		for (var init_ob_name in init_list) {
			meta_sit_rep.report[init_ob_name] = {};
			var init_ob = init_list[init_ob_name];
			if (!exists(init_ob.has_init_sequence)) {
				throw new TypeError("Object passed to XBS.fn.exec_init_sequence() does not contain 'has_init_sequence' property.");
			}
			for (var fn in init_ob) {
				if (fn != "has_init_sequence") {
					meta_sit_rep.report[init_ob_name][fn] = init_ob[fn]();
					if (meta_sit_rep.report[init_ob_name][fn] !== true) meta_sit_rep.state = false;
				}
			}
		}
		return meta_sit_rep;
	},
	/**
	 * setHost method
	 * @desc  Sets the root directory for AJAX references given <host>
	 * @host <str> string key for host in xbs_data.host_root_dirs
	 * @throws
	 * @returns <boolean>
	 */
	setHost: function (host) {
		if (xbs_data.host_root_dirs[host] == C.UNDEF) {
			throw new ReferenceError("Error: host '{0}' not found in xbs_data.host_root_dirs.".format(host));
		} else {
			xbs_data.cfg.root = xbs_data.host_root_dirs[host];
		}
		return true;
	}
};