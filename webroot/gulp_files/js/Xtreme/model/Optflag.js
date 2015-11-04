/**
 * Created by jono on 8/11/15.
 */

/**
 * Optflag Class
 *
 * @returns {Optflag}
 * @constructor
 */
Optflag = function(orbopt) {
	this.init(orbopt)
	return this
}
Optflag.prototype = {
	constructor: Optflag,
	id: undefined,
	orbopt: undefined,
	title: undefined,
	priceable: undefined,
	DOM: {
		button:	undefined
	},
	init: function(orbopt) {
		this.orbopt = orbopt;
	},
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
OptflagMap = function() {}
OptflagMap.prototype = {
	constructor:OptflagMap,
	regular: 0,
	premium: 0,
	cheese: 0,
	sauce: 0
}