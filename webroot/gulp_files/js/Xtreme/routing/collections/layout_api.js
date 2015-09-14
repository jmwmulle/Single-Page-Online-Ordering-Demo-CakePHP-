/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.layout_api = function() {
	this.flash = {
				params: {type: { url_fragment: false}},
				modal: XSM.modal.flash,
				behavior: C.OL,
				callbacks: {
					launch: function () {
						$(this.modal_content).html("<h5>Oh Noes!</h5><p>Something went awry there. Let us know so we can fix it!</p>");
						$(this.modal).removeClass(XSM.effects.slide_up);
					}
				}
			};
	this.footer = {
		params: ["method"],
		callbacks: {
			launch: function () {
				if ($(XSM.global.footer).hasClass('reveal')) {
					$(XSM.global.footer).addClass('stow');
					setTimeout(function () {
						$(XSM.global.footer).removeClass('reveal');
						setTimeout(function () {
							$(XSM.global.footer).removeClass('stow');
						}, 30);
					}, 30);
				}
				if (!$(XSM.global.footer).hasClass('reveal')) {
					$(XSM.global.footer).addClass('reveal');
				}
			}
		}
	};
	this.launch_apology = {
		url: {url: 'launch-apology', type: C.GET, defer: true},
		modal: XSM.modal.primary,
		params: ['action'],
		callbacks: {
			params_set: function () {
				if (this.read('action') == 'close') {
					this.unset('url');
					XBS.layout.dismiss_modal(this.modal);
				}
			}
		}
	};

	return this;
};