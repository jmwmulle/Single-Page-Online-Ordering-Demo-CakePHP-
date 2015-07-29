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
		populate_address_form: function( address_id ) {
			var address = null;
			for ( var index in XBS.data.User.Address) {
				if (XBS.data.User.Address[index].id === Number(address_id)) address = XBS.data.User.Address[index];
			};
			if (address) {
				$("#user-address-select", C.BODY).addClass(FX.fade_out);
				setTimeout( function() {
						$("#user-address-select", C.BODY).addClass(FX.hidden);
						$("#user-address-form", C.BODY).removeClass(FX.hidden);
						setTimeout( function() {
								$("#user-address-form", C.BODY).removeClass(FX.fade_out)
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
	}
}