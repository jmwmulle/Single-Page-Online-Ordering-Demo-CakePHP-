/**
 * Created by jono on 1/24/15.
 */
var xbs_validation = {
		init: function () { return true;},
		submit_address: function (context, delegate_route) {
			var debug_this = 2;
			if (debug_this > 0) pr([context, delegate_route], "XBS.validation.submit_address(route)", 2);
			$(XSM.forms.order_address_form).validate({
				debug: true,
				rules: {
					"data[orderAddress][firstname]": "required",
					"data[orderAddress][phone]": {required: true, phoneUS: true},
					"data[orderAddress][address]": "required",
					"data[orderAddress][email]": {required: true, email:true},
					"data[orderAddress][postal_code]": {required: true, minlength: 6, maxlength: 7}
				},
				messages: {
					"data[orderAddress][firstname]": "Well we have to call you <em>something!</em>",
					"data[orderAddress][email]": "No spam, promise—just for sending receipts!",
					"data[orderAddress][phone]": {
						required: "We'll need this in case there's a problem with your order.",
						phoneUS: "Just ten little digits, separated by hyphens if you like..."},
					"data[orderAddress][address]": "It's, err, <em>delivery</em> after all...",
					"data[orderAddress][postal_code]": {
						required: "This is how we check if you're in our delivery area!",
						minlength: "Prooooobably something like \"A0A 0A0\"...",
						maxlength: "Prooooobably something like \"A0A 0A0\"..."
					}
				},
				submitHandler: function () {
					$.ajax({
						type: C.POST,
						url: "confirm-address/session",
						data: $(XSM.forms.order_address_form).serialize(),
						success: function (response) {
							if (debug_this > 1) pr(response, 'XBS.validation.submit_address()->confirm_address_validation', 2);
							try {
								response.delegate_route = delegate_route;
								XBS.routing.cake_ajax_response(response, {
									callback: function(response) {
										XBS.layout.dismiss_modal(XSM.modal.primary);
										XBS.data.Service = response.cart.Service;
									}
								}, true, true);

							} catch (e) {
								pr(e, null, true);
								// todo: something... with... this... eror?
							}
//							if (delegate_route) {
//								setTimeout(function () {
//									$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: delegate_route, trigger: {}});
//								}, 300);
//							}
						}
					});
				}
			});
			$(XSM.forms.order_address_form).submit();
		},
		submit_register: function (route) {
			var debug_route = 0;
			if (debug_route > 0) pr(route, "XBS.validation.submit_address(route)", 2);
			$(XSM.forms.users_form).validate({
				debug: true,
				rules: {
					"data[Users][firstname]": "required",
					"data[Users][email]": {required: true, email: true},
					"data[Users][phone]": {required: true, phoneUS: true},
					"data[Users][address]": "required",
					"data[Users][postal_code]": {required: true, minlength: 6, maxlength: 7}
				},
				messages: {
					"data[Users][firstname]": "Well we have to call you <em>something!</em>",
					"data[Users][email]": "This will be your 'username'. Don't worry, we won't share it or spam you!",
					"data[Users][phone]": {
						required: "We'll need route in case there's a problem with your order.",
						phoneUS: "Jusst ten little digits, separated by hyphens if you like..."},
					"data[Users][address]": "It's, err, <em>delivery</em> after all...",
					"data[Users][postal_code]": {
						required: "This is how we check if you're in our delivery area!",
						minlength: "Prooooobably something like \"A0A 0A0\"...",
						maxlength: "Prooooobably something like \"A0A 0A0\"..."
					}
				},
				submitHandler: function () {
					$.ajax({
						type: route.url.type,
						url: "users/add",
						data: $(XSM.forms.users_form).serialize(),
						success: function (data) {
							//todo: handle... this?
						},
						fail: function () {},
						always: function () {}
					});
				}
			});
			$(XSM.forms.users_form).submit();
		}
	};
