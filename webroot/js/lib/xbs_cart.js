/**
 * Orb Class
 *
 * @param uid
 * @returns {Orb}
 * @constructor
 */
Orb = function(uid) {
	this.uid = uid;
	this.id = null;
	this.quantity = null;
	this.orbopts = [];
	this.note = null;
	this.price_rank = null;
	this.init(uid);

	return this;
}
Orb.prototype = {
	constructor: Orb,
	id: undefined,
	uid: undefined,
	quantity: 1,
	orbopts: [],
	note: undefined,
	price_rank: 0,

	init: function(uid) { this.uid = uid; },
	import_data: function(orb_data) {
		try {
			this.id = orb_data.orb.Orb.id;
			this.quantity = orb_data.pricing.quantity;
			this.note = orb_data.orb.Orb.note;
			this.price_rank = orb_data.pricing.rank;
			var opts = obj_values(orb_data.orbopts)
			for (var i = 0; i < opts.length; i++) {
				var opt = new Orbopt();
				opt.import_data(opts[i]);
				this.orbopts.push( opt );
			}
		} catch (e) {
			throw "OrbImportError: " + e.message;
		}
		return true;
	},
	generate_orbcard_opt_reference: function() {
	var reference = $("<ul/>").addClass(sel_to_str(XSM.menu.tiny_orb_opts_list));
		for (var i = 0; i < this.orbopts.length; i++) {
			if ( this.orbopts[i].selected() ) reference.append( this.orbopts[i].generate_orbcard_opt_item() );
		}
	}
}


/**
 * Orbopt Class
 *
 * @param id
 * @param weight
 * @param flags
 * @returns {Orbopt}
 * @constructor
 */
Orbopt = function() {}
Orbopt.prototype = {
	constructor:Orbopt,
	id: undefined,
	html_id: undefined,
	pricelist: {id:null, ranks:[]},
	title: "",
	coverage: {
		OFF: [true, -1],
		F: [false, ""],
		D: [false, "<span class='icon-double tiny-opt icon-hnj-inline'></span>"],
		R: [false, "<span class='icon-right-side tiny-opt icon-hnj-inline'></span>"],
		L: [false, "<span class='icon-left-side tiny-opt icon-hnj-inline'></span>"]
	},
	flags: [],
	import_data: function(opt_data) {
		try {
			this.id = opt_data.Orbopt.id;
			this.html_id = ["#orb-opt", this.id].join("-");
			for ( var weight in this.coverage) this.coverage[weight][0] = weight == opt_data.Orbopt.coverage;
			this.title = opt_data.Orbopt.title;
			this.pricelist = {id: opt_data.Pricelist.id, ranks: obj_values(opt_data.Pricelist, 2)};
			for (var i= 0; i < opt_data.Optflag.length; i++) {
				var flag = new Optflag();
				flag.import_data(opt_data.Optflag[i]);
				this.flags.push(flag);
			}
		} catch (e) { throw "Orbopt Import Error: " + e.message; }
		return true;
	},
	selected: function() { return this.coverage.OFF[0] === true},
	coverage_value: function(return_element) {
		for (var weight in this.coverage) {
			if ( this.coverage[weight][0] === true ) return return_element ? this.coverage[weight][1] : weight;
		}
	},
	icon: function() {
		return $(this.html_id).find(XSM.menu.orb_opt_icon + '[data-weight="' + weight + '"]')[0];
	},
	generate_orbcard_opt_item: function() {
		return $("<li/>").addClass(stripCSS(XSM.menu.tiny_orb_opts_list_item))
					.append(["<span class='tiny-opt-label'>", this.title ,"</span>"].join(""))
					.append( this.coverage_value(true) );
	}
}


/**
 * Optflag Class
 *
 * @returns {Optflag}
 * @constructor
 */
Optflag = function() {}
Optflag.prototype = {
	id: undefined,
	title: undefined,
	priceable: undefined,
	import_data: function(optflag_data) {
		try {
			this.id = optflag_data.id;
			this.title = optflag_data.title,
			this.priceable = optflag_data.price_factor
		} catch (e) { throw "Optflag Import Error: " + e.message; }
	}
}


/**
 * OptflagMap Class
 *
 * @returns {OptflagMap}
 * @constructor
 */
OptflagMap = function() {
	return this;
}
OptflagMap.prototype = {
	constructor:OptflagMap,
	regular: 0,
	premium: 0,
	cheese: 0,
	sauce: 0
}

/**
 * CakeModelFormInflector Class
 *
 * @param cake_id_str
 * @param properties_list
 * @param truncate_str
 * @returns {CakeModelFormInflector}
 * @constructor
 */
OrbInflector = function(cake_id_str) {
	this.init(cake_id_str);
	return this;
}
OrbInflector.prototype = {
	constructor: OrbInflector,
	property: null,
	cake_id_str: null,
	properties_list: [ ['id', null],
				                       ['quantity', null],
				                       ['note', null],
				                       ['orbopt', function(property) { return property.substr(0, 6)}] ],
	truncate_str: "OrderOrb",
	init: function(cake_id_str) {
		this.cake_id_str = cake_id_str;
		this.property = camelcase_to_pep8( this.truncate_str ?  cake_id_str.replace(this.truncate_str, "") : cake_id_str );
		for (var i = 0; i < this.properties_list.length; i++) {
			var prop_str = is_function(this.properties_list[i][1]) ? this.properties_list[i][1](this.property) : this.property;
			this[ "is_" +  this.properties_list[i][0]] = this.properties_list[i][0] == prop_str;
		}
	},
	opt_id: function() { return this.property.substr(0, 6); }
}


var xbs_cart = {
	configured: [], // CONFIRMED BY SERVER
	configuring: [],
	initialized: false,

	init: function (session_cart) {
		var debug_this = 1;
		if (debug_this > 0 ) pr(session_cart, "XBS.cart.init(cart details)", 2);
		for (var key in session_cart) {
			XBS.data[key] = session_cart[key];
		}
		for (var uid in XBS.data.Order) {
			var orb =  new Orb(uid);
			orb.import_data(XBS.data.Order[uid]);
			if ( XBS.cart.find_by_uid(uid) ) XBS.cart.delete(uid, C.CONFIGURING);
			XBS.cart.configured.push( orb );
		}

		XBS.cart.initialized = true;
		XBS.cart.configuring = [];
		return true;
	},

	/**
	 * Infers whether identifier string is a Orb model id or Orb UID
	 * @param identifier
	 * @returns {boolean}
	 */
	is_uid: function(identifier) { return identifier.split("_").length > 1},

	/**
	 * Generates a unique id from Orb model id and current time
	 * @param orb_id
	 * @returns {*|string}
	 */
	generate_uid: function(orb_id) { return [orb_id, now()].join("_");},

	/**
	 * Gets Orb model id from UID str
	 * @param uid
	 * @returns {*}
	 */
	id_from_uid: function(uid) { return uid.split("_")[0] },

	/**
	 * Uhhhh... I don't think this current;y works
	 * @param orb_id
	 */
	cancel_config: function (orb_id) {
		var uid = XBS.cart.generate_uid(orb_id);
		XBS.cart.configuring[uid] = new Orb(uid);
	},

	/**
	 * Removes an Orb object from cart.configur(ed/ing) and reindexes array
	 *
	 * @param uid
	 * @param source
	 * @returns {boolean}
	 */
	delete: function(uid, source) {
		var search_in = source == C.CONFIGURED ? XBS.cart.configured : XBS.cart.configuring;
		for (var i = 0; i < search_in.length; i++) {
			if (search_in[i].uid == uid) {
				array_remove(search_in, i);
				return true;
			}
		}
		return false;
	},

	/**
	 * Searches cart.configuring for open configurations by either uid or id
	 * @param identifier
	 * @returns {*}
	 */
	find_configuration: function (identifier) {
		var orb_config =  XBS.cart.is_uid(identifier) ? XBS.cart.find_by_uid(identifier) : XBS.cart.find_by_id(identifier, true);
		return orb_config ? orb_config[0] : false;
	},

	// DEPRECATED I THINK....
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

	add_to_cart: function (orb_id) {
		var uid = XBS.cart.generate_uid(orb_id);
		if (!XBS.cart.has_orb(uid, true)) {
			XBS.cart.configuring.push( new Orb(uid) );
		}
		return true;
	},
	orb_attr: function (orb_uid, attribute, in_configuration) {
		if (!XBS.cart.has_orb(orb_uid, in_configuration)) return false;
		if (!attribute) return false;
		attribute = XBS.cart.html_id_from_attr(attribute)
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

	/**
	 * Returns orb with matching uid if found, else false.
	 * @param uid
	 * @returns {*}
	 */
	find_by_uid: function(uid, source) {
		var search_in = source == C.CONFIGURED ? XBS.cart.configured : XBS.cart.configuring;
		for (var i = 0; i < search_in.length; i++) {
			if (search_in[i].uid == uid) return [search_in[i]];
		}
		return false;
	},

	/**
	 * Returns array of Orbs in cart.configuring or first such Orb, else false.
	 * @param id
	 * @param first
	 * @returns {*}
	 */
	find_by_id: function(id, first, source) {
		var search_in = source == C.CONFIGURED ? XBS.cart.configured : XBS.cart.configuring;
		var found = [];
		for (var i = 0; i < search_in.length; i++) {
			if (search_in[i].id == id) {
				if (first) return [search_in[i]];
				found.push( search_in[i] );
			}
		}
		return found.length > 0 ? found : false;
	},
	/**
	 * Prepares Orbcard form for new Orb configuration or loads configuration in progress
	 * @returns {boolean}
	 */
	configure: function (id, price_rank) {
		var debug_this = 1;
		if (debug_this > 0) pr(id, "XBS.cart.configure()", 2);
		var orb = XBS.cart.find_configuration(id);
		if (!orb)  {
			orb = new Orb( XBS.cart.generate_uid(id) );
			XBS.cart.configuring.push(orb);
		}
		if (price_rank) orb.price_rank = price_rank;
		$(XSM.menu.orb_order_form_quantity).val(orb.quantity);
		$(XSM.menu.orb_order_form_price_rank).val( orb.price_rank );
		$(XSM.menu.orb_order_form_prep_instrux).val(orb.note);
		$(XSM.menu.orb_order_form_orb_opts).each(function () {
			orb.orbopts.push = new Orbopt(new OrbInflector( $(this).attr('id')).opt_id(), $(this).val() );
		});
		XBS.cart.current_orb = orb;
//      will deal with pricing and stuff, not updated to new cart methods/structure
//		XBS.cart.inspect_configuration(uid);
		return orb; // for chaining
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
		var orb = XBS.cart.find_by_uid(uid);
		var flags = new OptflagMap();

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