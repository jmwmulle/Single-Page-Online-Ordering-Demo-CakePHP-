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

	this.orbs = {};
	this.configured = {}
	this.initialized = false;

	this.init = function(cart_details) {
		pr(cart_details, "cart details");
		try {
			this.orbs = Object.keys(cart_details.OrderItem).length > 0 ? cart_details.OrderItem : {};
			this.initialized = true;
			return true;
		} catch(e) {
			return null
		}
	};

	this.tally = function() {
	};

	this.has_orb = function(orb_id) { return orb_id in this.orbs; }

	this.orb_attr = function(orb_id, attribute) {
		if (!this.has_orb) return false;
		if (!attribute) return false;
		attribute = camelcase_to_pep8(attribute.replace("OrderOrb", ""))
		if (attribute == "id") return orb_id;
		if (attribute in this.orbs[orb_id]) return this.orbs[orb_id][attribute]
		if (attribute.substr(0,6) == "orbopt") {
			var orbopt_id = attribute.split("_")[1];
			if (orbopt_id in this.orbs[orb_id]["orbopts"]) {
				return this.orbs[orb_id]["orbopts"][orbopt_id]['weight'];
			} else {
				return -1;
			}
		}
		return false;
	}

	this.init(cart_details);

	return this;
}
