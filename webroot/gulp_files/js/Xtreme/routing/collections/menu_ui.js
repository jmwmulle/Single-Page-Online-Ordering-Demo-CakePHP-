/**
 * Created by jono on 8/11/15.
 */

XT.route_collections.menu_ui = function() {
	this.favorite = {
				params: {context: {value: null, url_fragment: false}},
				url: {url: "favorite"},
				data: false,
				callbacks: {
					modal: C.FLASH,
					params: function () { this.data = $(XSM.menu.orb_order_form).serialize(); },
					launch: function (e, resp) { pr(resp);}
				}
			};
	this.menu = {
				params: ['reveal_method'],
				modal: C.PRIMARY,
				url: {url: "menu"},
				callbacks: {
					params_set: function () {
						if (this.read('reveal_method') == 'unstash') {
							this.unset('url');
							this.unset('launch_callback');
							this.modal.hide();
							setTimeout(function () { XT.menu.unstash() }, 300);
						}
					},
					launch: function () { window.xtr.splash.fold();}
				}
			};
	this.menuitem = {
		url: {url: "menuitem"},
		params: {orb_id: {value: null, url_fragment: true}}
	};
	this.orbcat =  {
		url: {url: "menu", defer: true},
		params: {
			id: {value: null, url_fragment: true},
			name: {}
		},
		callbacks: {
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response) { XT.orbcard.load_menu(response.data) }
				}, false, false)
			}
		}
	};
	this.price_rank = {
		params: ["rank"],
		propagates: false,
		callbacks: {
			params_set: function () { XT.orbcard.orb.price_rank.set( this.read('rank') ) }
		}
	};
	this.toggle_opt = {
		params: ["id"],
		propagates: false,
		callbacks: {
			params_set: function () { XT.orbcard.orb.toggle_opt( this.read('id'), false ) }
		}
	};
	this.coverage_toggle = {
		propagates:false,
		params: ["id", "weight"],
		propagates: false,
		callbacks: {
			params_set: function () { XT.orbcard.orb.toggle_opt( this.read('id'), this.read("weight") ) }
		}
	};
	this.optflag = {
		params: ['target', 'action'],
		callbacks: {
			launch: function () { XT.orbcard.toggle_filter(this.read('target')); }
		}
	};
	this.load_orb = {
		url: { url: "menu-item", type: C.GET, defer: true },
		params:{ id:{ url_fragment:true }, fart:{url_fragment:true, value:"poop"} },
		propagates: false,
		callbacks: {
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response, data) {
						setTimeout( function() {
							XT.orbcard.load_orb(data.id, response.data.view_data)
						}, XT.orbcard.reset_stage() );
					},
					data: { id: this.read('id') }
				}, false, false);
			}
		}
	};
	this.configure_orb = {
		params: ['id', 'rank'],
		propagates: false,
		callbacks: {
			params_set: function() {
				// populate form with any orb configurations in progress
				 XT.orbcard.configure(this.read('id'), this.read('rank'));
			},
			launch: function() { XT.orbcard.show_face(C.BACK) }
		}
	};
	this.orb_card = {
		params: ["action", "action_arg", "data"],
		stop_propagation: true,
		callbacks: {
			params_set: function () {
				var launch = false;
				switch (this.read('action')) {
					case 'add_to_cart':
						switch (this.read('action_arg')) {
							case "confirm":
								this.url = {
									url: "add-to-cart",
									type: C.POST,
									defer: true,
									data: $(XSM.menu.orb_order_form).serialize()
								};
								launch = function () {
									try {
										var data = JSON.parse(this.deferral_data);
										if (data.success == true && window.xtr.cart.add_to_cart()) {
											XT.layout.reveal_orb_card_modal();
										}
									} catch (e) {
										throw "Add to cart failed at server:\n " + this.deferral_data;
									}
								}
								break;
							case 'cancel':
								launch = XT.menu.reset_orb_card_stage();
								break;
						}
						break;
					default:
						launch = function () { XT.menu.toggle_orb_card_row_menu(this.read('action'), null);}
						break;
				}
				if (launch) this.set_callback("launch", launch)
			}
		}
	};
	this.validate_form = {
				params: ['target', 'restore', 'delegate'],
				callbacks: {
					params_set: function () {
						pr(this.params, "vfrom");
						XT.validate(this.read('target'), this.read('restore'), this.read('delegate')) }
				}
			};
	this.close_modal = {
			params: ["modal"],
			callbacks: { launch: function () { new Modal(this.read('modal')).hide(true) } }
		};
	this.orbcard_modal = {
				params: ["action", "target"],
				propagates:false,
				modal: C.PRIMARY,
				callbacks: {
					params_set: function() {
						XT.orbcard.show_face(C.FRONT);
						switch ( this.read('action') ) {
							case "continue_ordering":
								setTimeout(function () { XT.orbcard.modal.hide(); XT.orbcard.reset_stage(); }, 900);
								break;
							case "view":
								XT.orbcard.modal.hide();
								this.url= {
									url: ["review", this.read('target')].join("-"),
									defer: false,
									type: C.GET
								};
								this.stash = true;
								break;
						}
					}
				}
		};
	return this;
};