var xbs_cart = {
	pricable_optflags: {},
	orbs: {}, // CONFIRMED BY SERVER
	configuring: {orbopts: {}}, // ORB_ID: {CONFIG}
	initialized: false,
	empty_config: function(identifier) {
		var uid = null;
		var id = null;
		if (!!identifier) {
			if (XBS.cart.is_uid(identifier)) {
				uid = identifier;
				id = XBS.cart.id_from_uid(identifier);
			} else {
				id = identifier;
				uid = XBS.cart.orb_uid(identifier);
			}
		}
		return {
			id: id,
			uid: uid,
			quantity:null,
			orbopts:{},
			preparation_instructions:null,
			price_rank: null,
			sauce_weight: null,
			default_opts: {premium: null, regular: null},
			opt_weight: {premium: null, regular: null}
		}
	},
	attributes_object: function() {
		return  {
			str: null,
			is_id: false,
			is_quanity: false,
			is_prep_instrux: false,
			is_orbopt: false,
			opt_id: null
		}
	},

	init: function (cart_from_session) {
		var debug_this = 0;
		if (debug_this > 0 ) pr(cart_from_session, "XBS.cart.init(cart details)", 2);
		if ('Order' in cart_from_session) XBS.data.order = cart_from_session.Order;
		XBS.cart.orbs = is_object(cart_from_session) && "OrderItem" in cart_from_session ? cart_from_session.OrderItem : {};

		$.get(["", xbs_data.cfg.root, "opt-price-factors"].join(C.DS), function(response) {
				XBS.cart.pricable_optflags = integer_keys($.parseJSON(response));
				XBS.cart.pricable_optflags[0] = "regular";
			}
		);

		XBS.cart.initialized = true;
		XBS.cart.configuring = {};
		return true;
	},
	is_uid: function(identifier) { return identifier.split("_").length > 1},
	orb_uid: function(orb_id) { return [orb_id, now()].join("_");},
	id_from_uid: function(uid) { return uid.split("_")[0] },
	tally: function () {},
	cancel_config: function (orb_id) { XBS.cart.configuring[XBS.cart.orb_uid(orb_id)] = XBS.cart.empty_config; },
	has_configuration_started: function (identifier) { return XBS.cart.has_orb(identifier, true);},
	has_orb: function (identifier, in_configuring, as_int) {
		var found = 0;
		var context = in_configuring ? XBS.cart.configuring : XBS.cart.orbs;
		if ( XBS.cart.is_uid(identifier) ) {
			found = identifier in context
		} else {
			as_int === false; // ie. if not otherwise specified, as_int should be true for searching by ids
			for (var uid in context) { if (context[uid].id == identifier) found += 1 }
		}
		if (as_int === false) return found > 1 || found === true;
		if ( found === true  ) return 1;
		return found === false ?  0 : found;
	},
	from_attribute_id_str: function (attribute_str) {
		var attr_ob = jQuery.extend({}, XBS.cart.attributes_object);
		attribute_str = camelcase_to_pep8(attribute_str.replace("OrderOrb", ""));
		if (attribute_str == "id") {
			attr_ob.is_id = true;
			attr_ob.str = "id";
		}
		if (attribute_str == "quantity") {
			attr_ob.is_quantity = true;
			attr_ob.str = "quantity";
		}
		if (attribute_str == "preparation_instructions") {
			attr_ob.is_prep_instrux = true;
			attr_ob.str = "preparation_instructions";
		}
		if (attribute_str.substr(0, 6) == "orbopt") {
			attr_ob.is_orbopt = true;
			attr_ob.opt_id = attribute_str.split("_")[1];
			attr_ob.str = "orbopt";
		}
		return attr_ob;
	},
	add_to_cart: function (orb_id) {
		var uid = XBS.cart.orb_uid(orb_id);
		if (!XBS.cart.has_orb(uid, true)) {
			XBS.cart.configuring[uid] = XBS.cart.empty_config();
		}
		return true;
	},
	orb_attr: function (orb_uid, attribute, in_configuration) {
		if (!XBS.cart.has_orb(orb_uid, in_configuration)) return false;
		if (!attribute) return false;
		attribute = XBS.cart.from_attribute_id_str(attribute)
		if (attribute.is_id) return XBS.cart.id_from_uid(orb_uid);
		var context = in_configuration ? XBS.cart.configuring : XBS.cart.orbs;
		if (attribute.str in context[orb_uid]) return context[orb_uid][attribute.str]

		if (attribute.is_orbopt) {
			if (attribute.opt_id in context[orb_uid]["orbopts"]) {
				try {
					if ('weight' in  context[orb_uid].orbopts[attribute.opt_id]) {
						return context[orb_uid].orbopts[attribute.opt_id]['weight']; // orbs
					}
				} catch (e) {
					return context[orb_uid].orbopts[attribute.opt_id]; // configuring
				}
			} else {
				return -1;
			}
		}
		return false;
	},
	configure: function () {
		var debug_this = 0;
		if (debug_this > 0) pr("<no args>", "XBS.cart.configure()", 2);
		var orb_id = $(XSM.menu.orb_order_form_orb_id).val();
		var orb_uid = $(XSM.menu.orb_order_form_orb_uid).val();
		if (!orb_uid) orb_uid = [orb_id, now()].join("_");
		if (!(orb_uid in XBS.cart.configuring)) XBS.cart.configuring[orb_uid] = XBS.cart.empty_config();
		XBS.cart.configuring[orb_uid].quantity = $(XSM.menu.orb_order_form_quantity).val();
		XBS.cart.configuring[orb_uid].price_rank = $(XSM.menu.orb_order_form_price_rank).val();
		XBS.cart.configuring[orb_uid].preparation_instructions = $(XSM.menu.orb_order_form_prep_instrux).val();
		$(XSM.menu.orb_order_form_orb_opts).each(function () {
			var id = XBS.cart.from_attribute_id_str($(this).attr('id')).opt_id
			XBS.cart.configuring[orb_uid].orbopts[id] = {
				id: id,
				weight: $(this).val(),
				optflags: $(as_id("orb-opt-" + id)).data('optflags')
			}
		});
		XBS.cart.inspect_configuration(orb_uid);
		return true;
	},
	weight_to_int: function(weight) {
		switch (weight) {
			case "-1":
				return -1;
			case "F":
				return 1;
			case "D":
				return 2;
			default:
				return 0.5;
		}
	},
	inspect_configuration: function(uid) {
		var orb = XBS.cart.configuring[uid];
		pr(orb, "orb");
		var opt_weights = copy(XBS.cart.pricable_optflags);
		XBS.cart.pricable_optflags[3] = "fart";
		for (var opt_id in orb.orbopts) {
			var opt = orb.orbopts[opt_id];
			for (var id in opt.optflags.length) {
				if ( opt.optflags[id] in opt_weights ) {
					var weight_val = XBS.cart.weight_to_int(opt.weight);
					xbs_cart.pricable_optflags[opt.optflags[id]] += weight_val > -1 ? weight_val : 0;
				}
			}
		}
		XBS.menu.enforce_opt_rules(orb, opt_weights);
	},
	validate_order_review: function() {
		var valid = true;
		XBS.data.order.payment = $(XSM.modal.payment_method_input).val();
		if (XBS.data.order.order_method == C.JUST_BROWSING) valid = false;
		if (XBS.data.order.order_method == C.DELIVERY ) {
			if ( !XBS.data.order.address) valid = false;
			if ( !XBS.data.order.payment) valid = false;
		}
		if (valid) {
			$(XSM.modal.finalize_order_button).removeClass(XSM.effects.disabled);
		}

	}
};