/**
 * Created by jono on 1/24/15.
 */
function  validate_address(restore, delegate) {
	var debug_this = 2;
	if (debug_this > 0) pr([restore, delegate], "XBS.validation.submit_address(route)", 2);
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
				url: [XT.host, "confirm-address", "session"].join(C.DS),
				data: $(XSM.forms.order_address_form).serialize(),
				success: function (response) {
					if (debug_this > 1) pr($.parseJSON(response), 'XBS.validation.submit_address()->confirm_address_validation', 2);
					try {
						new Modal(C.PRIMARY).hide();
						XT.cart.import_cart(response.cart);
						var route;
						switch (restore) {
							case "menu":
								route = "menu/unstash";
								break;
							case "review":
								route = "review_order"
								break;
						}
						setTimeout(function() {$(XT.router).trigger(C.ROUTE_REQUEST, {request:route, trigger:{}}) }, 300);
					} catch (e) {
						pr(e, null, true);
						// todo: something... with... this... eror?
					}
				}
			});
		}
	});
	$(XSM.forms.order_address_form).submit();
};

function  validate_pickup_information(restore, delegate) {
	var debug_this = 2;
	if (debug_this > 2) pr([restore, delegate], "XT.validate_pickup_information(restore, delegate)", 2);
	$(XSM.forms.order_information_form).validate({
		debug: true,
		rules: {
			"data[orderInformation][firstname]": "required",
			"data[orderInformation][phone]": {required: true, phoneUS: true}
		},
		messages: {
			"data[orderInformation][firstname]": "Well we have to call you <em>something!</em>",
			"data[orderInformation][phone]": {
				required: "We'll need this in case there's a problem with your order.",
				phoneUS: "Just ten little digits, separated by hyphens if you like..."}
		},
		submitHandler: function () {
			$.ajax({
				type: C.POST,
				url: [XT.host, "confirm-pickup-info", "session"].join(C.DS),
				data: $(XSM.forms.order_information_form).serialize(),
				success: function (response) {
					XT.router.cake_ajax_response(response, {
						callback: function(response) {
							pr(response);
							new Modal(C.PRIMARY).hide();
							XT.cart.import_cart(response.cart);
							var route;
							if ( restore == "menu") route = "menu/unstash";
							if ( restore == "review") route = "review_order";
							setTimeout(function() {$(XT.router).trigger(C.ROUTE_REQUEST, {request:route, trigger:{}}) }, 300);
						}
					}, true, true);
					return;
				}
			});
		}
	});
	$(XSM.forms.order_information_form).submit();
};


function validate_register(restore, delegate) {
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

XT.validate = function(target, restore, delegate) { window["validate_" + target](restore, delegate) }