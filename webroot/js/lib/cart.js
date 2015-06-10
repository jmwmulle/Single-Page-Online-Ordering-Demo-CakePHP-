/**
 * Created by jono on 12/18/14.
 */

/***
 * Cart Class
 *
 * @param cart_details
 * @returns {Cart}
 * @constructor
 */
Cart = function(cart_details) {

	XBS.cart.orbs = {}; // CONFIRMED BY SERVER
	XBS.cart.configuring = {init: true} // ORB_ID: {CONFIG}
	XBS.cart.initialized = false;
	XBS.cart.__empty_config = function() {
		return {
			id: null,
			uid: null,
			quantity:null,
			orbopts:{},
			preparation_instructions:null,
			price_rank: null
			}
	}
	XBS.cart.attributes_object = {
						str: null,
						is_id: false,
						is_quanity: false,
						is_prep_instrux: false,
						is_orbopt:false,
						opt_id: null
					}

	XBS.cart.init = function(cart_details) {
		pr(cart_details, "cart details");
		try {
			XBS.cart.orbs = Object.keys(cart_details.OrderItem).length > 0 ? cart_details.OrderItem : {};
			XBS.cart.initialized = true;
			XBS.cart.configuring = {};
			return true;
		} catch(e) {
			return null
		}
	};

	XBS.cart.tally = function() {
	};

	XBS.cart.has_orb = function(orb_id, in_configuring) {
		return in_configuring ? orb_id in XBS.cart.configuring : orb_id in XBS.cart.orbs;
	}

	XBS.cart.from_attribute_id_str = function(attribute_str) {
		var attr_ob = $(XBS.cart.attributes_object).clone();
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
		if (attribute_str.substr(0,6) == "orbopt") {
			attr_ob.is_orbopt = true;
			attr_ob.opt_id = attribute_str.split("_")[1];
			attr_ob.str = "orbopt";
		}
		return attr_ob;
	}

	XBS.cart.orb_attr = function(orb_id, attribute) {
		if (!XBS.cart.has_orb) return false;
		if (!attribute) return false;
		attribute = XBS.cart.from_attribute_id_str(attribute)
		if (attribute.is_id) return orb_id;
		if (attribute.str in XBS.cart.orbs[orb_id]) return XBS.cart.orbs[orb_id][attribute.str]
		if (attribute.is_orbopt) {
			if (attribute.opt_id in XBS.cart.orbs[orb_id]["orbopts"]) {
				return XBS.cart.orbs[orb_id]["orbopts"][attribute.opt_id]['weight'];
			} else {
				return -1;
			}
		}
		return false;
	}

	XBS.cart.configure = function() {
		var orb_id = $(XSM.menu.orb_order_form_orb_id).val();
		if ( XBS.cart.configuring === null ) XBS.cart.configuring[orb_id] = XBS.cart.empty_config();
		if ( !(String(orb_id) in XBS.cart.configuring) ) XBS.cart.configuring[orb_id] = $(XBS.cart.empty_config).clone();
		XBS.cart.configuring[orb_id].quantity = $(XSM.menu.orb_order_form_quantity).val();
		XBS.cart.configuring[orb_id].price_rank = $(XSM.menu.orb_order_form_price_rank).val();
		XBS.cart.configuring[orb_id].preparation_instructions = $(XSM.menu.orb_order_form_prep_instrux).val();
		$(XSM.menu.orb_order_form_orb_opts).each( function() {
			var opt_id = XBS.cart.from_attribute_id_str($(this).attr('id')).opt_id;
			XBS.cart.configuring[orb_id].orb_opts[opt_id] = $(this).val();
		});
		return true;
	}


}

