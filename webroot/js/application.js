
if (page_name ==  "Vendor Interface") {
	$(".breakout").each(function () {
	$(this).css({
		left: (($(window).width() - $(this).innerWidth()) / 2) + "px",
		top: (($(window).height() - $(this).innerHeight()) / 2) + "px"
	})
});
}


function InitError(raisedError, message) {
	this.prototype = Error.prototype;
	this.name = "InitError";
	this.message = message ? message : "No additional message provided";
	this.raisedError = raisedError;
	this.read = function () {
		if (raisedError.stackHistory != C.UNDEF) {
			this.raisedError.stack = this.raisedError.stackHistory;
		}
		//todo: make this not rely on pr() being defined!!
		pr(this.raisedError.message + ". \n\tWith trace: {0}".format(this.raisedError.stack), "InitError > {0}".format(this.raisedError.name), true);
	};

	return this;
}

$(document).ready( function() {
	XBS.init(is_splash, page_name, host, cart);
});


