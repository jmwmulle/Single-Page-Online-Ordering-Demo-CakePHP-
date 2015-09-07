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
			self.orbopts.render = function(restore_default) {
				$(self.orbopts.Opts).each( function() {
					if (restore_default) this.reset(true);
					this.coverage.set()
				} )
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
				self.orbopts.counts.sidesauce.selected = 0;
			}
		},
		counts: {
			reset: undefined,
			sauces: 0,
			sidesauce: {included: 0, selected:0},
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
	init_list: ['id', 'uid', 'price_rank', 'quantity', 'note', 'orbopts', 'sauces', 'opts'],
	init: function(id, uid) {
		this.DOM.id = $("#OrderOrbId")[0];
		this.DOM.uid = $("#OrderOrbUid")[0];
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		this.id.set(id);
		this.uid.set(uid);
		return this;
	},
	import_from_cart: function(orb_data) {
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
	iter_opts: function() {
		var opts = {};
		for (var i = 0; i < this.orbopts.Opts.length; i++) { opts[i] = this.orbopts.Opts[i] }
		return opts;
	},
	inspect: function() {
		var opts = this.iter_opts();
		this.orbopts.counts.reset();
		for (var i in opts ) {
			var opt = opts[i];
			if ( opt.selected() ) {
				if ( opt.is('sauces') ) {
					if (this.orbopts.counts.sauces < 1 || !this.sauces.paired(opt) ) {
						this.orbopts.counts.sauces += opt.coverage.score();
					} else {
						opt.deselect()
					}
				}
				if ( opt.is('cheeses') ) this.orbopts.counts.cheeses.selected++
				if ( opt.is('premium') ) this.orbopts.counts.premium.selected++
			}
		}
		this.sauces.filter();
	},
	filter: function(inactive_flags) {
		$(this.orbopts.Opts).each( function() { this.filter.set(inactive_flags) });
		$(this.orbopts.Opts).each(function() { this.hide() });
		var self = this;
		setTimeout( function() { $(self.orbopts.Opts).each(function() { this.show(true) }) }, 330);
	},
	toggle_opt: function(id, weight) {
		var opt = this.orbopts.find(id);
		if ( opt.is('sauces') ) {
			this.sauces.toggle(opt, weight);
		} else {
			this.opts.toggle(opt, weight);
		}
		this.inspect();
		$(XT.orbcard).trigger(C.ORB_CFG);
	},
	render: function() {
		XT.layout.activize(this.DOM.button, false);
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
	opts: {
		init: function(self) {
			self.opts.toggle = function(opt, weight) {
				if ( weight === false && opt.selected() ) return opt.deselect();
				if (opt.selected() && weight == opt.coverage.value() ) {
					if ( XT.orbcard.menu.portionable() ) {
						return opt.deselect()
					} else {
						weight = "F";
					}
				}
				if (!opt.selected() && weight == false) weight = "F";
				opt.select(weight);
			}
		},
		toggle: undefined
	},
	sauces: {
		init: function(self) {
			self.sauces.paired = function(opt) {
				var selected = self.orbopts.selected('sauces');
				if (selected.length  == 0) return false;
				if (selected.length  == 1 && selected[0] == opt) return false;
				return selected[0] == opt ? selected[1] : selected[0]
			};
			self.sauces.select = function(opt, weight) {
				var pair = self.sauces.paired(opt);
				if (pair) {
					var opt_score;
					if ( ! opt.selected() ) {
						opt_score = opt.coverage.score();
					} else {
						opt_score = weight ? self.sauces.weight_vals[weight] : opt.coverage.score();
					}
					var opt_val = weight ? weight: opt.coverage.value();
					if (opt_score == -1) opt_score = 1; // ie "F"
					if (opt_val == -1) opt_val = "F"; // ie. not set
					if (pair.coverage.value() == opt_val) throw E.PORTION_COLLISION;
					if (pair.coverage.score() + opt_score > 1) throw E.SAUCE_OVERWEIGHT;
				}
				if ( weight == undefined || opt.coverage.value() == -1) weight = "F";
				if ( weight === opt.coverage.value() ) throw E.PORTION_RESELECT;
				opt.select(weight);
			};
			self.sauces.filter = function() {
				var opts = self.iter_opts();
				for (var i in opts) {
					var opt = opts[i];
					if ( opt.is("sauces") && !opt.selected() ) {
						self.orbopts.counts.sauces >= 1 ? opt.disable() : opt.enable()
					}
				}
			};
			self.sauces.toggle = function(opt, weight) {
				try {
					if ( opt.selected() ) {
						if (weight === false) return opt.deselect()
						if (weight === "F" && opt.selected() && opt.coverage.value() == "F") return opt.deselect()
					}
					self.sauces.select(opt, weight);
				}
				catch (e) {
					self.sauces.toggle_loops++;
					if (self.sauces.toggle_loops > 10) die();
					var pair = self.sauces.paired(opt);
					switch (e) {
						case E.PORTION_COLLISION:
							if (pair) {
								pair.coverage.flip();
							} else {
								weight == "F"
							}
							break;
						case E.SAUCE_OVERWEIGHT:
							if (weight === false || weight == undefined) {
								pair.select("L");
								weight = "R"
							} else {
								if (self.sauces.weight_vals[weight] > 0.5) {
									pair.deselect();
								} else {
									opt.select(weight == "R" ? "L" : "R")
									self.orbopts.counts.sauces = 0.5;
								}
							}
							break;
						case E.PORTION_RESELECT:
							if (pair || weight == "F") {
								opt.deselect();
								return
							} else {
								weight = "F";
							}
							break;
						default:
							throw e
					}
					self.sauces.toggle(opt, weight)
				}
			}
		},
		toggle_loops: 0,
		paired: undefined,
		select: undefined,
		toggle: undefined,
		filter: undefined,
		weight_vals: {F:1, D:2, L:0.5, R:0.5}
	},
	unload: function() {
		this.reset();
		$(this.orbopts.Opts).each( function() { this.unload() } );

		return this
	}
}

