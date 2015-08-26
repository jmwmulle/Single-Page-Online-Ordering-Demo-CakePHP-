/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.orders_api = function() {
	this.finalize_order =  {
		url: { url: "orders" + C.DS + "finalize", defer: true, data: $("#OrderReviewForm").serialize(), type: C.POST },
		callbacks: {
			launch: function () {
					var data = $.parseJSON(this.deferral_data);
					var trigger = this.trigger;
					if (!data.error) {
						if (data.order_id) {
							var route = "pending_order" + C.DS + data.order_id + C.DS + "launching";
							$(XT.router).trigger(C.ROUTE_REQUEST, { request: route, trigger: trigger.event });
						}
					}
				}
		}
	},
	this.review_order = {
		url: { url: "review-order", type: C.GET, defer: false},
		params: ["context"],
		modal: C.PRIMARY,
		callbacks: { params_set: function() { this.modal.hide() } }
	};
	this.order_accepted = {
		url: "order-accepted",
		modal: C.PRIMARY
	};
	this.splash_order = {
				params: {method: { value: null, url_fragment: false}},
				callbacks: {
					launch: function () {
						$(XSM.splash.order_delivery).removeClass(XSM.effects.slide_left);
						$(XSM.splash.order_pickup).removeClass(XSM.effects.slide_right);
					}
				}
			};
	this.payment_method = {
		params: ['context', 'action'],
		modal: C.PRIMARY,
		callbacks: {
			params_set: function () {
				switch (this.read('context')) {
					case "review_modal":
						this.modal.payment_method(this.read('action'));
						break;
				}
			}
		}
	};
	this.pending_order = {
		params: {
			order_id: {value: null, url_fragment: true},
			status: {value: null, url_fragment: true}
		},
		modal: C.PRIMARY,
		url: {url: "order-confirmation", defer: true, type: C.POST},
		callbacks: {
			params_set: function () {
				switch (this.read('status')) {
					case "launching":
						this.params.status = {value: true};
						this.url.defer = false;
						var request = {
							request: "pending_order" + C.DS + this.read('order_id') + C.DS + C.PENDING,
							trigger: this.trigger
						};
						setTimeout(function () { $(window.xtr.router).trigger(C.ROUTE_REQUEST, request)}, 3000);
						this.unset("launch_callback");
						break;
				}
			},
			launch: function () {
				var data = $.parseJSON(this.deferral_data);

				switch (Number(data.status)) {
					case C.REJECTED:
						break;
					case C.ACCEPTED:
						$(window.xtr.router).trigger(C.ROUTE_REQUEST, {request: "order_accepted", trigger: this.trigger});
						break;
					default:
						var request = {
							request: "pending_order" + C.DS + this.read('order_id') + C.DS + C.PENDING,
							trigger: this.trigger
						};
						setTimeout(function () {
							$(window.xtr.router).trigger(C.ROUTE_REQUEST, request);
						}, 3000);
						break;

				}

			}
		}
};
	this.set_delivery_address = {
		modal: C.PRIMARY,
		url: {url: "set-address", type: C.GET, defer: false},
		params: {context: {url_fragment:true}, restore:{url_fragment:false}},
		callbacks: {
			params_set: function () {
				if (this.read('context') == 'cancel' ) {
					this.unset('url');
					this.unset('launch');
					switch (this.read('restore')) {
						case 'menu':
							this.modal.hide();
							XT.menu.unstash();
							setTimeout(function() {
							$(XT.router).trigger(C.ROUTE_REQUEST, {request:"set_order_method/menu/just_browsing", trigger:{}});
							XT.cart.set_order_method(C.JUST_BROWSING);
							}, 300);
							break;
						case 'review':
							$(XT.router).trigger(C.ROUTE_REQUEST, {request:"review_order", trigger:{}});
						default:
							this.modal.hide();
							break;
					}
				}
			}
		}
	};
	this.set_order_method = {
		modal: C.PRIMARY,
		url: {url: "order-method", type: C.POST, defer: true},
		stash: true,
		params: {
			context: {value: null, url_fragment: true},
			method: {value: null, url_fragment: true}
		},
		callbacks: {
			params_set: function() {
				if (this.read('context') == "menu" && this.read('method') != "delivery") this.stash = false;
			},
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response) { XT.cart.import_cart(response.data.Cart) }
				}, true, true);
			}
		}
	};
	this.set_user_address = {
		params: ['id', 'action'],
		modal: C.PRIMARY,
		callbacks: {
			params_set: function() {
				switch (this.read('action') ) {
					case 'reveal':
						this.modal.reveal_user_addresses(this.modal, this.read('id'));
						break;
					case 'set':
						this.modal.populate_address_form(this.modal, this.read('id'));
						break;
				}
			}
		}
	};
	this.clear_cart = {
		url: { url: "clear-cart", type:C.POST, defer: true},
		callbacks: { launch: function() { XT.router.cake_ajax_response(this.deferral_data) }
		}
	}
	this.cart = {
				modal: C.PRIMARY,
				params: ["action", "action_arg", "data"],
				propagates: false,
				url: {url: "add-to-cart", type: C.POST, defer: true},
				callbacks: {
					params_set: function () {
						switch ( this.read("action") )  {
							case "add":
								 this.url.data = $(XSM.menu.orb_order_form).serialize();
								break;
							case "review":
								this.url =  {
									url: "review-cart",
									type: C.GET,
									defer: false
								};
								this.stash = true;
								this.unset('launch');
								break;
							default: // ie. "cancel"
								this.unset(['url', 'launch']);
								 XT.orbcard.reset_stage( true );
								break;
						}
					},
					launch: function () {
						XT.router.cake_ajax_response(this.deferral_data, {
							callback: function(response) {
								XT.cart.import_cart(response.data.Cart);
								if ( exists(response.delegate_route) ) return;
								pr(XT.orbcard.modal);
								XT.orbcard.modal.show();
							}
						}, true, true);
					}
				}
			};
	return this
};