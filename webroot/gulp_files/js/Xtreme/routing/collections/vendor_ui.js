/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.vendor_ui = function() {
	this.vendor_ui = {
		params: {target: {url_fragment: true}},
		url: {url: "vendor-ui", defer: true, type: C.POST},
		modal: C.PRIMARY,
		callbacks: {
			params_set: function () {
				pr(this.read('target'), "vendor_ui");
			},
			launch: function () {
				if (this.read('target') == "opts") {
					$("#menu-options-tab").replaceWith(this.deferral_data);
				}
				if (this.read('target') == "menu") {
					$("#menu-tab").replaceWith(this.deferral_data);
				}
				$(document).foundation();
//				XT.vendor_ui.init();
			}
		}
	};
	this.update_menu = {
		params: { target: {url_fragment: true}, attribute: {url_fragment: true} },
		url: { url: "orbs" + C.DS + "update_menu", type: C.POST, data: null },
		callbacks: {
			params_set: function () {
				var data = null;
				if (this.read('attribute') == "orbopts") {
					data = $(XSM.vendor_ui.orbopt_config_form_wrapper).serialize();
				} else {
					var cell_id = as_id(["orb", this.read('target'), this.read('attribute')].join("-"));
					data = $("form", cell_id).serialize();
				}
				this.url = {
					url: ["orbs", "update_menu", this.read('target'), this.read('attribute')].join(C.DS),
					type: C.POST,
					defer: true,
					data: data
				};
				switch (this.read('target')) {
					case 'pricedicts':
						if (this.read('attribute') == 'fetch') {
							this.url = {
								url: "add-price-labels",
								type: C.GET,
								defer: false
							};
							this.modal = new Modal(C.PRIMARY);
							this.unset('launch');
						}
						if (this.read('attribute') == 'save') {
							this.url = {
								url: "add-price-labels",
								type: C.POST,
								defer: true,
								data: $(XSM.vendor_ui.price_dict_update_form).serialize()
							};

							this.unset('modal');
						}
						break;
				}

			},
			launch: function () {
				var self = this;
				XT.router.cake_ajax_response(this.deferral_data, {
						callback: function (response, data) {
							if (data.target == 'pricedicts') $("#menu-tab").load([XT.host, "vendor-ui", "menu"].join(C.DS), function() {
									XT.vendor_ui.fix_breakouts()
							});
							if (self.modal) self.modal.hide();
							XT.vendor_ui.save_orb(data.target, data.attribute, 'replace' in response ? response.replace : null);
						},
						data: { target: this.read('target'), attribute: this.read('attribute') }
					},
					true
				);
			}
		}
	};
	// ORBS
	this.orb_edit = {
				params: { id: {value: null}, action: {}, action_arg: {} },
				modal: C.PRIMARY,
				callbacks: {
					params_set: function () {
						switch (this.read('action')) {
							case "delete":
								var confirmation_box = as_id(['delete', 'orb', this.read('id')].join("-"));
								switch (this.read('action_arg')) {
									case "confirm":
										$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
										setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
										this.unset('launch');
										break;
									case "cancel":
										$(confirmation_box).addClass(XSM.effects.fade_out);
										setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
										this.unset('launch');
										break;
									case "delete":
										this.url = {
											url: ["delete-menu-item", this.read('id')].join(C.DS),
											type: C.POST,
											defer: true
										};
										this.set_callback("launch", function () {
											XT.router.cake_ajax_response(this.deferral_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_tab).load([XT.host, "vendor-ui", "menu"].join(C.DS), function () {
														XT.vendor_ui.data_tables('menu');
														XT.vendor_ui.fix_breakouts();
													});
												}
											}, true, true);
										});
								}
							case "add":
								switch (this.read('action_arg')) {
									case "create":
										this.url = {
											url: "add-menu-item",
											type: C.GET,
											defer: false
										}
										this.unset('launch');
										break;
									case "save":
										this.modal.hide();
										this.url = {
											url: "add-menu-item",
											type: C.POST,
											defer: true,
											data: $(XSM.vendor_ui.orb_add_form, XSM.modal.primary).serialize()
										}
										this.set_callback("launch", function () {
											XT.router.cake_ajax_response(this.deferral_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_tab).load([XT.host, "vendor-ui", "menu"].join(C.DS),
														function () {
															XT.vendor_ui.data_tables('menu');
															XT.vendor_ui.fix_breakouts();
														});
												}
											}, true, true);
										});
										break;
								}
								break;

						}
					},
					launch: function () { XT.router.cake_ajax_response(this.deferral_data, {}, true, true);}
				}
			};
	this.orb_config = {
		params: ["target", "action", "attribute"],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case 'edit':
						XT.vendor_ui.edit_cell('orb', this.read('target'), this.read('attribute'));
						break;
					case 'cancel':
						XT.vendor_ui.cancel_cell_editing('orb', this.read('target'), this.read('attribute'));
						break;
				}
			}
		}
	};

	// ORBOPTS
	this.orbopt_config = {
		url: {url: "orbopt-config", method: C.GET},
		modal: C.PRIMARY,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				if (this.read('action') != "launch") {
					this.unset('url');
					this.unset('modal');
				}
				switch (this.read('action')) {
					case 'add':
						this.url = {
							url: "add-menu-option",
							type: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.add_orbopt_form).serialize()
						};
						this.set_callback("launch", function () {
							XT.router.cake_ajax_response(this.deferral_data, {
								callback: function () { XT.vendor_ui.reload_tab('opts'); }
							}, true);
						});
					case 'delete':
						var confirmation_box = as_id(['delete', 'orbopt', this.read('id')].join("-"));
						switch (this.read('action_arg')) {
							case "confirm":
								$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
								setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
								this.unset('launch');
								break;
							case "cancel":
								$(confirmation_box).addClass(XSM.effects.fade_out);
								setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
								this.unset('launch');
								break;
							case "delete":
								this.url = {
									url: ["delete-menu-option", this.read('id')].join(C.DS),
									type: C.POST,
									defer: true
								};
								this.set_callback("launch", function () {
									XT.router.cake_ajax_response(this.deferral_data, {
										callback: function () {
											$(XSM.vendor_ui.menu_options_tab).load([XT.host, "vendor-ui", "menu"].join(C.DS),
												function () {
													XT.vendor_ui.data_tables('opts');
													XT.vendor_ui.fix_breakouts();
												});
										}
									}, true);
								});

						}
						break;
					case 'toggle':
						this.set_callback('launch', function () {
							XT.vendor_ui.toggle_orbopt(this.read('id'));
						});
						break;
					case 'set_opt_state':
						this.set_callback('launch', function () {
							pr([this.read('id'), this.read('action_arg')]);
							XT.vendor_ui.set_orbopt_state(this.read('id'), this.read('action_arg'));
						});
						break;
					case 'toggle_group':
						this.set_callback('launch', function () {
							XT.vendor_ui.toggle_orbopt_group($(this.trigger.event.target).val());
						});
						break;
					case 'filter':
						this.set_callback('launch', function () {
							XT.vendor_ui.toggle_filter(this.read('id'));
						});
					default:
						break;
				}
			},
			launch: function () {
				var prim_con_h = $(XSM.modal.primary_content).innerHeight();
				var prim_mod_max = $(XSM.modal.primary).innerHeight() - 48;
				if (prim_con_h > prim_mod_max) {
					$(XSM.modal.primary_content).css({
						height: prim_mod_max,
						overflowY: "auto"
					});
				}
				var labels = $(".content .orbopt span");
				var i,j,temparray,chunk = 6;
				for (i=0,j=labels.length; i<j; i+=chunk) {
				    temparray = labels.slice(i,i+chunk);
				    var max_h = 0;
					for (var l =0; l < 6; l++) {
						if ( $(temparray[l]).innerHeight() > max_h) max_h = $(temparray[l]).innerHeight();
					}
					for (var l =0; l < 6; l++) $(temparray[l]).css( { height:max_h} );
				}

				$(document).foundation();
			}
		}
	},
	// "edit" is to orbopts as "config" is to orbs in terms of routing names / functionality in vendor_ui
	this.orbopt_edit = {
		params: ['id', 'action', 'attribute'],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case 'breakout':
						this.unset('launch');
						this.unset('url');
						var target = null;
						if (this.read('attribute') == 'add_opt') {
							target = XSM.vendor_ui.orbopt_add_breakout;
						} else {
							target = XSM.vendor_ui.orbopt_pricelist_add_breakout;
						}
						XT.vendor_ui.toggle_menu_options_breakout(target);
					case 'edit':
						pr(this.trigger);
						XT.vendor_ui.edit_cell('orbopt', this.read('id'), this.read('attribute'));
						break;
					case 'cancel':
						pr(this.trigger);

						XT.vendor_ui.cancel_cell_editing('orbopt', this.read('id'), this.read('attribute'));
						break;
					case 'save':
						this.url = {
							url: ["update-orbopt", this.read('id'), this.read('attribute')].join(C.DS),
							type: C.POST,
							defer: true,
							data: $("form", as_id(["orbopt", this.read('id'), this.read('attribute')].join("-"))).serialize()
						};
						this.set_callback("launch", function () {
							XT.router.cake_ajax_response(this.deferral_data, {
									callback: XT.vendor_ui.save_orbopt,
									data: { id: this.read('id'), attribute: this.read('attribute') }
								},
								true, true);
						});
						break;
				}
			}
		}
	};
	this.orbopt_pricelist = {
		params: ['action', 'action-arg', 'id'], // by context can be either of pricelist.id or orbopt.id, usually former
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "set":
						XT.vendor_ui.set_orbopt_pricelist_focus();
						break;
					case 'launch':
						this.modal = new Modal(C.PRIMARY);
						this.url = {
							url:["launch-orbopt-pricelist-config", this.read('id')].join(C.DS),
							type: C.GET,
							defer:false
						};
						this.set_callback('launch', XT.vendor_ui.fix_breakouts);
						break;
					case 'add':
						XT.vendor_ui.toggle_pricelist_add("reveal");
						break;
					case "edit":
						if (this.read('action-arg') == "save") {
							var orbopt_id = $("#orbopt-pricelist-select-form").data('opt');

							this.url = {
								url: ["save-orbopt-pricing-edit", this.read('id'), orbopt_id].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("#orbopt-pricelist-add-edit-form").serialize()
							};
						} else {
							XT.vendor_ui.edit_orbopt_pricelist();
						}
						break;
					case 'cancel-add':
						XT.vendor_ui.set_orbopt_pricelist_focus();
						break;
					case "cancel":
						$(XT.router).trigger(C.ROUTE_REQUEST, {request: "orbopt_edit/-1/cancel/pricing", trigger: {}});
						break;
					case "save":
						if (this.read('action-arg') == "pricelist") {
							var orbopt_id = $("#orbopt-pricelist-select-form", C.BODY).data('opt');
							this.url = {
								url: ["add-orbopt-pricelist", orbopt_id].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("form", XSM.vendor_ui.orbopt_pricelist_add).serialize()
							};
						}
						if (this.read('action-arg') == "opt") {
							this.url = {
								url: ["update-orbopt", this.read('id'), 'pricing'].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("#orbopt-pricelist-select-form", C.BODY).serialize()
							};
						}
						break;
					case "delete":
						//TODO: you wrote the *entire* delete method when you were a) fucking ballin' at code but b) fiending to leave; it's not tested AT ALL yet
						// delete.delete is reached on the second pass, after warning & confirmation
						if ( this.read('action-arg') == "delete" ) {
							this.url = {
								url: ["orbopt-pricing-delete", this.read('id')].join(C.DS),
								type: C.POST,
								defer:true
							}
						} else {
							this.unset("launch");
						}
						XT.vendor_ui.delete_orbopt_pricelist(this.read('action-arg'));
						break;

				}
			},
			launch: function() {
				if (this.deferral_data != undefined) {
					XT.router.cake_ajax_response(this.deferral_data, {}, true, true);
				}
			}
		}
	};
	this.orbopt_optgroup_config = {
		url: {url: "orbopt-optgroup-config", type: C.GET},
		modal: C.PRIMARY,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "toggle":
						this.unset('url');
						this.unset('modal');
						this.unset('launch');
						var label_id = as_id(["optgroup", this.read('id'), 'label'].join("-"));
						var field_sel = "input[name='OrboptOrbcat[" + this.read('id') + "]'";
						$(label_id).toggleClass(XSM.effects.active)
						$(field_sel).val( $(field_sel).val() == 0 ? 1 : 0 );
						break;
					case "save":
						this.url = {
							url: this.url.url,
							method: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.orbopt_optgroup_config_form).serialize()
						};
						break;
				}
			},
			launch: function () {
				if (this.deferral_data != undefined) {
					var self = this;
					XT.router.cake_ajax_response(self.deferral_data, {
						callback: function() { self.modal.hide()}
					}, true, true);
				}
				var labels = $(".content .orbopt-optgroup span");
				var i,j,temparray,chunk = 6;
				for (i=0,j=labels.length; i<j; i+=chunk) {
				    temparray = labels.slice(i,i+chunk);
				    var max_h = 0;
					for (var l =0; l < 6; l++) {
						if ( $(temparray[l]).innerHeight() > max_h) max_h = $(temparray[l]).innerHeight();
					}
					for (var l =0; l < 6; l++) $(temparray[l]).css( { height:max_h} );
				}
				$(document).foundation();
			}
		}
	};
	this.orbflag_config = {
		params: {orbopt: {url_fragment: true}, optflag: {url_fragment: true}},
		url: {url: "optflag-config", type: C.POST, defer: true},
		callbacks: {
			params_set: function () {
				if (XT.vendor_ui.overflagged(this.read('orbopt'), this.read('optflag')) ) {
					this.unset('url');
					this.unset('launch');
					XT.vendor_ui.overflagging_alert(C.SHOW);
				}
			},
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
						callback: XT.vendor_ui.toggle_optflag,
						data: { orbopt: this.read('orbopt'), optflag: this.read('optflag') }
					},
					true, true);
			}
		}
	};

	this.overflag_dismiss = {
		callbacks: {
			params_set: function() { XT.vendor_ui.overflagging_alert(C.HIDE); }
		}
	};

	this.specials_edit = {
		params: { id: {value: null}, action: {}, action_arg: {} },
		modal: C.PRIMARY,
		callbacks: {
			params_set: function() {
				switch ( this.read('action') ) {
					case "edit":
						XT.vendor_ui.edit_cell('specials', this.read('id'), this.read('action_arg'));
						break;
					case "cancel":
						XT.vendor_ui.cancel_cell_editing('specials', this.read('id'), this.read('action_arg'));
						break;
					case "delete":
						var confirmation_box = as_id(['delete', 'specials', this.read('id')].join("-"));
						switch (this.read('action_arg')) {
							case "confirm":
								$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
								setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
								this.unset('launch');
								break;
							case "cancel":
								$(confirmation_box).addClass(XSM.effects.fade_out);
								setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
								this.unset('launch');
								break;
							case "delete":
								this.url = {
									url: ["delete-menu-item", this.read('id')].join(C.DS),
									type: C.POST,
									defer: true
								};
								this.set_callback("launch", function () {
									XT.router.cake_ajax_response(this.deferral_data, {
										callback: function () {
											$(XSM.vendor_ui.menu_tab).load([XT.host, "vendor-ui", "menu"].join(C.DS), function () {
												XT.vendor_ui.data_tables('menu');
												XT.vendor_ui.fix_breakouts();
											});
										}
									}, true, true);
								});
						}

				}

			}
		}
	};

	this.specials_add = {
		params: ['action'],
		url: {url:'add-special', type: C.GET},
		modal: C.PRIMARY,
		callbacks: {
			params_set: function() {
				switch ( this.read("action") ) {
					case "add_orb":
						this.url = false;
						XT.vendor_ui.specials_add_orb();
						break;
					case 'save':
						this.url.type = C.POST;
						this.url.data = $("#SpecialAjaxAddForm").serialize();
						this.url.defer = true;
						break;
				}
			},
			launch: function() {
				if (this.read('action') == 'save') {
					XT.router.cake_ajax_response(this.deferral_data, {}, true, true);
				}
			}
		}
	};

	this.specials_add_orbcat_filter = {
		params: ['action'],
		callbacks: {
			params_set: function() {
				switch (this.read('action') ) {
					case "reveal":
						XT.vendor_ui.specials_orbcat_filter();
						break;
				}
			}
		}
	}

	this.specials_criteria = {
			params:['is_condition', 'target', 'action'],
			callbacks: {
				params_set: function() {
					if (this.read('is_condition') == "1" && this.read('action') == 'toggle') {
						XT.vendor_ui.toggle_specials_add_conditions();
						return;
					}
					switch (this.read('action') ) {
						case "choose":
							XT.vendor_ui.toggle_specials_options(this.read('target'), false, this.read('is_condition') == "1")
							break;
						case "restore":
							XT.vendor_ui.toggle_specials_options(this.read('target'), true, this.read('is_condition') == "1")
					}
				}
			}
		},
	this.specials_add_close_breakout = {
		params: ['is_condition', 'parent', 'target'],
		callbacks: {
			params_set: function() {
				if (this.read('target') == 'orb_selector') {
					$("#orb-selector").addClass(FX.fade_out);
					setTimeout( function() {
						$("#orb-selector").addClass(FX.hidden);
					}, 300);
				} else {
					XT.vendor_ui.set_specials_option_choice(this.read('parent'), this.read('target'), this.read('is_condition') == "1");
				}
			}
		}
	}

	return this
};

