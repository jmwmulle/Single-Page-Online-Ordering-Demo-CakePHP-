/**
 * Created by jono on 8/11/15.
 *
 * Orbopt Class
 *
 * @param id
 * @param weight
 * @param flags
 * @returns {Orbopt}
 * @constructor
 */
Orbopt = function(id) {
	this.id = id;
	this.DOM = {
		input: undefined,
		button: undefined,
		coverage: {
			F: undefined,
			D: undefined,
			R: undefined,
			L: undefined,
			icon: undefined
		}
	};
	this.active = false;
	this.filter = {
		init: function(self) {
			self.filter.set = function(inactive_flags) {
				self.filter.state = false;
				for (var i = 0; i < inactive_flags.length; i++) {
					for (var j = 0; j < self.flags.length; j++ ) {
						if (inactive_flags[i] == self.flags[j]) self.filter.state = true;
					}
				}
				$(self.DOM.button)[self.filter.state ? "removeClass" : "addClass"]("no-filter")
			}
		},
		set: undefined,
		apply: undefined,
		state: undefined
	};
	this.pricelist = {id:null, ranks:[]};
	this.title = undefined;
	this.coverage = {
		init: function(self) {
			self.coverage.icon = function() {
				if ( self.coverage.value() ) return "";
				return self.coverage.weights[self.coverage.value()].icon };
			self.coverage.value = function() {
				for (var weight in self.coverage.weights) if ( self.coverage.weights[weight].selected ) return weight;
				return -1
			};
			self.coverage.select = function(selected_weight) {
				if ( XT.orbcard.menu.portionable() ) {
					for (var weight in self.coverage.weights) self.coverage.weights[weight].selected = (weight == selected_weight);
				} else {
					var value = self.coverage.value();
					var f = true;
					if (selected_weight == "D") {
						f = value == "D";
					} else if (selected_weight == "F") {
						f = value != "F";
					}
					self.coverage.weights.D.selected = !f;
					self.coverage.weights.F.selected = f;
				}
				self.coverage.set();
			},
			self.coverage.set = function() {
				$(self.DOM.input).val( self.coverage.value() )
				XT.layout.activize(self.DOM.coverage[ self.coverage.value() ])
			};
			self.coverage.enable = function() {
				for (var weight in self.coverage.weights) {
					$(self.DOM.coverage[weight]).removeClass(FX.disabled).addClass(FX.enabled);
					if ( self.coverage.weights[weight].selected ) XT.layout.activize(self.DOM.coverage[weight])
				}
			};
			self.coverage.disable = function() {
				for (var weight in self.coverage.weights) {
					$(self.DOM.coverage[weight]).removeClass(FX.enabled).addClass(FX.disabled);
				}
			},
			self.coverage.score = function() {
				if (self.coverage.value() == -1) return -1;
				return self.coverage.weights[self.coverage.value()].score }

		},
		enable: undefined,
		disable: undefined,
		select: undefined,
		set: undefined,
		value: undefined,
		icon: undefined,
		score: undefined,
		weights: {
			F: {selected: false, score: 1, icon:""},
			D: {selected: false, score: 1, icon:"<span class='icon-double tiny-opt icon-hnj-inline'></span>"},
			R: {selected: false, score: 0.5, icon:"<span class='icon-right-side tiny-opt icon-hnj-inline'></span>"},
			L: {selected: false, score: 0.5, icon:"<span class='icon-left-side tiny-opt icon-hnj-inline'></span>"}
		}
	};
	this.flags = [];
	this.init();
	return this;
}

Orbopt.prototype = {
	constructor:Orbopt,
	init_list: ['coverage', 'filter'],
	init: function() {
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		return this;
	},
	deselect: function() {
		$(this.DOM.button).addClass( FX.inactive).removeClass( FX.active);
		this.coverage.disable();
		this.active = false;
	},
	select: function() {
		this.active = true;
		$(this.DOM.button).addClass( FX.active ).removeClass( FX.inactive );
		this.coverage.enable();
		return this
	},
	selected: function() { return !!this.active && $(this.DOM.button).hasClass(FX.active) },
	toggle: function() {
		!this.active ? this.select() : this.deselect();
		this.coverage.set();
	},
	import_data: function(opt_data) {
		//TODO: nothing here to set state to active if selected
		try {
			this.id = opt_data.Orbopt.id;
			for ( var weight in this.coverage) this.coverage[weight][0] = weight == opt_data.Orbopt.coverage;
			this.title = opt_data.Orbopt.title;
			this.pricelist = {id: opt_data.Pricelist.id, ranks: obj_values(opt_data.Pricelist, 2)};
		} catch (e) { throw "Orbopt Import Error: " + e.message; }
		return true;
	},
	disable: function() {
		this.deselect();
		$(this.DOM.button).addClass([FX.inelligible, FX.disabled].join(" "));

	},
	enable: function() {
		$(this.DOM.button).removeClass([FX.inelligible, FX.disabled].join(" "))
		$(this.DOM.coverage).each( function() { $(this).removeClass(FX.inelligible) } );
	},
	configure: function() {
		this.DOM.input = $(["#OrderOrbOrbopt", this.id].join(""))[0];
		this.DOM.button = $(["#orb-opt", this.id].join("-"))[0];
		for (var weight in this.coverage.weights) {
			var cvg_classes = {F: "full", D: "double", L:"left-side",R: "right-side"}
			this.DOM.coverage[weight] = $([".orb-opt-coverage", cvg_classes[weight]].join("."), this.DOM.button)[0];
//			if ( $(this.DOM.coverage[weight]).hasClass(FX.active) ) this.coverage.weights[weight].selected = true;

		}
		// get all flags not already present (ie. if a new orb is loaded from the DOM as against cart)
		this.flags = $(this.DOM.button).data('optflags');
		this.title = $(this.DOM.button).data('title');
		if ( XT.orbcard.menu.portionable() ) {
			$(this.DOM.button).hasClass(FX.default)  ? this.select().coverage.select("F") : this.deselect();
		} else {
			if (!this.is("sauces") ) {
				$(this.DOM.button).hasClass(FX.default)  ? this.select().coverage.select("D") : this.deselect();
			}
		}
		return this;
	},
	hide: function() {
		var self = this;
		$(this.DOM.button).addClass(FX.fade_out);
		setTimeout(function() { $(self.DOM.button).addClass(FX.hidden) }, 300);
		return this
	},
	show: function(unfiltered_only) {
		var self = this;
		if (unfiltered_only && this.filter.state === true) return;
		$(this.DOM.button).removeClass(FX.hidden);
		setTimeout(function() { $(self.DOM.button).removeClass(FX.fade_out) }, 30);
		return this
	},
	mini_cfg: function() {
		return $("<li/>").addClass(stripCSS(XSM.menu.tiny_orb_opts_list_item))
			.append(["<span class='tiny-opt-label'>", this.title.toTitleCase() ,"</span>"].join(""))
			.append( this.coverage.icon() );
	},

	render: function() {
		var self = this;
		var value = self.coverage.value();
		$(this.DOM.input).val( value );
	},
	reset: function() {
		$(this.DOM.button)
			.removeClass([FX.inelligible, FX.active].join(" "))
			.addClass(FX.inactive);
		this.render();
	},
	unload: function() { $(this.DOM.button).remove() },
	is: function(flag) {
		for (var i =0; i < this.flags.length; i++) if (XT.orbcard.flag_map[ this.flags[i] ] == flag) return true;
		return false
	}

}