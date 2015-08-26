/**
 * Orb Class
 *
 * @param uid
 * @returns {Orb}
 * @constructor
 */
Orb = function(id, uid) {
	this.DOM =  {
		uid: undefined,
		button: undefined,
		quantity: undefined,
		note: undefined,
		price_rank: {
			input: undefined,
			buttons: {}
		}
	};
	this.id = {
		init: function(self) {
			self.id.set = function(uid) {
				if (uid) self.id.value = uid;
				$(self.DOM.id).val(self.id.value);
			}
		},
		set: undefined,
		value: undefined
	};
	this.uid = {
		init: function(self) {
			self.uid.set = function(uid) {
				if (uid) self.uid.value = uid;
				$(self.DOM.uid).val(self.uid.value);
			}
		},
		set: undefined,
		value: undefined
	};
	this.quantity =  {
			init: function(self) {
				self.quantity.set = function(value) {
					if (value) self.quantity.value = value;
					$(self.DOM.quantity_input).val(self.quantity.value);
					return self
				}
				self.quantity.reset = function() { self.quantity.set(1); return self }
			},
			value: 1,
			set: undefined,
			reset: undefined
		};
	this.orbopts = {
		init: function(self) {
			self.orbopts.render = function() {
				$(self.orbopts.Opts).each( function() { this.render() } )
				return self;
			}
			self.orbopts.select = function( opt_id ) {
					self.orbopts.find(opt_id).select()
					$(XT.orbcard).trigger(C.ORB_CFG);
					return self
			},
			self.orbopts.deselect = function( opt_id ) {
					self.orbopts.find(opt_id).deselect();
					$(XT.orbcard).trigger(C.ORB_CFG);
					return self
			},
			self.orbopts.select_coverage = function( opt_id, weight) {
				self.orbopts.find(opt_id).select_coverage(weight);
				$(XT.orbcard).trigger(C.ORB_CFG);
				return this
			},
			self.orbopts.find = function(opt_id) {
				for (var i = 0; i < self.orbopts.Opts.length; i++) {
					if (self.orbopts.Opts[i].id == opt_id) return self.orbopts.Opts[i];
				}
				return false
			},
			self.orbopts.selected = function(filter_flag) {
				var selected = [];
				for (var i = 0; i < self.orbopts.Opts.length; i++) {
					if (self.orbopts.Opts[i].selected() ) {
						var opt = self.orbopts.Opts[i];
						if (!filter_flag ) {
							selected.push(opt);
						} else if ( opt.is(filter_flag) ) {
							selected.push(opt);
						}
					}
				}
				return selected;
			},
			self.orbopts.counts.reset = function() {
				self.orbopts.counts.sauces = 0;
				self.orbopts.counts.cheeses.selected = 0;
				self.orbopts.counts.premium.selected = 0;
				self.orbopts.counts.regular.selected = 0;
			}
		},
		counts: {
			reset: undefined,
			sauces: 0,
			regular: {included: 0, selected:0},
			premium: {included: 0, selected:0},
			cheeses: {included: 0, selected:0}
		},
		Opts: [],
		render: undefined,
		select: undefined,
		selected: undefined,
		deselect: undefined,
		select_coverage: undefined,
		find: undefined
	},
	this.note = {
		init: function(self) {
			self.note.set = function(value) {
				if (value) self.note.value = value;
				$(self.DOM.note).val(self.note.value);
				return self
			}
			self.note.reset = function() { self.note.set(""); return self }
		},
		set: undefined,
		reset: undefined,
		value: undefined
	},
	this.price_rank = {
		init: function(self) {
			self.price_rank.set = function(value) {
				if (value != undefined) {
					self.price_rank.value = value;
				} else {
					value = self.price_rank.value;
				}
				$(self.DOM.price_rank.input).val(self.price_rank.value);
				XT.layout.activize(self.DOM.price_rank.buttons[self.price_rank.value]);
				return self
			}
			self.price_rank.reset = function() {
				$(self.DOM.price_rank.input).val(0);
				XT.layout.activize(self.DOM.price_rank.buttons[0]);
				return self
			}
		},
		value: 0,
		set: undefined,
		reset: undefined
	};


	this.init(id, uid);
	return this;
}

Orb.prototype = {
	constructor: Orb,
	init_list: ['id', 'uid', 'price_rank', 'quantity', 'note', 'orbopts'],
	init: function(id, uid) {
		this.DOM.id = $("#OrderOrbId")[0];
		this.DOM.uid = $("#OrderOrbUid")[0];
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		this.id.set(id);
		this.uid.set(uid);
		return this;
	},
	import_from_cart: function(orb_data) {
//		try {
			this.id.set(orb_data.orb.Orb.id);
			this.quantity.set( orb_data.pricing.quantity );
			this.note.set( orb_data.orb.Orb.note );
			this.price_rank.set( orb_data.pricing.rank );
			var opts = obj_values(orb_data.orbopts)
			for (var i = 0; i < opts.length; i++) {
				var opt = new Orbopt();
				opt.import_data(opts[i]);
				this.orbopts.push( opt );
			}
//		} catch (e) { throw OrbImportError(e, e.message) }
		return this;
	},
	configure: function(rank) {
		var self = this;
		// for every orbopt present in the orbcard form and not this.orbopts, create a new opt
		$(XSM.menu.orb_order_form_orb_opts).each(function () {
			if ( !self.orbopts.find( $(this).data('id') ) ) self.orbopts.Opts.push( new Orbopt($(this).data('id')) );
		});
		this.uid.set();
		for (var i = 0; i < this.orbopts.Opts.length; i++) this.orbopts.Opts[i].configure();
		this.DOM.button = $(["#orb", this.id.value].join("-"))[0];
		this.DOM.quantity = $(XSM.menu.orb_order_form_quantity)[0];
		this.DOM.price_rank.input = $(XSM.menu.orb_order_form_price_rank)[0];
		$(XSM.menu.orb_size_button).each( function() { self.DOM.price_rank.buttons[$(this).data('rank')] = this; });
		this.price_rank.set(rank)
		try {
			this.inspect();
		} catch (e) {
			if (e.message != "Cannot read property 'flag_map' of undefined") throw e
		}
		return this
	},
	inspect: function() {
		this.orbopts.counts.reset();
		for (var i = 0; i < this.orbopts.Opts.length; i++) {
			var opt = this.orbopts.Opts[i];
			if ( opt.selected() ) {
				if ( opt.is('sauces') ) {
					if ( this.orbopts.counts.sauces < 1) {
						var target_score = this.orbopts.counts.sauces + opt.coverage.score;
						if ( target_score > 1) opt.coverage.select( opt.coverage.value == "R" ? "L" : "R" )
						this.orbopts.counts.sauces += opt.coverage.score();
					} else {
						opt.deselect()
					}
				}
				if ( opt.is('cheeses') ) this.orbopts.counts.cheeses.selected++
				if ( opt.is('premium') ) this.orbopts.counts.premium.selected++
			}
		}
		this.toggle_sauces();
	},
	toggle_sauces: function() {
		for (var i = 0; i < this.orbopts.Opts.length; i++) {
			var opt = this.orbopts.Opts[i];
			if ( opt.is("sauces") ) {
				if (this.orbopts.counts.sauces <= 0.5) opt.enable()
				if (this.orbopts.counts.sauces >= 1 && !opt.selected()) opt.disable()
			}
		}
	},

	filter: function(inactive_flags) {
		$(this.orbopts.Opts).each( function() { this.filter.set(inactive_flags) });
		$(this.orbopts.Opts).each(function() { this.hide() });
		var self = this;
		setTimeout( function() { $(self.orbopts.Opts).each(function() { this.show(true) }) }, 330);
	},
	toggle_opt: function(id, weight) {
		var opt = this.orbopts.find(id);
		if (weight && !opt.is("sauces") ){ // ie. just switching non-sauces coverage
			opt.coverage.select(weight);
		} else if (opt.is("sauces") ) {
			if (opt.coverage.value() == weight ) { // ie. moving from L/R/D back to F
				var selected = this.orbopts.selected('sauces');
				selected.length == 2 ? opt.deselect() : opt.coverage.select("F");
			} else if ( this.orbopts.counts.sauces == 0.5 ) { // ie. a half-sauce already picked
				var pair_opt = this.orbopts.selected('sauces')[0];
				// if choosing a specific half, move the other sauce to the now-empty half
				if (weight == "R") pair_opt.coverage.select("L");
				if (weight == "L") pair_opt.coverage.select("R");
				if (!weight) { // choose remaining half if merely activating opt
					opt.toggle();
					opt.coverage.select( pair_opt.coverage.value == "R" ? "L" : "R")
				} else { // choose the now-free half as requested
					opt.coverage.select(weight);
				}
			} else {
				if (weight) {
					var target_weight = opt.coverage.weights[weight].score + this.orbopts.counts.sauces;
					var selected = this.orbopts.selected('sauces');
					if (selected.length == 1 && selected[0] == opt) {
						opt.coverage.select(weight);
					} else if (target_weight > 1) {
						if ( selected.length == 1 ) {
							selected[0].deselect();
						} else {
							selected[0] == opt ? selected[1].deselect() : selected[0].deselect();
						}
						opt.coverage.select(weight);
					}
				} else {
					opt.toggle();
				}
			}
		} else {
			opt.toggle();
		}

		this.inspect();
//		if ( opt.is('sauces') ) {
//			this.orbopts.counts.sauces = !opt.selected() ? 1 : 0;
//			this.toggle_sauces();
//		}
//		opt.toggle();
		$(XT.orbcard).trigger(C.ORB_CFG);
	},
	render: function() {
		XT.layout.activize(this.DOM.button);
		this.quantity.set();
		this.note.set();
		this.price_rank.set();
		this.orbopts.render();

		return this
	},
	reset: function() {
		this.quantity.reset();
		this.note.reset();
		this.price_rank.reset();
		$(this.orbopts.Opts).each( function() { this.reset() } );

		return this
	},
	unload: function() {
		this.reset();
		$(this.orbopts.Opts).each( function() { this.unload() } );

		return this
	}
}


/**
 * OrbInflector Class
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
