/**
 * Created by jono on 4/30/15
 *
 */


SpecialsCreator = function() {
	this.init();
	return this;
};

SpecialsCreator.prototype = {
	constructor: SpecialsCreator,
	max_c_length: 50,
	init_list: ['orblist', 'current', 'breakout'],
	sections: {
		method: false,
		quantity: false,
		criteria: false,
		content: true,
		price: true,
		order_method: true
	},
	DOM: {
		buttons: {
			save: undefined,
			cancel: undefined
		},
		fields: {
			title: undefined,
			vendor_title: undefined,
			active: undefined,
			inactive: undefined,
			price: undefined,
			description: undefined
		},
		features: {
			box: undefined,
			table: undefined,
			method: {
				select: {
					wrapper: undefined,
					field: undefined,
					first_opt: undefined
				},
				choice: {
					box: undefined,
					content: undefined
				},
				box: undefined
			},
			quantity: {
				select: {
					wrapper: undefined,
					field: undefined,
					first_opt: undefined
				},
				choice: {
					box: undefined,
					content: undefined
				},
				box: undefined
			},
			criteria: {
				select: {
					wrapper: undefined,
					field: undefined,
					first_opt: undefined
				},
				choice: {
					box: undefined,
					content: undefined
				},
				box: undefined
			}
		},
		conditions: {
			box: undefined,
			table: undefined,
			content: {
				select: {
					wrapper: undefined,
					field: undefined,
					first_opt:undefined
				},
				choice: {
					box: undefined,
					content: undefined
				},
				box: undefined
			},
			price: {
				select: {
					wrapper: undefined,
					field: undefined,
					first_opt:undefined
				},
				choice: {
					box: undefined,
					content: undefined
				},
				box: undefined
			},
			order_method: {
				select: {
					wrapper: undefined,
					field: undefined,
					first_opt:undefined
				},
				choice: {
					box: undefined,
					content: undefined
				},
				box: undefined
			}
		},
		breakouts: {
			orb:{
				box: undefined,
				index: undefined,
				collection: undefined,
				input: undefined,           // unused
				buttons: {
					add: undefined,
					remove: undefined,
					save: undefined,
					cancel: undefined
				},
				sizes: {
					wrapper: undefined,
					content: undefined
				}
			},
			orbcat:{
				box: undefined,
				index: undefined,
				collection: undefined,
				input: undefined,           // unused
				buttons: {
					add: undefined,
					remove: undefined,
					save: undefined,
					cancel: undefined
				}
			},
			price:{
				box: undefined,
				index: undefined,           // unused
				collection: undefined,      // unused
				input: undefined,
				buttons: {
					add: undefined,         // unused
					remove: undefined,      // unused
					save: undefined,
					cancel: undefined
				}
			},
			orblist: {
				box: undefined,
				index: undefined,           // unused
				collection: undefined,      // unused
				input: undefined,
				meta: undefined,
				active: undefined,
				buttons: {
					create: undefined,
					add: undefined,
					remove: undefined,
					save: undefined,
					cancel: undefined
				}
			}
		}
	},
	current: {
		fields: {
			title: undefined,
			vendortitle: undefined,
			active: true,
			price: undefined,
			description: undefined
		},
		init: function(self) {
			self.current.features.init(self);
			self.current.conditions.init(self);

			self.current.update = function() {
				var saveable = true;
				for (var f in self.current.fields) {
					if (f == "active") continue;
					var val = $(self.DOM.fields[f]).val();
					self.current.fields[f] = val != "" ? val : undefined;
					if ( !defined(self.current.fields[f]) ) saveable = false;
				}
				$(self.DOM.buttons.save)[saveable ? 'removeClass' : 'addClass'](FX.disabled);
			};

			self.current.save = function() {
				var special = self.current.fields;
				special.vendor_title = special.vendortitle;
				delete(special.vendortitle);
				var features = self.current.format_features();
				var conditions = self.current.format_conditions();
				var specials_orbs = features[1];
				for (var i=0; i < conditions[1].length; i++) specials_orbs.push(conditions[1][i]);
				var data = {
					Special: special,
					SpecialFeature: features[0],
					SpecialCondition: conditions[0],
					SpecialsOrbs: specials_orbs
				};
				$.ajax({
					type: C.POST,
					url: "save-special",
					data: data,
					success: function (data) {
						try {
							data = JSON.parse(data);
						} catch (e) {
							$("#primary-modal-content", C.BODY).append($("<div />").html(data));
							die();
						}
						if (data.success == true) {
							XT.vendor_ui.reload_tab('specials');
							$(XT.router).trigger(C.ROUTE_REQUEST, {request:["close_modal","primary"].join(C.DS), trigger:{}});
						}
					}
				});
			};

			self.current.format_features = function() {
				var formatted = [];
				var specials_orbs = [];
				for (var f in self.current.features.saved) {
					var f = self.current.features.saved[f];
					var feature = {choose: false, receive: false, quantity:1, orblist_id: null, orbcat_id: null};
					feature.choose = f.method.opt == "choose" ? 1 : 0;
					feature.receive = f.method.opt == "receive" ? 1 : 0;
					feature.quantity = f.quantity.value;
					if (f.criteria.opt == "orblist") feature.orblist_id = f.criteria.value;
					if (f.criteria.opt == "orbcat") feature.orbcat_id = f.criteria.value;
					if (f.criteria.opt == "orb") {
						for (var i = 0; i < f.criteria.value.length; i++) {
							var sp_o = {
								orb_id:f.criteria.value[i][0],
								feature:1,
								condition: 0,
								price_1: 0,
								price_2: 0,
								price_3: 0,
								price_4: 0,
								price_5: 0
							}
							for (var j = 0; j < f.criteria.value[i][1].length + 1; j++) {
								sp_o['price_'+f.criteria.value[i][1][j]] = 1
							}
							specials_orbs.push(sp_o)
						}
					}
					formatted.push(feature);
				}
				return [formatted, specials_orbs];
			};
			self.current.format_conditions = function() {
				var formatted = [];
				var specials_orbs = [];
				for (var c in self.current.conditions.saved) {
					var c = self.current.conditions.saved[c];
					var condition = {
						orblist_id: null,
						orbcat_id: null,
						price_min: null,
						price_max: null,
						delivery: false,
						pickup: false
					};
					if (defined(c.price) && c.price.opt == "max") condition.price_max = c.price.value;
					if (defined(c.price) && c.price.opt == "min") condition.price_min = c.price.value;
					condition.delivery = defined(c.order_method) && c.order_method.opt == "delivery" ? 1 : 0;
					condition.pickup = defined(c.order_method) && c.order_method.opt == "pick-up" ? 1 : 0;
					if (defined(c.content) && c.content.opt == "orblist") condition.orblist_id = c.content.value;
					if (defined(c.content) && c.content.opt == "orbcat") condition.orbcat_id = c.content.value;
					if (defined(c.content) && c.content.opt == "orb") {
						for (var i = 0; i < c.content.value.length; i++) {
							var sp_o = {
								orb_id: c.content.value[i][0],
								feature:1,
								condition: 0,
								price_1: 0,
								price_2: 0,
								price_3: 0,
								price_4: 0,
								price_5: 0
							}
							for (var j = 0; j < c.content.value[i][1].length; j++) {
								sp_o['price_'+ c.content.value[i][1][j]] = 1
							}
							specials_orbs.push(sp_o)
						}
					}
					formatted.push(condition);
				}
				return [formatted, specials_orbs];
			}

			self.current.active = function(state) {
				self.current.fields.active = Number(state) == 1;
				var active_el = self.current.fields.active ? "active" : "inactive";
				var inactive_el = self.current.fields.active ? "inactive" : "active";
				$(self.DOM.fields[active_el]).addClass(FX.active).removeClass(FX.inactive);
				$(self.DOM.fields[inactive_el]).addClass(FX.inactive).removeClass(FX.active);
			}
		},
		active: undefined,
		update: undefined,
		save: undefined,
		features: {
			section: "features",
			in_progress: false,
			saved: {},
			method: {opt:undefined, value: undefined, content: undefined},
			quantity: {opt:undefined, value: undefined, content: undefined},
			criteria: {opt:undefined, value: undefined, content: undefined},

			init: function(self) {
				var f = self.current.features;
				f.create = function() {
					if (f.in_progress) {
						f.in_progress = false;
						f.reset('method');
						f.reset('quantity');
						f.reset('criteria');
						setTimeout( function() { f.create(); }, 1000);
						return
					}
					f.in_progress = true;
					f.show();
				};

				f.show = function() {
					$(self.DOM.features.box).removeClass(FX.hidden);
					setTimeout(function() { $(self.DOM.features.box).removeClass(FX.fade_out) }, 30);
				};

				f.hide = function() {
					$(self.DOM.features.box).addClass(FX.fade_out);
					setTimeout(function() { $(self.DOM.features.box).addClass(FX.hidden) }, 30);
				};

				f.cancel = function() {
					f.hide()
					setTimeout( function() {
						f.reset('method');
						f.reset('quantity');
						f.reset('criteria');
						 }, 350);
				};

				f.select = function(target) {
					var opt = $("option:selected", self.DOM.features[target].select.field)[0];
					var breakout = $(opt).data('breakout');
					f[target].opt = $(self.DOM.features[target].select.field).val();
					if ( breakout != "0") return self.breakout.launch(breakout, f.section, target);

					f[target].value = $(self.DOM.features[target].select.field).val();
					f[target].content = $(opt).html();
					f.update(target)
				};

				f.reset = function(target, suppress_check) {
					var t = target;
					f[t] = {opt: undefined, value: undefined, content: undefined};
					$(self.DOM.features[t].select.field).val(self.DOM.features[t].select.first_opt);

					$(self.DOM.features[t].choice.box).addClass(FX.fade_out);
					setTimeout( function() { $(self.DOM.features[t].choice.box).addClass(FX.hidden) }, 330);
					setTimeout( function() { $(self.DOM.features[t].select.wrapper).removeClass(FX.hidden) }, 350);
					setTimeout( function() { $(self.DOM.features[t].select.wrapper).removeClass(FX.fade_out) }, 380);
					if ( !suppress_check ) f.check_exclusions();
				};

				f.update = function(target) {
					$(self.DOM.features[target].choice.content).html(f[target].content);
					f.check_exclusions();

					$(self.DOM.features[target].select.wrapper).addClass(FX.fade_out);
					setTimeout( function() { $(self.DOM.features[target].select.wrapper).addClass(FX.hidden) }, 330);
					setTimeout( function() { $(self.DOM.features[target].choice.box).removeClass(FX.hidden) }, 350);
					setTimeout( function() { $(self.DOM.features[target].choice.box).removeClass(FX.fade_out) }, 380);
				};

				f.save = function() {
					var id = f.section + now();
					var feature = {
						id: id,
						section: f.section,
						sentence: [f.method.content, f.quantity.content, "items from", f.criteria.content].join(" "),
						method: f.method,
						quantity: f.quantity,
						criteria: f.criteria
					}
					self.current.features.saved[id] = feature;
					f.cancel();
					$(self.DOM.features.table).append( self.table_row(feature) );
				};

				f.check_exclusions = function() {
					if ( !defined(f.method.value) ) {
						f.reset("criteria", true);
						f.reset("quantity", true);
						$(self.DOM.features.criteria.select.field).attr(FX.disabled, true);
						$(self.DOM.features.quantity.select.field).attr(FX.disabled, true);
					} else {
						$(self.DOM.features.criteria.select.field).removeAttr(FX.disabled);
						$(self.DOM.features.quantity.select.field).removeAttr(FX.disabled);
					}
					$("option", self.DOM.features.criteria.select.field).each( function() {
						$(this).removeClass(FX.hidden);
						if ( $(this).val() == "orb" && f.method.opt == "choose" ) $(this).addClass(FX.hidden);
						if ( $(this).val() != "orb" && f.method.opt == "receive" ) $(this).addClass(FX.hidden);
					});
				};

				f.delete = function(id) {
					$( as_id(id) ).remove();
					delete(f.saved[id]);
				}


			},
			create: undefined,
			cancel: undefined,
			select: undefined,
			reset: undefined,
			save: undefined,
			show: undefined,
			hide: undefined,
			update: undefined,
			check_exclusions: undefined,
			delete: undefined
		},
		conditions: {
			active: undefined,
			section: "conditions",
			saved: {},
			in_progress: undefined,
			content: {opt:undefined, value: undefined, content: undefined},
			price: {opt:undefined, value: undefined, content: undefined},
			order_method: {opt:undefined, value: undefined, content: undefined},

			init: function(self) {
				var c = self.current.conditions;

				c.show = function() {
					$(self.DOM.conditions.box).removeClass(FX.hidden);
					setTimeout(function() { $(self.DOM.conditions.box).removeClass(FX.fade_out); }, 30);
				};


				c.hide = function() {
					$(self.DOM.conditions.box).addClass(FX.fade_out);
					setTimeout(function() { $(self.DOM.conditions.box).addClass(FX.hidden); }, 300);
				};

				c.create = function() {
					if (c.in_progress) {
						c.reset('content');
						c.reset('price');
						c.reset('order_method');
						c.in_progress = false;
						c.create();
						return
					}
					c.in_progress = true;
					c.show();
				};

				c.reset = function(target, preserve_disable) {
					var t = target;
					c[t] = {opt: undefined, value: undefined, content: undefined};
					$(self.DOM.conditions[t].select.field).val(self.DOM.conditions[t].select.first_opt);

					$(self.DOM.conditions[t].choice.box).addClass(FX.fade_out);
					setTimeout( function() { $(self.DOM.conditions[t].choice.box).addClass(FX.hidden) }, 330);
					setTimeout( function() { $(self.DOM.conditions[t].select.wrapper).removeClass(FX.hidden) }, 350);
					setTimeout( function() { $(self.DOM.conditions[t].select.wrapper).removeClass(FX.fade_out) }, 380);
					var sections = ['content', 'price', 'order_method'];
					if ( !preserve_disable) {
						c.active = undefined
						for ( var i = 0; i < sections.length; i++ ) {
							$(self.DOM.conditions[sections[i]].select.field).removeAttr('disabled');
						}
					}
				};

				c.cancel = function() {
					c.hide();
					c.active = undefined;
					setTimeout(function() {
							c.reset('content');
							c.reset('price');
							c.reset('order_method');
					}, 330);
				};

				c.select = function(target) {
					c.active = target;
					var sections = ['content', 'price', 'order_method'];
					for ( var i = 0; i < sections.length; i++ ) {
						if ( sections[i] != target ) {
							$(self.DOM.conditions[sections[i]].select.field).attr('disabled', true);
						}
					}
					var opt = $("option:selected", self.DOM.conditions[target].select.field)[0];
					var breakout = $(opt).data('breakout');
					c[target].opt = $(opt).val();
					if ( breakout != "0") return self.breakout.launch(breakout, c.section, target);
					c[target].value = $(self.DOM.conditions[target].select.field).val();
					c[target].content = $(opt).html();
					c.update(target)

				};

				c.update = function(target) {
					$(self.DOM.conditions[target].choice.content).html(c[target].content);
					$(self.DOM.conditions[target].select.wrapper).addClass(FX.fade_out);
					setTimeout( function() { $(self.DOM.conditions[target].select.wrapper).addClass(FX.hidden) }, 330);
					setTimeout( function() { $(self.DOM.conditions[target].choice.box).removeClass(FX.hidden) }, 350);
					setTimeout( function() { $(self.DOM.conditions[target].choice.box).removeClass(FX.fade_out) }, 380);
				};

				c.save = function() {
					var id = c.section + now();
					var sentence;
					switch (c.active) {
						case "content":
							sentence = "Order must include " + c.content.content;
							break;
						case "price":
							sentence = "Order must cost " + c.price.content;
							break;
						case "order_method":
							sentence = "Order must be for " + c.order_method.content;
							break;
					}
					var condition = {
						id: id,
						section: c.section,
						sentence: sentence
					}

					condition[c.active] = c[c.active];
					self.current.conditions.saved[id] = condition;
					c.cancel();
					$(self.DOM.conditions.table).append( self.table_row(condition) );
				};

				c.delete = function(id) {
					$( as_id(id) ).remove();
					delete(c.saved[id]);
				}

			},
			create: undefined,
			cancel: undefined,
			edit: undefined,
			hide: undefined,
			save: undefined,
			select: undefined,
			show: undefined,
			update: undefined
		}
	},
	orblist: {
		lists: {},
		active: undefined,
		init: function(self) {
			var o = self.orblist;

			o.load = function() {
				for (var j = 0; j < o.active.orbs.length; j++) {
					var c_id = as_id(["orblist", "selector", "collection", o.active.orbs[j].id].join("-"));
					var i_id = as_id(["orblist", "selector", "index", o.active.orbs[j].id].join("-"));
					$(c_id).removeClass(FX.hidden);
					$(i_id).attr(FX.disabled, true);
				}
				$(self.DOM.breakouts.orblist.active).html(title_case(o.active.name));
			};

			o.populate = function() {
				$.ajax({
					type: C.GET,
					url: "load-orblists",
					success: function (data) {
						data = JSON.parse(data);
						if (data.success == true) {
							for (var i = 0; i < data.data.length; i++) {
								o.lists[data.data[i].id] = data.data[i];
							}
						}
					}
				});
			};

			o.select = function() {
				o.active = o.lists[$($("option:selected", self.DOM.breakouts.orblist.meta)[0]).val()];
				$(self.DOM.breakouts.orblist.buttons.delete).removeClass(FX.disabled);
				o.load()
			};

			o.save = function(section, select, opt) {
				if ( !defined(o.active) ) return false;
				self.current[section][select].opt = opt;
				self.current[section][select].value = o.active.id;
				self.current[section][select].content = o.active.name;
				return true
			}

			o.create = function() {
				var name = $(self.DOM.breakouts.orblist.input).val();
				if (name.length == 0) {
					$(self.DOM.breakouts.orblist.input).addClass(FX.error);
					return;
				}
				$(self.DOM.breakouts.orblist.input).removeClass(FX.error);
				$.ajax({
					type: C.POST,
					url: "create-orblist",
					data: {name:name, deprecated:0},
					success: function (data) {
						data = JSON.parse(data);
						if (data.success == true) {
							$(self.DOM.breakouts.orblist.meta).append(
								$("<option />")
									.attr('id', "orblist-selector-meta-" + data.data.id)
									.val(data.data.id)
									.html(name)
							);
							o.lists[data.data.id] = {id:data.data.id, name: name, orbs:[], deprecated:false};
						}
					}
				});
			};

			o.update = function() {
				var orbs = [];
				$("option", self.DOM.breakouts.orblist.collection).each(function() {
					if ( !$(this).hasClass(FX.hidden) ) orbs.push({id: $(this).val()});
				});
				$.ajax({
					type: C.POST,
					url: "update-orblist/"+o.active.id,
					data: {Orb:orbs},
					success: function (data) {
						data = JSON.parse(data);
						if (data.success == true) {
							o.lists[o.active.id].orbs = data.data.orbs;
							o.inspect_active();
						}
					}
				});
				return true;
			};

			o.inspect_active = function() {
				var update_possible = false;
				var collection = [];
				var active = []
				$("option", self.DOM.breakouts.orblist.collection).each( function() {
					if ( !$(this).hasClass(FX.hidden) ) collection.push( $(this).val() );
				});
				for (var i=0; i < o.active.orbs.length; i++) {
					active.push(o.active.orbs[i].id);
					if (! in_array(o.active.orbs[i].id, collection)) {
						update_possible = true;
						break;
					}
				}
				if ( !update_possible ) {
					for (var i=0; i < collection.length; i++) {
						if (! in_array(collection[i], active)) {
							update_possible = true;
							break;
						}
					}
				}
				if (update_possible) {
					$(self.DOM.breakouts.orblist.buttons.update).removeClass(FX.disabled)
				} else{
					$(self.DOM.breakouts.orblist.buttons.update).addClass(FX.disabled)
				}
			};

			o.reset = function() {
				o.populate();
				$("option", self.DOM.breakouts.orblist.index).removeAttr(FX.disabled).removeAttr("selected");
				$("option", self.DOM.breakouts.orblist.collection).addClass(FX.hidden);
				$("option", self.DOM.breakouts.orblist.meta).removeAttr("selected");
				$(self.DOM.breakouts.orblist.buttons.delete).addClass(FX.disabled);
				$(self.DOM.breakouts.orblist.buttons.update).addClass(FX.disabled);
				$(self.DOM.breakouts.orblist.input).val("");
				$(self.DOM.breakouts.orblist.active).html("None");
			}



		},
		inspect_active: undefined,
		populate: undefined,
		sync: undefined,
		save: undefined,
		save_list: undefined,
		load: undefined,
		create: undefined,
		cancel: undefined,
		reset: undefined
	},
	breakout: {
		types:{
			orb: 'multiselect',
			orbcat: 'select',
			price: 'input',
			orblist: 'orblist'
		},
		section: undefined,
		select: undefined,
		init: function(self) {
			var b = self.breakout;

			b.show = function(target) {
				$(self.DOM.breakouts[target].box).removeClass(FX.hidden);
				setTimeout( function() { $(self.DOM.breakouts[target].box).removeClass(FX.fade_out); }, 30);
			};

			b.hide = function(target) {
				$(self.DOM.breakouts[target].box).addClass(FX.fade_out);
				setTimeout( function() { $(self.DOM.breakouts[target].box).addClass(FX.hidden); }, 320);
			};

			b.cancel = function(target) {
				self.current[b.section].reset(b.select);
				b.hide(target);
				b.reset(target);
			};

			b.save = function(target, type) {
				var opt = self.current[b.section][b.select].opt;
				if ( target == "orblist") {
					if ( !self.orblist.save(b.section, b.select, opt) ) return;
				} else {
					if ( !b["save_" + type](target, opt) ) return;
				}

				b.hide(target);
				setTimeout(function() { self.current[b.section].update(b.select); }, 330);
				setTimeout(function() { b.reset(target); }, 350);
			};

			b.save_select = function(target, opt) {
				self.current[b.section].reset(b.select, true);
				var choice = $("option:selected", self.DOM.breakouts[target].index)[0];
				if ($(choice).val() ==  "--" ) {
					$( self.DOM.breakouts[target].index).addClass(FX.error);
					return false;
				}
				$( self.DOM.breakouts[target].index ).removeClass(FX.error);
				self.current[b.section][b.select].opt = opt;
				self.current[b.section][b.select].value = title_case( $(choice).val() );
				self.current[b.section][b.select].content = $(choice).html();

				return true
			};

			b.save_multiselect = function(target, opt) {
				var collection_ids = [];
				var collection_labels = [];
				$("option", self.DOM.breakouts[target].collection).each( function() {
					if ( !$(this).hasClass(FX.hidden) ) {
						var orb_id = $(this).val();
						var sizes = [];
						$('input.orb-size', as_id(["orb", "selector", "sizes", orb_id].join("-"))).each( function() {
							if ( $(this).prop('checked') ) {
								sizes.push( $(this).val() );
							}
						});
						collection_ids.push( [orb_id, sizes] );
						collection_labels.push( $(this).data('label') );
					}
				});
				if (collection_ids.length == 0) {
					$(self.DOM.breakouts[target].collection).addClass(FX.error);
					return false;
				}
				$(self.DOM.breakouts[target].collection).removeClass(FX.error);
				var c_str = String(collection_labels.join(", "));
				if ( c_str.length > self.max_c_length) c_str = c_str.substr(0, self.max_c_length) + "...";
				self.current[b.section].reset(b.select, true);
				self.current[b.section][b.select].opt = opt;
				self.current[b.section][b.select].value = collection_ids;
				self.current[b.section][b.select].content = c_str;

				return true
			};

			b.save_input = function(target, opt) {
				var price_prefix = "";

				if (b.select == "price") {
					price_prefix = $($("option:selected", self.DOM[b.section][b.select].select.field)[0]).html() ;
				}

				self.current[b.section].reset(b.select, true);
				var value = $(self.DOM.breakouts[target].input).val();
				var content = b.select == "price"? price_prefix + " $" + String(Number(value).toFixed(2)) : value;

				if ( value.length == 0 ) {
					$(self.DOM.breakouts[target].input).addClass(FX.error);
					return false;
				}
				$(self.DOM.breakouts[target].input).removeClass(FX.error);
				self.current[b.section][b.select].opt = opt;
				self.current[b.section][b.select].content = content;
				self.current[b.section][b.select].value = value;
				return true;
			}

			b.launch = function(target, section, select) {
				if ( defined(b.section) || defined(b.select) ) return self.current[section].reset(select);
				b.section = section;
				b.select = select;
				b.show(target);
			};

			b.reset = function(target) {
				b.section = undefined;
				b.select = undefined;
				if (target == "orblist") return self.orblist.reset();
				b["reset_" + b.types[target]](target);
			};

			b.reset_select = function(target) {
				$('option', self.DOM.breakouts[target].index).each( function() {
					$(this).removeAttr("selected");
				});
				$(self.DOM.breakouts[target].collection).removeClass(FX.error);
			}


			b.reset_input = function(target) {
				$(self.DOM.breakouts[target].input).removeClass(FX.error).val("");
			}

			b.reset_multiselect = function(target) {
				$('option', self.DOM.breakouts[target].index).each( function() {
					$(this).attr('selected', false).removeAttr(FX.disabled);
				});
				$('option', self.DOM.breakouts[target].collection).each( function() {
					$(this).attr('selected', false).addClass(FX.hidden);
				});
				$('.orb-size-row').addClass(FX.fade_out);
				setTimeout( function() {
					$('.orb-size-row').addClass(FX.hidden);
					$("input", self.DOM.breakouts.orb.sizes.content).each( function() {
						$(this).prop('checked', true);
					});
				}, 330);
				$(self.DOM.breakouts[target].collection).removeClass(FX.error);
			};

			b.add = function(target) {
				$("option:selected", self.DOM.breakouts[target].index).each( function() {
					var orb_id = $(this).val();
					$(this).attr(FX.disabled, true);
					$( as_id([target, "selector", "collection", orb_id].join("-"))).removeClass(FX.hidden);
					$(as_id(['orb','selector', 'sizes', orb_id].join("-"))).removeClass(FX.hidden);
					setTimeout(function() { $(as_id(['orb','selector', 'sizes', orb_id].join("-"))).removeClass(FX.fade_out);}, 30);
				});

				if (target == "orblist") self.orblist.inspect_active()
			};

			b.remove = function(target) {
				$("option:selected", self.DOM.breakouts[target].collection).each( function() {
					var orb_id = $(this).val();
					$(this).addClass(FX.hidden);
					$( as_id([target, "selector", "index", orb_id].join("-"))).removeAttr(FX.disabled);
					$(as_id(['orb','selector', 'sizes', orb_id].join("-"))).addClass(FX.fade_out);
					setTimeout(function() {
						$(as_id(['orb','selector', 'sizes', orb_id].join("-"))).addClass(FX.hidden);
						$("input.orb-size", as_id(['orb','selector', 'sizes', orb_id].join("-"))).each( function() {
							$(this).prop('checked', true);
						});
					}, 300);
				});



				if (target == "orblist") self.orblist.inspect_active()
			}
		},
		show: undefined,
		hide: undefined,
		launch: undefined,
		cancel: undefined,
		save: undefined,
		save_multiselect: undefined,
		save_input: undefined,
		reset: undefined,
		reset_multiselect: undefined,
		reset_input: undefined,
		add: undefined,
		remove: undefined
	},


	init: function() {
		this.init_dom();
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		this.orblist.populate();
		this.current.features.check_exclusions();
	},
	init_dom: function() {
 		this.DOM.features.box =  $('#specials-features', C.BODY)[0];
 		this.DOM.features.table =  $('#specials-features-table > tbody', C.BODY)[0];
 		this.DOM.conditions.box =  $('#specials-conditions', C.BODY)[0];
 		this.DOM.conditions.table =  $('#specials-conditions-table > tbody', C.BODY)[0];
		for (var f in this.current.fields) {
			this.DOM.fields[f] = $(as_id("Special"+title_case(f)))[0];
		}
		this.DOM.fields["inactive"] = $(as_id('SpecialsInactive'))[0];
		this.DOM.fields["active"] = $(as_id('SpecialsActive'))[0];
		this.DOM.buttons.save = $(as_id("specials-save-button"))[0];
		this.DOM.buttons.cancel = $(as_id("specials-cancel-button"))[0];

		for ( var section in this.sections ) {
			var prefix = this.sections[section] ? "add-special-conditions" : "add-special";
			var container = this.sections[section] ? "conditions" : "features";
			this.DOM[container][section].box =  $([prefix, 'select'].join("-"))[0];
			this.DOM[container][section].select.field =  $(as_id([prefix, section, "select"].join("-")))[0];
			this.DOM[container][section].select.wrapper =  $(as_id([prefix, section, "wrapper"].join("-")))[0];
			this.DOM[container][section].select.first_opt =  $("option", this.DOM[container][section].select.field)[0];
			this.DOM[container][section].choice.box =  $(as_id([prefix, section, "choice"].join("-")))[0];
			this.DOM[container][section].choice.content = $(".select-choice", $(this.DOM[container][section].choice.box)[0])[0];
		}
		var breakouts = {orb:true, orbcat:true, price:true, orblist:true};
		var buttons = {add:true, remove: true, save: true, cancel: true};
		for ( var s in breakouts ) {
			this.DOM.breakouts[s].box = $( as_id([s, "selector"].join("-")) )[0];
			if ( s != "price") {
				this.DOM.breakouts[s].index = $( as_id([s, "selector", "index", "select"].join("-")) )[0];
				this.DOM.breakouts[s].collection = $( as_id([s, "selector", "collection", "select"].join("-")) )[0];
				if (s == "orblist") {
					this.DOM.breakouts[s].input = $( as_id([s, "selector", "input"].join("-")) )[0];
					this.DOM.breakouts[s].meta = $( as_id([s, "selector", "meta", 'select'].join("-")) )[0];
					this.DOM.breakouts[s].active = $( as_id([s, "selector", "active"].join("-")) )[0];
					this.DOM.breakouts[s].buttons.create = $(as_class(['create','button'].join("-")), this.DOM.breakouts[s].box)[0]
					this.DOM.breakouts[s].buttons.delete = $(as_class(['delete','button'].join("-")), this.DOM.breakouts[s].box)[0]
					this.DOM.breakouts[s].buttons.update = $(as_class(['update','button'].join("-")), this.DOM.breakouts[s].box)[0]
				}
			} else {
				this.DOM.breakouts[s].input = $( as_id([s, "selector", "input"].join("-")) )[0];
			}
			for ( var b in buttons ) this.DOM.breakouts[s].buttons[b] = $(as_class([b,'button'].join("-")), this.DOM.breakouts[s].box)[0];
		}
		this.DOM.breakouts.orb.sizes.wrapper = $(as_id("orb-sizes-wrapper"))[0];
		this.DOM.breakouts.orb.sizes.content = $(as_id("orb-sizes-wrapper-content"))[0];

	},

	table_row: function(data) {
		var route = ['specials_'+data.section, 'delete', false, data.id].join(C.DS);
		return $("<tr />").attr('id', data.id).append( [
					$("<td />").html(data.sentence),
					$("<td />").append(
						$("<a />")
							.attr('href', '#')
							.addClass('modal-button tny ')
							.attr('data-route', route)
							.append( $('<span />').html("Delete") )
					)
		            ])
	}
};


var xt_vendor_ui = {
	orbopt_pricelist_id:-1,
	specials_target: undefined,

	init: function () {
		XT.vendor_ui.loading_screen(0);
		XT.vendor_ui.fix_breakouts();
		$(XSM.vendor_ui.ui_tabs).tabs();
		for ( var table in {menu:null, opts:null, specials:null } ) {
			XT.vendor_ui.data_tables(table);
		}
	},
	loading_screen: function(last_height) {
		var ui_height = $(XSM.vendor_ui.ui_tabs).innerHeight();
		if (last_height == ui_height) {
			$("#loading-screen").addClass(FX.fade_out);
			setTimeout(function(){ $("#loading-screen").addClass(FX.hidden); }, 300);
		} else {
			setTimeout( function() { XT.vendor_ui.loading_screen(ui_height)}, 300);
		}
	},
	fix_breakouts: function() {
		$(FX.breakout).each(function () {
			$(this).css({
				left: (0.5 * $(window).width() - 400) + "px", // all breakouts are 800px wide, vendor.scss ~L514
				top: "300px"
			})
		});
	},
	data_tables: function (table) {
		var tables = {
			menu: {
				id: XSM.vendor_ui.menu_table,
				cols: [
					{ width: 200},
					{ width: 400},
					{ width: 100},
					{ width: 350},
					{ width: 50}
				]
			},
			opts: {
				id: XSM.vendor_ui.orbopts_table,
				cols: [
					{ width: 100 },
					{ width: 100 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 }
				]
			},
			specials: {
				id: XSM.vendor_ui.specials_table,
				cols: [
					{ width: 100 },
					{ width: 100 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 }
				]
			}
		};
		$(tables[table].id).dataTable({
				bDestroy: true,
				bJQueryUI: true,
				bDeferRender: false,
				autoWidth: false,
				columns: tables[table].cols
		});
	},

	save_orb: function (orb_id, attribute, replacement) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		var saved_value = null;
		switch (attribute) {
			case 'title':
				saved_value = $("input[name='Orb[title]']", cell_id).val();
				if (!saved_value) saved_value = "Click to Add";
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'orbcat':
				var orbcat_id = $("select", cell_id).val();
				$($("select", cell_id).find("option")).each(function () {
					if (Number($(this).attr('value')) == orbcat_id) {
						saved_value = $(this).text();
						$(this).attr('selected', 'selected');
					} else {
						$(this).removeAttr('selected');
					}
				});
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'description':
				saved_value = $("textarea", cell_id).val();
				if (!saved_value) saved_value = "Click to Add";
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'prices':
				$(cell_id).html(replacement);
				break;
		}
		this.fix_breakouts();
		XT.vendor_ui.cancel_cell_editing('orb', orb_id, attribute);
	},
	set_orbopt_pricelist_focus: function() {
		$("#orbopt-pricelist-add input[name='Pricelist[id]']", C.BODY).val($("#orbopt-pricelist-select").val());
		XT.vendor_ui.toggle_pricelist_add("stow");
		$( "#orbopt-pricelist-buttons .modal-button.disabled", C.BODY).removeClass(FX.disabled).addClass(FX.enabled);
	},
	edit_orbopt_pricelist: function() {
		var url = ["edit-orbopt-pricing", $("#orbopt-pricelist-select").val()].join(C.DS);
		$("#orbopt-pricelist-add-container").load([XT.host,url].join(C.DS), function() { XT.vendor_ui.toggle_pricelist_add("reveal", true); });
	},
	toggle_pricelist_add: function(state, preserve_fields) {
		if (!preserve_fields || state != "reveal") {
			$("input", "#orbopt-pricelist-add-container").each(function() { $(this).val("");});
		}
		if (state == "reveal") {
			$( ".modal-button.enabled", "#orbopt-pricelist-buttons").removeClass(FX.enabled).addClass(FX.disabled);
			$("#orbopt-pricelist-add-container").removeClass(FX.hidden);
			setTimeout(function() { $("#orbopt-pricelist-add-container").removeClass(FX.fade_out); }, 10);
		}
		if (state == "stow") {
			$("#orbopt-pricelist-add-container").addClass(FX.fade_out);
			setTimeout(function() { $("#orbopt-pricelist-add-container").addClass(FX.hidden); }, 300);
		}
	},
	delete_orbopt_pricelist: function(action) {
		var pricelist_id = $("#orbopt-pricelist-select").val();
		switch (action) {
			case "confirm":
				$(XT.router).trigger(C.ROUTE_REQUEST, {request:["orbopt_pricelist", "delete","delete", pricelist_id].join(C.DS), trigger:{}});
				break;
			case "warn":
				$("#delete-orbopt-pricelist-confirmation").removeClass(FX.hidden);
				setTimeout(function() { $("#delete-orbopt-pricelist-confirmation").removeClass(FX.fade_out); }, 10);
				break;
			case "cancel":
				$("#delete-orbopt-pricelist-confirmation").addClass(FX.fade_out);
				setTimeout(function() { $("#delete-orbopt-pricelist-confirmation").addClass(FX.hidden); }, 300);
				break;
			case "print_opts":
				XT.vendor_ui.delete_orbopt_pricelist("cancel"); // close the cancellation warning
				XT.vendor_ui.reload_orbopt_pricelist_config();
				break;
		};
	},
	reload_orbopt_pricelist_config: function() {
		var opt_id = $("#orbopt-pricelist-select-form", C.BODY).data('opt');
		$(XT.router).trigger(C.ROUTE_REQUEST, {request:["orbopt_pricelist","launch","false",opt_id].join(C.DS), trigger:{}});
	},
	edit_cell: function (table, id, attribute) {
		var display_element = XSM.vendor_ui.orb_attr_display;
		var edit_element = XSM.vendor_ui.orb_attr_edit;
		var cell_id = as_id([table, id, attribute].join("-"));
		if (table == "orbopt") {
			display_element = XSM.vendor_ui.orbopt_attr_display;
			edit_element = XSM.vendor_ui.orbopt_attr_edit;
		}
		if (table == "specials") {
			display_element = XSM.vendor_ui.specials_attr_display;
			edit_element = XSM.vendor_ui.specials_attr_edit;
		}

		$(display_element, cell_id).addClass(FX.fade_out);
		setTimeout( function() {
			$(display_element, cell_id).addClass(FX.hidden);
			$(edit_element, cell_id).removeClass(FX.hidden);
			setTimeout(function () { $(edit_element, cell_id).removeClass(FX.fade_out); }, 30);
		}, 300);
	},
	cancel_cell_editing: function (table, id, attribute) {
		var display_element = XSM.vendor_ui.orb_attr_display;
		var edit_element = XSM.vendor_ui.orb_attr_edit;
		var cell_id = as_id([table, id, attribute].join("-"));
		if (table == "orbopt") {
			display_element = XSM.vendor_ui.orbopt_attr_display;
			edit_element = XSM.vendor_ui.orbopt_attr_edit;
		}
		if (table == "specials") {
			display_element = XSM.vendor_ui.specials_attr_display;
			edit_element = XSM.vendor_ui.specials_attr_edit;
		}
		$(edit_element, cell_id).addClass(FX.fade_out);
		setTimeout(function() { $(display_element, cell_id).removeClass(FX.fade_out);}, 30)
		setTimeout(function() {
			$(edit_element, cell_id).addClass(FX.hidden);
			$(display_element, cell_id).removeClass(FX.hidden);
			new Modal(C.PRIMARY).hide();
		}, 330);
	},
	save_orbopt: function(response, data) {
		var cell_id;
		if (data.attribute == "pricing") {
			cell_id = XSM.vendor_ui.orbopt_pricelist_add;
			$(cell_id, C.BODY).addClass([FX.hidden, FX.fade_out].join(" "));
		} else {
			cell_id = as_id(["orbopt", data.id, data.attribute].join("-"));
		}
		switch (data.attribute) {
			case "title":
				$(XSM.vendor_ui.orbopt_attr_display, cell_id).html(response.submitted_data.data.Orbopt.title);
				break;
			case "vendor-title":
				$(XSM.vendor_ui.orbopt_attr_display, cell_id).html(response.submitted_data.data.Orbopt.vendor_title);
				break;
		}
		if ("replacement" in data) $(XSM.vendor_ui.orbopt_attr_display, cell_id).html(data.replacement);
		XT.vendor_ui.cancel_cell_editing("orbopt", data.id, data.attribute);
	},
	toggle_orbopt_group: function (orbcat_id) {
		orbcat_id = Number(orbcat_id);
		$("li.orbopt", XSM.modal.primary).each(function () {
			var data = $(this).data();
			if (in_array(orbcat_id, data.groups)) {
				$(XT.router).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt , "set_opt_state", "active"].join(C.DS), trigger: {}});
			} else {
				$(XT.router).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt, "set_opt_state", "inactive"].join(C.DS), trigger: {}});
			}
		});
	},


	set_orbopt_state: function (opt_id, state, label, input) {
		var opt_label_wrapper = label ? label : "#orbopt-" + opt_id + "-label";
		var opt_label = opt_label_wrapper + " span";
		var opt_input = input ? input : "li[data-orbopt='" + opt_id + "'] input";
		if (state == FX.active) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active_plus).removeClass(FX.inactive).addClass(FX.active);
			$(opt_input, XSM.modal.primary).val(1);
		}
		if (state == FX.inactive) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active).removeClass(FX.active_plus).addClass(FX.inactive);
			$(opt_input, XSM.modal.primary).val(0);
		}
		if (state == FX.active_plus) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active).removeClass(FX.inactive).addClass(FX.active_plus);
			$(opt_input, XSM.modal.primary).val(2);
		}
	},


	toggle_filter: function (filter_id) {
		var filter = "#orbopt-flag-" + filter_id;
		if ($(filter, XSM.modal.primary).hasClass(FX.active)) {
			$(filter).removeClass(FX.active);
		} else {
			$(filter).addClass(FX.active);
		}
		XT.vendor_ui.filter_opts()
	},

	toggle_optflag: function (response, data) {
		var orbopt_id = data.orbopt;
		var optflag_id = data.optflag;
		var cell_id = as_id(["orbopt", orbopt_id, "optflag", optflag_id].join("-"));
		if ($("span", cell_id).hasClass(FX.active)) {
			$("span", cell_id).removeClass(FX.active).addClass(FX.inactive);
		} else {
			$("span", cell_id).removeClass(FX.inactive).addClass(FX.active);
		}
	},

	toggle_orbopt: function (opt_id) {
		var opt_label = "#orbopt-" + opt_id + "-label";
		var opt_input = "li[data-orbopt='" + opt_id + "'] input";
		var state = null;
		if ( $(opt_label, XSM.modal.primary).hasClass(FX.active) ) {
			state = FX.active_plus;
		} else if ($(opt_label, XSM.modal.primary).hasClass(FX.active_plus) ) {
			state =  FX.inactive;
		} else {
			state = FX.active;
		}
		XT.vendor_ui.set_orbopt_state(opt_id, state, opt_label, opt_input)
	},


	filter_opts: function () {
		var active_flags = new Array();
		$("dd.orbopt-flag.active").each(function () { active_flags.push(Number($(this).data('id'))); });
		$("li.orbopt", XSM.modal.primary).each(function () {
			var flags = $(this).data('flags');
			var active = false;
			for (var i in flags) {
				if (active_flags.indexOf(flags[i]) > -1) active = true;
			}
			if (active) {
				$(this).removeClass(FX.hidden);
			} else {
				$(this).addClass(FX.hidden)
			}
			$(document).foundation();
		});
	},

	reload_tab: function( tab ) {
		switch (tab) {
			case "opts":
				$(XSM.vendor_ui.menu_options_tab).html('');
				$(XSM.vendor_ui.menu_options_tab).load(["vendor-ui", "opts"].join(C.DS), function() {
																		XT.vendor_ui.data_tables(tab);
																		XT.vendor_ui.fix_breakouts();
																	});
			break;
			case "menu":
				$(XSM.vendor_ui.menu_tab).load(["vendor-ui", "menu"].join(C.DS), function() {
																XT.vendor_ui.data_tables(tab);
																XT.vendor_ui.fix_breakouts();
															});
			break;
			case "specials":
				$(XSM.vendor_ui.specials_tab).load(["vendor-ui", "specials"].join(C.DS), function() {
																				XT.vendor_ui.data_tables(tab);
																				XT.vendor_ui.fix_breakouts();
																			});
		}
	},

	toggle_menu_options_breakout: function( id ) {
		if ( $(id, XSM.vendor_ui.menu_options_tab).hasClass(FX.hidden) ) {
			$(id, XSM.vendor_ui.menu_options_tab).removeClass(FX.hidden);
		} else {
			$(id, XSM.vendor_ui.menu_options_tab).addClass(FX.hidden);
		}
	},
	overflagged: function(opt_id, optflag_id) {
		var true_count = 0;
		var flag_map = {0: null, 1: null, 2: null, 3: null, 4: null, 5: null, 6: null, 7: null};
		for (var  flag_id in flag_map) {
			var cell_id = as_id(["orbopt", opt_id, "optflag", flag_id].join("-"));
			var active = $("span", cell_id).hasClass(FX.active);
			if (active === true) true_count++;
			flag_map[flag_id] = active;
		}
		if (true_count == 0 || flag_map[optflag_id] == true /*ie. deselecting*/) return false;
		flag_map[optflag_id] = true; // test turning it on
		true_count++;
		// premium & meat or premium & veg
		if ( true_count == 2 ) {
			if (flag_map[6]) return !(flag_map[1] || flag_map[2])
		}
		return true;
	},

	overflagging_alert: function(action) {
		var method, time, class_1, class_2;
		if (action == C.SHOW) {
			method = "removeClass";
			time = 300;
			class_1 = FX.hidden;
			class_2 = FX.fade_out;
		} else {
			method = "addClass";
			time = 10;
			class_1 = FX.fade_out;
			class_2 = FX.hidden;
		}
		$("#overflagging-alert")[method](class_1);
		setTimeout( function() { $("#overflagging-alert")[method](class_2); }, time);
	},

	confirm_delete_special: function(id) {
		$(as_id(["specials", "delete", id].join("-"))).removeClass(FX.hidden);
		setTimeout(function() { $(as_id(["specials", "delete", id].join("-"))).removeClass(FX.fade_out); }, 30);
	},
	cancel_delete_special: function(id) {
		$(as_id(["specials", "delete", id].join("-"))).addClass(FX.fade_out);
		setTimeout(function() { $(as_id(["specials", "delete", id].join("-"))).addClass(FX.hidden); }, 330);
	},
	delete_special: function(id) {
		$.ajax({
			type: C.POST,
			url: ["delete-special", id].join(C.DS),
			data: null,
			success: function (data) {
				try {
					data = JSON.parse(data);
				} catch (e) {
					$(C.BODY).append($("<div />").html(data));
					die();
				}
				if (data.success == true) {
					XT.vendor_ui.reload_tab('specials');
				}
			}
		});
	},
	specials: undefined
}
