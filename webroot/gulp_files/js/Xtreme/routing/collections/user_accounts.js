/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.user_accounts = function() {
	this.login = {
		url: {url: false, type: C.POST},
		modal: XSM.modal.primary,
		params: {
			context: {},
			channel: {url_fragment: true},
			restore: {}
		},
		callbacks: {
			params_set: function () {
				var context = this.read('context');
				var channel = this.read('channel');
				var restore = this.read('restore');
				pr(this.params, "login::params");
				switch (context) {
					case "confirm-address":
						switch (channel) {
							case "email":
								this.unset("url");
								this.set_callback('launch', function () {
									$(XBS.routing).trigger(C.ROUTE_REQUEST, {
										request: "login/modal/email/confirm-address",
										trigger: this.trigger
									});
								});
								break;
							default:
								this.unset('url');
								this.unset('modal');
								this.set_callback('launch', function () {
									$(XSM.modal.confirm_address_login_panel).hide().removeClass(XSM.effects.true_hidden);
									setTimeout(function () {
										$(XSM.modal.confirm_address_login_panel).show('clip');
									}, 30);
								})
						}
						break;
					case "modal":
						this.url = {url: "login/email", defer: false, type: C.GET};
						if (restore) {
							this.set_callback('launch', function () {
								pr("got here");
								$("#on-close").replaceWith(
									'<div id="on-close" class="true-hidden" data-action="restore/confirm_address"></div>');
							});
						}
						break;
					default:
						if (channel == "email") {
							this.url.url = "login/email";
							this.url.type = C.GET;
							//											this.url.defer = true;
							this.set_callback("launch", function () {
								pr("login/email");
								pr(this.deferral_data);
							})
						}
				}
			},
			launch: function () {
				window.location = "http://development-xtreme-pizza.ca/auth/" + this.read('channel');
			}
		}
	};
	this.register = {
				modal: XSM.modal.primary,
				url: {url: "sign-up", type: C.POST, defer: true},
				params: {
					context: {},
					channel: {value: null, url_fragment: false},
					restore: {},
					hide_reg: {value: false}
				},
				callbacks: {
					params_set: function () {
						var channel = this.read('channel');
						switch (this.read('context')) {
							case "modal":
								this.url.defer = true;
								if (in_array(channel, ['email', "submit"])) this.url.url = false;
								if (in_array(channel, ["twitter", "facebook", "google"])) {
									this.add_param("hide-reg", true, false);
									this.url.url = "auth/" + channel;
								}
								if (channel == 'submit') {
									this.set_callback("launch", function () { XBS.validation.submit_register(this);})
								}
								break;
							case "topbar":
								this.url.defer = false;
								this.unset('launch');
								break;
							case "orb_card":
								this.set_callback("launch", function () {
									var data = this.deferral_data;
									$(XSM.modal.primary_content).html(data);
									$($(XSM.modal.primary).find(".register-link.email")[0]).addClass(XSM.effects.active);
									XBS.menu.toggle_orb_card_row_menu("register", C.HIDE);
									setTimeout(function () {
										$(XSM.modal.primary).removeClass(XSM.effects.slide_up);
										setTimeout(function () {
											$("#registration-method-bar").addClass("diminish");
											setTimeout(function () {
												$(XSM.modal.primary_deferred_content).removeClass(XSM.effects.slide_left);
											}, XBS.data.delays.global_transition);
										}, XBS.data.delays.global_transition);
									}, XBS.data.delays.orb_card_row_toggle);
								});
								break;
						}
					},
					launch: function () {
						var container = $(this.modal).find(XSM.modal.deferred_content)[0];
						var load_time = 30;
						$("#registration-method-bar").addClass("diminish");
						setTimeout(function () {
							$(".register-link.email").addClass(XSM.effects.active);
							if (this.deferral_data) {
								$(container).replaceWith(
									$("<div/>").addClass([XSM.modal.deferred_content, XSM.effects.slide_left].join(" "))
										.html(this.deferral_data)
								);
							}
							setTimeout(function () { $(container).removeClass(XSM.effects.slide_left);}, load_time);
						}, 300);
					}
				}
			};
	this.submit_registration = {
				modal: XSM.modal.primary,
				url: {url: false, type: C.POST, defer: true},
				params: {channel: {value: false, url_fragment: false}},
				callbacks: {
					launch: function () {
						if (this.read('channel') == "email") {
							XBS.validation.submit_register(this);
						} else {
							this.url.url = "auth" + C.DS + this.read('channel');
						}
					}
				}
			};

	return this;
};