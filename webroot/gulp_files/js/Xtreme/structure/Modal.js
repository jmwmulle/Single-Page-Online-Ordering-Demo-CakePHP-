/**
 * Created by jono on 7/28/15.
 *
 * created late in development, this fucker is NOT a comprehensive collection of all modal-only functions. sorry!!!!
 */

function reveal_user_addresses() {
	$("#user-address-form", C.BODY).addClass(FX.fade_out);
	setTimeout( function() {
		$("#switch-user-address", C.BODY).addClass(FX.hidden);
		$("#user-address-form", C.BODY).addClass(FX.hidden);
		$("#user-address-select", C.BODY).removeClass(FX.hidden);
			setTimeout( function() {
				$("#user-address-select", C.BODY).removeClass(FX.fade_out);
			}, 10);
	}, 300);
};
function populate_address_form(self,  address_id ) {
	var address = null;
	for ( var index in XT.cart.session_data.User.Address) {
		if (XT.cart.session_data.User.Address[index].id === Number(address_id)) address = XT.cart.session_data.User.Address[index];
	};
	if (address_id == -1) {
		address = XT.cart.session_data.User.Address[0];
		address.address  = null;
		address.address_2 = null;
		address.phone = null;
		address.email = null;
		address.building_type = null;
		address.note = null;
		address.postal_code = null;
		address.delivery_time = null;
		address.id = null;
	}
	if (address) {
		$("#user-address-select", C.BODY).addClass(FX.fade_out);
		setTimeout( function() {
				var form_elements = $("#orderAddressForm")[0];
				for (var i = 1; i < form_elements.length; i++) {
					var field_id = $(form_elements[i]).attr('id');
					var address_component = title_to_snake(field_id.match( /^orderAddress(.*)$/)[1]);
					if (address_component == "address2") address_component = "address_2";
					if (address_component in address) {
						if (address_component != "building_type") {
							$(as_id(field_id), XSM.modal.primary).val(address[address_component]);
						} else {
							$(as_id(field_id) + " option", self.DOM.box).each(function() {
								if ( $(this).html() == address[address_component] ) {
									$(this).attr("selected", true);
								} else {
									$(this).removeAttr("selected");
								}
							})
						}
					}
					$("#switch-user-address", C.BODY).removeClass(FX.hidden);
					$("#user-address-form", C.BODY).removeClass(FX.fade_out);
				};
		}, 10)
		setTimeout( function() {
				$("#user-address-select", C.BODY).addClass(FX.hidden);
				$("#user-address-form", C.BODY).removeClass(FX.hidden);
		}, 310);
	}
};

function dismiss (self, data, context, modal, modal_content) {
	self.hide();
	setTimeout(function () {
		$(modal_content).html(data);
		if (context == "review") $(XSM.modal.on_close).replaceWith(XBS.modal.on_close_element);
		$(XSM.modal.submit_order_button_wrapper).html(
			XSM.generated.order_address_button(context)
		);
		setTimeout(function () { $(modal).removeClass(XSM.effects.slide_up);}, 30);
	}, 600);
};

function payment_method(payment_type) {
	XT.cart.session_data.Service.pay_method = payment_type;
	$(XSM.modal.payment_method_input).val(payment_type);
	if (payment_type == "cash") {
		$("#payment-cash").removeClass([FX.inactive, FX.cancel].join(" ")).addClass(FX.active);
		$("#payment-debit").removeClass(FX.active).addClass([FX.inactive, FX.cancel].join(" "));
	}
	if (payment_type == "debit") {
		$("#payment-debit").removeClass([FX.inactive, FX.cancel].join(" ")).addClass(FX.active);
		$("#payment-cash").removeClass(FX.active).addClass([FX.inactive, FX.cancel].join(" "));
	}
}


Modal = function(name) {
	this.name = undefined;
	this.DOM = {
		box: undefined,
		content: undefined,
		masthead: undefined
	}
	this.hide = undefined;
	this.show = undefined;
	this.hide_class = undefined;
	this.dismiss = undefined;
	this.init(name);
	return this;
}
		this.name = name;
Modal.prototype = {
	on_close_element:"<div id='on-close' class='true-hidden' data-action='unstash'></div>",
	init_list: ['on_close', 'content'],
	init: function(name) {
		var self = this;
		this.name = name;

		this.DOM.box = $(XSM.modal[name])[0];
		try {
			this.DOM.content = $(XSM.modal.primary_content)[0];
			this.DOM.masthead = $( "#modal-masthead" )[0];
		} catch (e) {
			this.DOM.content = false;
			this.DOM.masthead = false
		}
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
		if (name == C.ORBCARD) {
			this.hide_class = FX.hidden;
			this.hide = function(close_button) {
				$(XSM.modal.orb_card).hide('clip');
				$(XSM.menu.orb_card_overlay).addClass(FX.fade_out);
				setTimeout(function () { $(XSM.menu.orb_card_overlay).addClass(FX.hidden); }, 300);
				if (close_button) $(self).trigger(C.MODAL_DISMISSED)
				return 300;
			};
			this.show = function() {
				$(XSM.menu.orb_card_overlay).removeClass(FX.hidden);
				setTimeout( function() {
					$(XSM.menu.orb_card_overlay).removeClass(FX.fade_out);
					$(XSM.modal.orb_card).show('clip')
				}, 300);
			}
		} else {
			this.hide_class = FX.slide_up;
			this.hide = function(close_button) {
				$(self.DOM.box).addClass(self.hide_class);
				if (close_button) $(self).trigger(C.MODAL_DISMISSED);
				return 300;
			};
			this.show = function() { if ( self.hidden() ) $(self.DOM.box).removeClass( self.hide_class ) };
		}

		$(this).on(C.MODAL_DISMISSED, function() { self.on_close.launch() });

		// add extra functions based on context, cheat version of class inheritance lolol
		if (this.name == C.PRIMARY) {
			this.reveal_user_addresses = reveal_user_addresses;
			this.populate_address_form = populate_address_form;
			this.dismiss = dismiss;
			this.payment_method = payment_method;
		}
	},
	content: {
		init: function(self) {
			self.content.set = function(data) { $(self.DOM.content).html(data) }
		},
		set: undefined
	},
	hidden: function() { var self = this; return $(this.DOM.box).hasClass( this.hide_class ) },
	on_close: {
		init: function(self) {
			self.on_close.launch = function() {
				var action = $( $(self.DOM.box).find(XSM.modal.on_close)[0]).data('action').replace(/(-)/g, "_");
//				if (action == undefined) action="unstash";
				if (action) self.on_close[action]();
			};
			self.on_close.reset_user_activity =  function() {
				$(XSM.menu.user_activity_panel).children().each(function () {
					if ($(this).hasClass(FX.active)) $(this).removeClass(FX.active).addClass(FX.inactive);
					if ($(this).hasClass(FX.active_by_default)) $(this).addClass(FX.active);
				})
				XT.menu.unstash();
			};
			self.on_close.unstash = function() { XT.menu.unstash() }
		},
		launch: undefined,
		reset_order_method: undefined,
		unstash_menu: undefined
	},
	resize: function() {
		var modal_width, modal_max_height, modal_left, modal_top;
		switch (this.name) {
			case C.PRIMARY:
				modal_width = 1200 / 12 * 8;
				modal_max_height = 0.8 * $(window).innerHeight();
				modal_top = 0.1 * $(window).innerHeight();
				var pm_width = 0.8 * $(window).innerWidth();
				if (pm_width > modal_width) {
					modal_left = ($(window).innerWidth() - modal_width) / 2;
				} else {
					modal_left = 0.1 * $(window).innerWidth();
				}
				if (pm_width < modal_width) modal_width = pm_width;
				break;
			case C.FLASH:
				modal_width = 40 * C.REM;
				modal_left = (window.innerWidth / 2) - ( modal_width / 2);
				modal_max_height = "default";
				modal_top = 2 * C.REM;
				break;
			default:
				return;
		}

		$(this.DOM.box).css({
			top: modal_top,
			left: modal_left,
			width: modal_width,
			maxHeight: modal_max_height
		});
	}
}
