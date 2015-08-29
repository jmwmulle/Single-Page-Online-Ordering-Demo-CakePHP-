XtremeCart = function( cart_id ) {
	this.init(cart_id);
	return this;
}
XtremeCart.prototype = {
	constructor: this.XtremeCart,
	configured: [], // CONFIRMED BY SERVER
	configuring: [],
	cart_id: undefined,
	initialized: false,
	session_data: undefined,

	init: function (cart_id) {
		var debug_this = 0
		if (debug_this > 0 ) pr(cart_id, "XtremeCart.init(cart_id)", 2);
		this.cart_id = cart_id;
		this.import_cart(false);
	},
	import_cart: function(session_data) {
		var self = this;
		if ( !session_data ) {
			$.get(["cart", this.cart_id].join(C.DS), function(response) { self.import_cart($.parseJSON(response).data)});
			return;
		}

		if ( session_data && !this.cart_id) this.cart_id = session_data.cart_id;

		this.session_data = session_data;
		for (var uid in this.session_data.Order) {
			var orb =  new Orb(uid);
			orb.import_from_cart(session_data.Order[uid]);
			if ( this.find_by_uid(uid) ) this.delete(uid, C.CONFIGURING);
			this.configured.push( orb );
		}
		if (!this.initialized && this.session_data.id)  {
			var request = ["order_confirmation", this.session_data.id, "relaunching"].join(C.DS);
			setTimeout( function() {
				$(XT.router).trigger(C.ROUTE_REQUEST, {request: request, trigger:{} }) },
			XT.menu.stash());
			return

		}
		this.initialized == true;
		this.configuring = [];
		this.set_order_method();
	},

	/**
	 * Infers whether identifier string is a Orb model id or Orb UID
	 * @param identifier
	 * @returns {boolean}
	 */
	is_uid: function(identifier) { return String(identifier).split("_").length > 1},

	/**
	 * Generates a unique id from Orb model id and current time
	 * @param orb_id
	 * @returns {*|string}
	 */
	generate_uid: function(orb_id) { return [orb_id, now()].join("_");},


	/**
	 * Searches cart.configuring for open configurations by either uid or id
	 * @param identifier
	 * @returns {*}
	 */
	find_configuration: function (identifier) {
		var orb_config =  this.is_uid(identifier) ? this.find_by_uid(identifier) : this.find_by_id(identifier, true);
		return orb_config ? orb_config[0] : false;
	},

	add_to_cart: function (orb_id) {
		var uid = this.generate_uid(orb_id);
		if (!this.has_orb(uid, true)) {
			this.configuring.push( new Orb(uid) );
		}
		return true;
	},


	/**
	 * Returns orb with matching uid if found, else false.
	 * @param uid
	 * @returns {*}
	 */
	find_by_uid: function(uid, source) {
		var search_in = source == C.CONFIGURED ? this.configured : this.configuring;
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
		var search_in = source == C.CONFIGURED ? this.configured : this.configuring;
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
	 * @returns Orb
	 */
	configure: function (id, price_rank) {
		var debug_this = 0;
		if (debug_this > 0) pr(id, "XT.cart.configure()", 2);
		var orb = this.find_configuration(id);
		if (!orb)  {
			orb = new Orb( id, this.generate_uid(id));
			this.configuring.push(orb);
		}

		orb.configure(price_rank);

		return orb; // for chaining
	},

	set_order_method: function (method) {
		if (method)  {
			this.session_data.Service.order_method = method;
		} else {
			method = this.session_data.Service.order_method;
		}
		if (!method) {
			this.session_data.Service.order_method = C.JUST_BROWSING;
			method = C.JUST_BROWSING;
		}
		$(XSM.menu.user_activity_panel_items).each(function () {
			var route = $($(this).children()[0]).data('route');
			if (route) {
				if (route.split(C.DS)[2] == method) {
					$(this).removeClass(FX.inactive).addClass(FX.active);
				} else {
					$(this).removeClass(FX.active).addClass(FX.inactive);
				}
			}
		});

	},

	update: function(uid, target) {
		pr([uid, target, this.session_data.Order[uid], as_id(["order-item", uid].join("-")), as_id([uid, "price"].join("-"))]);
		if ( $(target).hasClass('edit-orb') ) {
			var orb = this.session_data.Order[uid];
			$( as_id([uid, "price"].join("-")) ).html(orb.pricing.net_formatted)
			return $(target).remove()
		}
		$( as_id(["order-item", uid].join("-")) ).remove();

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

	validate_order_review: function() {
		var valid = true;
		this.session_data.Service.pay_method = $(XSM.modal.payment_method_input).val();
		if (this.session_data.Service.order_method == C.JUST_BROWSING) valid = false;
		if (this.session_data.Service.order_method == C.DELIVERY ) {
			valid = this.session_data.Service.address === true && this.session_data.Service.pay_method != undefined;
		}
		if (valid) $(XSM.modal.finalize_order_button).removeClass(XSM.effects.disabled);
	}

};

