/**
 * Created by jono on 8/7/15.
 */
Orbcard = function(orb_id) {
	this.init(orb_id);
	return this;
}

Orbcard.prototype = {
	constructor: Orbcard,
	init_list: ['content_boxes', 'stage', 'rows', 'mini_cfg'],
	exposed_face: C.FRONT_FACE,
	animation_queue: {start:0, queued:0, animating: false},
	filters: {},
	flag_map: undefined,
	XT: undefined,
	orb: undefined,
	DOM: {
		stage: undefined,
		rows: [],
		mini_cfg: undefined,
		content_boxes: [],
		menu: {
			box: undefined,
			header: undefined
		}
	},
	content_boxes: {
		init: function(self) {
			self.content_boxes.hide = function() { $(self.DOM.content_boxes).each(function() { $(this).addClass(FX.fade_out) }) }
			self.content_boxes.show = function() { $(self.DOM.content_boxes).each(function() { $(this).removeClass(FX.fade_out) }) }
		},
		hide: undefined,
		show: undefined
	},
	menu: undefined,
	mini_cfg: {
		init: function(self) {
			self.mini_cfg.clear = function() { $(self.DOM.mini_cfg).html(""); return self.mini_cfg };
			self.mini_cfg.render = function() {
				for (var i = 0; i < self.orb.orbopts.Opts.length; i++) {
					var opt = self.orb.orbopts.Opts[i];
					if ( opt.selected() ) $(self.DOM.mini_cfg).append( opt.mini_cfg() );
				}
			}
		},
		clear: undefined,
		render: undefined
	},
	modal: undefined,
	stage: {
		init: function(self) { self.stage.replace = function(html) { $(self.DOM.stage).html(html); self.init_DOM(); } },
		replace: undefined
	},
	rows: {
		init: function(self) {
			self.rows.close = function() { return 0 }
		},
		close: undefined
	},


	init: function(orb_id) {
		var self = this;
		$.ajax({
			type: C.GET,
			url: "flagmap",
			success: function (data) { data = $.parseJSON(data); self.flag_map = data.data }
		});
		this.init_DOM();
		this.modal = new Modal(C.ORBCARD);
		$(this).on(C.ORB_CFG, self.render);
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
	},

	configure: function(id, rank) {
		if (this.orb) {
			this.orb.configure(rank);
		} else {
			this.orb = XT.cart.configure(id, rank)
		}
		this.render(true);
	},

	render: function (kill) { this.orb.render(kill); this.mini_cfg.clear().render() },

	/**
	 * Maps DOM elements into Orbcard.DOM.
	 */
	init_DOM: function() {
		this.DOM.stage = $(XSM.menu.orb_card_stage)[0];
		this.DOM.mini_cfg = $(XSM.menu.tiny_orb_opts_list)[0];
		this.menu = new OrbcardMenu();
		this.menu.init_DOM();
		var self = this;
		$(XSM.menu.orb_card_content_box, this.DOM.stage).each(function () { self.DOM.content_boxes.push(this) });
		$(XSM.menu.orb_card_row, this.DOM.stage).each(function () { self.DOM.rows.push(this) });
	},

	flipped: function(assert) {
		if (assert === true) $(XSM.menu.orb_card_3d_context).removeClass(FX.flipped_y);
		if (assert === false) $(XSM.menu.orb_card_3d_context).addClass(FX.flipped_y);
		return $(XSM.menu.orb_card_3d_context).hasClass(FX.flipped_y);
	},

	/**
	 * Loads orbcat data into orbcard menu and triggers call to load default Orb.
	 *
	 * @param orbcat_id
	 * @param orbcat_name
	 */
	load_menu: function (data) {
		var self = this;
		$(this).on(C.ORB_LOADED, function() {
			$(self).off(C.ORB_LOADED);
			self.menu.lists.unload(C.FRONT);
			setTimeout( function() { self.menu.headers.set(data.orbcat) }, 150);
			setTimeout( function() { self.menu.lists.replace(C.FRONT, data.view_data.orb_list) }, 300 );
			setTimeout( function() { self.menu.lists.show(C.FRONT, 'hide') }, 630);
			setTimeout( function() { self.menu.lists.show(C.FRONT, 'fade') }, 660)
		});
		$(XT.router).trigger(C.ROUTE_REQUEST, {request:data.route, trigger:{}});
		return this
	},

	/**
	 * Updates orb card when an orb is clicked in the bottom menu.
	 *
	 * @param orb_id
	 */
	load_orb: function (orb_id, data) {
		var self = this;
		var row_time = this.rows.close();
		setTimeout(function() { self.content_boxes.hide() }, row_time);
		setTimeout(function () {
			self.stage.replace(data.orb_card_stage);
			self.menu.filters.replace(data.filter);
			self.menu.lists.unload(C.BACK);
			self.menu.lists.replace(C.BACK, data.orbopts.list );
			self.menu.portionable( Number(data.orbopts.portionable) === 1 )
			self.init_DOM();
			if (self.orb) self.orb.unload()
			self.orb = XT.cart.configure(orb_id);
			self.render();
			setTimeout(function() {
				self.content_boxes.show();
				$(self).trigger(C.ORB_LOADED);
			}, 30);
		}, row_time);

	},

	/**
	 * Resets the Orbcard configuration and view.
	 */
	reset_stage: function () {
		try {
			this.orb.reset(); // will fail when no orb yet loaded
		} catch (e) {}
		var event_delay = 0;

		if (this.exposed_face == C.BACK) {
			this.show_face(C.FRONT);
			event_delay = 950;
		}
		return event_delay;
	},

	/**
	 * Exposes back face of Orbcard & ensures UI agrees with Orb object configuration.
	 *
	 * @param price_rank
	 */
	show_face: function (face) {
		this.menu.init_DOM();
		if (this.exposed_face == face) return this.rows.close();
		this.exposed_face = face;
		var intervals = [0, 30, 300, 300];
		var self = this;
		var close = function() {
			var row_menu_hide_time = 0;
			$(as_class(FX.swap_width)).each(function () {
				row_menu_hide_time = 800;
				self.toggle_row_menu($($(this).children(".orb-card-button")[0]).attr('id'), C.HIDE)
			});
			return row_menu_hide_time;
		}
		setTimeout(function () {
			self.flipped(face == C.FRONT);
			self.menu.show_face(face, intervals);
		}, self.rows.close())

		return this;
	},

	toggle_filter: function(id) {
		this.menu.filters.toggle(id);
		this.orb.filter( this.menu.filters.inactive() );
	},

	/* -------------------------------------------------------------------------------------------------------------  */
	/* ----------------------------------------------- NOT YET REBUILT ---------------------------------------------  */
	/* -------------------------------------------------------------------------------------------------------------  */

	/**
	 * Swaps state of Orbcard row to expose or hide it.
	 *
	 * @param menu
	 * @param state
	 * @returns {boolean}
	 */
	toggle_row_menu: function (menu, state) {
		var row;
		var button;
		var panel;
		if (menu == "register") {
			row = XSM.menu.orb_card_row_1;
			button = XSM.menu.register_button;
			panel = XSM.menu.registration_panel;
		} else {
			row = XSM.menu.orb_card_row_3;
			button = XSM.menu.share_button;
			panel = XSM.menu.social_panel;
		}

		if ($(panel).hasClass(FX.true_hidden)) $(panel).removeClass(FX.true_hidden);
		if (!state) state = $(row).hasClass(FX.swap_width) ? C.HIDE : C.SHOW;

		if (state == C.HIDE) {
			var wait_for_complete = 0;
			if (!(this.animation_queue.animating === false)) {
				if (this.animation_queue.queued > 1) return;
				this.animation_queue.queued += 1;
				wait_for_complete = new Date().getTime() - this.animation_queue.start;
			}
			setTimeout(function () {
				this.animation_queue.start = new Date().getTime();
				if (!$(row).hasClass(FX.swap_width)) return;
				$(panel).addClass(FX.fade_out);
				setTimeout(function () {
					$(panel).hide();
					$(button).addClass(FX.stash);
					setTimeout(function () {
						$(row).removeClass(FX.swap_width);
						setTimeout(function () { $(button).removeClass(FX.stash);}, 300);
						$(XT).trigger(C.ORB_ROW_ANIMATION_COMPLETE, {menu: menu, finished: C.SHOW});
					}, 500)
				}, 300);
			}, wait_for_complete);
		}

		if (state == C.SHOW) {
			var wait_for_complete = 0;
			if (!(this.animation_queue.animating === false)) {
				if (this.animation_queue.queued > 1) return;
				this.animation_queue.queued += 1;
				wait_for_complete = new Date().getTime() - this.animation_queue.start;
			}
			setTimeout(function () {
				this.animation_queue.start = new Date().getTime();
				if ($(row).hasClass(FX.swap_width)) return;
				$(row).addClass(FX.swap_width);
				if (!$(panel).hasClass(FX.fade_out)) $(panel).addClass(FX.fade_out);
				$(panel).show();
				setTimeout(function () {
					setTimeout(function () {
						$(panel).removeClass(FX.fade_out);
					}, 300);
					$(XT).trigger(C.ORB_ROW_ANIMATION_COMPLETE, {menu: menu, finished: C.HIDE});
				}, 300)
			}, wait_for_complete);
		}

		return true;
	},




	/**
	 * Apply current state of opt flag filters to visible orbopts list
	 *
	 * @param flag_id
	 * @returns {boolean}
	 */
	apply_filters: function(flag_id) {
		if (flag_id == "*") {
			for (var id in this.filters) this.apply_filters(id);
			return true;
		}
 		$(XSM.menu.orb_opt).addClass(FX.fade_out);
		setTimeout(function () {
			$(XSM.menu.orb_opt).show();
			$.map($(XSM.menu.orb_opt), function (orb_opt, index) {
				$.map($(orb_opt).data('optflags'), function (flag_id) {
					if (this.filters[flag_id] == C.UNCHECK) $(orb_opt).hide();
				});
			});
			setTimeout(function () { $(XSM.menu.orb_opt).removeClass(FX.fade_out); }, 30);
		}, 300);

		return true;
	},

//	reveal_modal: function() {
//		$(XSM.menu.orb_card_overlay).removeClass(FX.hidden);
//		setTimeout( function() {
//			$(XSM.menu.orb_card_overlay).removeClass(FX.fade_out);
//			$(XSM.modal.orb_card).show('clip')
//		}, 300);
//	}
};
