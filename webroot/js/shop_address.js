$(document).ready(function(){

	$('#OrderSameaddress').click(function(){

		if($('#OrderSameaddress').attr("checked", "checked")) {

			$('#OrderDeliveryAddress').val($('#OrderBillingAddress').val());
			$('#OrderDeliveryAddress2').val($('#OrderBillingAddress2').val());
			$('#OrderDeliveryCity').val($('#OrderBillingCity').val());
			$('#OrderDeliveryPostalCode').val($('#OrderBillingPostalCode').val());

		} else {

			$("#OrderDeliveryAddress").val('');
			$('#OrderDeliveryAddress2').val('');
			$('#OrderDeliveryCity').val('');
			$('#OrderDeliveryPostalCode').val('');

		}

	});

});
