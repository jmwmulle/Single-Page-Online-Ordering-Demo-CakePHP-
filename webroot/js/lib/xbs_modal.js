/**
 * Created by jono on 7/28/15.
 *
 * created late in development, this fucker is NOT a comprehensive collection of all modal-only functions. sorry!!!!
 */

var xbs_modal = {
	on_close_element:"<div id='on-close' class='true-hidden' data-action='unstash'></div>",
	init: function() {

		return true;
	},
	order_method: {
		reveal_user_addresses: function() {
			$("#user-address-form", C.BODY).addClass(FX.fade_out);
			setTimeout( function() {
				$("#switch-user-address", C.BODY).addClass(FX.hidden);
				$("#user-address-form", C.BODY).addClass(FX.hidden);
				$("#user-address-select", C.BODY).removeClass(FX.hidden);
					setTimeout( function() {
						$("#user-address-select", C.BODY).removeClass(FX.fade_out);
					}, 10);
			}, 300);
		},
		populate_address_form: function( address_id ) {
			var address = null;
			for ( var index in XBS.data.User.Address) {
				if (XBS.data.User.Address[index].id === Number(address_id)) address = XBS.data.User.Address[index];
			};
			if (address_id == -1) {
				address = XBS.data.User.Address[0];
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
						$("#user-address-select", C.BODY).addClass(FX.hidden);
						$("#user-address-form", C.BODY).removeClass(FX.hidden);
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
											$(as_id(field_id) + " option", XSM.modal.primary).each(function() {
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
				}, 300);
			}
		},
		dismiss: function(data, context, modal, modal_content) {
			XBS.layout.dismiss_modal(modal, false);
			setTimeout(function () {
				$(modal_content).html(data);
				if (context == "review") $(XSM.modal.on_close).replaceWith(XBS.modal.on_close_element);
				$(XSM.modal.submit_order_button_wrapper).html(
					XSM.generated.order_address_button(context)
				);
				setTimeout(function () { $(modal).removeClass(XSM.effects.slide_up);}, 30);
			}, 600);
		}
	},
	payment_method: function(payment_type) {
		XBS.data.order.payment = payment_type;
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


}