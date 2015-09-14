/**
 * Created by jono on 8/14/15.
 */
OrbcardMenu = function() {
	this.DOM = {
		box: undefined,
		header: {
			front: {
				box: undefined,
				three_d_box: undefined,
				front:undefined,
				back:undefined
			},
			back: {
				box: undefined,
				buttons: {},
				flyout: {
					box: undefined,
					buttons: {
						all: undefined,
						none: undefined
					}
				}
			}
		},
		list: {
			front: {
				box: undefined,
				buttons: []
			},
			back: {
				box: undefined,
				buttons: []
			}
		}
	};
	this.init();
	return this;
}

OrbcardMenu.prototype = {
	constructor: OrbcardMenu,
	init_list: ['filters', 'headers', 'lists'],
	orbcat: undefined,
	headers: {
		init: function(self) {
			self.headers.hide = function(face, method) { $(self.DOM.header[face].box).addClass( self.hide_classes(method, "header", face) ) };
			self.headers.show = function(face, method) { $(self.DOM.header[face].box).removeClass( self.hide_classes(method, "header", face) ) };
			self.headers.swap = function(show_face, intervals) {
				var hide_face = show_face == C.FRONT ? C.BACK : C.FRONT;
				setTimeout( function() { self.headers[hide_face].hide(hide_face, "hide") }, intervals[0]);
				setTimeout(function() { self.headers[hide_face].hide(hide_face, "fade") }, intervals[1]);
				setTimeout(function() { self.headers[show_face].show(show_face, "unhide") }, intervals[2]);
				setTimeout( function() { self.headers[show_face].show(show_face, "unfade"); }, intervals[3])
			},
			self.headers.set = function(orbcat) {
//				if ( !orbcat ) return self.orbcat = $(self.DOM.header.front.front).html()
				var flipped = $(self.DOM.header.front.three_d_box).hasClass(FX.flipped_x);
				$(self.DOM.header.front[flipped ? C.FRONT : C.BACK]).html(orbcat);
				$(self.DOM.header.front.three_d_box)[flipped ? "removeClass" : "addClass"](FX.flipped_x);
//				$(self.DOM.header.front[flipped ? C.FRONT : C.BACK]).html(orbcat);
			}
		},
		swap: undefined,
		hide: undefined,
		show: undefined,
		set: undefined
	},
	filters: {
		init: function(self) {
			self.filters.reset = function (target_state) {
				if (target_state != C.CHECK && target_state != C.UNCHECK) target_state = C.CHECK;
				self.filters.states = {}
				$(XSM.menu.orb_opt_filter).each(function () { self.filters.states[$(this).data('optflag-id')] = target_state });
			};
			self.filters.replace =  function(html) {
				$(self.DOM.header.back.box).replaceWith(html).removeClass(FX.hidden);
				self.init_DOM()
			};
			self.filters.apply = function(filter) { pr(filter, "Orbcard::filters.apply()") };
			self.filters.show = function() { $(self.DOM.header.back.box).removeClass(FX.slide_right) };
			self.filters.hide = function() { $(self.DOM.header.back.box).addClass(FX.slide_right) };
			self.filters.toggle = function(id) {
				var filters = id in self.filters.states ? [id] : obj_keys(self.filters.states);
				for (var i = 0; i < filters.length; i++) {
					var flag_id = filters[i];
					self.filters.states[flag_id] = self.filters.states[flag_id] === false;
					var remove = self.filters.states[flag_id] ? FX.unchecked: FX.checked;
					var add  = self.filters.states[flag_id] ? FX.checked : FX.unchecked;
					if (id == "-*") {
						remove = FX.checked;
						add = FX.unchecked;
					}
					if (id == "*") {
						remove = FX.unchecked;
						add = FX.checked
					}
					$("span.filter-icon", self.DOM.header.back.buttons[flag_id]).removeClass(remove).addClass(add);
				}
			};
			self.filters.inactive = function()  {
				var active = [];
				for (var id in self.DOM.header.back.buttons) if (self.filters.states[id] === false) active.push(id);
				return active;
			}
		},
		hide: undefined,
		show: undefined,
		states: {},
		replace: undefined,
		reset: undefined,
		toggle: undefined
	},
	lists: {
		init: function(self) {
			self.lists.swap = function(front, intervals) {
				var hide = front == "opts" ? C.BACK : C.FRONT;
				setTimeout( function() { self.lists.hide(hide, "hide") }, intervals[0]);
				setTimeout(function() { self.lists.hide(hide, "fade") }, intervals[1]);
				setTimeout(function() { self.lists.show(front, "unhide") }, intervals[2]);
				setTimeout( function() { self.lists.show(front, "unfade"); }, intervals[3])
			}
			self.lists.hide = function(list, method) { $(self.DOM.list[list].box).addClass( self.hide_classes(method, "list") ) },
			self.lists.show = function(list, method) {
				$(self.DOM.list[list].box).removeClass( self.hide_classes(method,  "list") ) },
			self.lists.replace = function(list, html) {
				delete(self.DOM.list[list].box);
				$(XSM.menu.orb_card_stage_menu_wrapper).append(html);
				self.init_DOM();
			}
			self.lists.unload = function(list) {
				self.lists.hide(list, 'fade');
				setTimeout( function() { $(self.DOM.list[list].box).remove() }, 300);
			}
		},
		swap: undefined,
		hide: undefined,
		show: undefined,
		replace: undefined,
		unload_orbs: undefined
	},
	init: function() {
		for (var i = 0; i < this.init_list.length; i++) this[this.init_list[i]].init(this);
	},
	init_DOM: function() {
		var self = this;
		this.DOM.box = $(XSM.menu.orb_card_stage_menu)[0];
		this.DOM.header.front.box = $(XSM.menu.active_orbcat_menu_header)[0];
		this.DOM.header.front.front = $(XSM.menu.active_orb_name_front_face)[0];
		this.DOM.header.front.back = $(XSM.menu.active_orb_name_back_face)[0];
		this.DOM.header.front.three_d_box = $(XSM.menu.active_orb_name_3d_context)[0];
		this.DOM.header.back.box = $(XSM.menu.optflag_filter_header)[0];
		this.DOM.list.front.box = $(XSM.menu.orb_card_stage_menu_orb)[0];
		this.DOM.list.back.box = $(XSM.menu.orb_card_stage_menu_opt)[0];

		$(XSM.menu.orb_opt_filter).each( function() {
			var flag_id = $(this).data('optflag-id');
			self.DOM.header.back.buttons[flag_id] = this;
			self.filters.states[flag_id] = true;
		});
		$("li", this.DOM.list.front.box).each( function() { self.DOM.list.front.buttons.push(this) });
		$("li.orbopt", this.DOM.list.back.box).each( function() { self.DOM.list.back.buttons.push(this)} );
	},
	hide_classes: function(method, element, face) {
		var classes;
		if (method == "both") {
			if (element == "list") {
				classes = [FX.fade_out, FX.hidden]
			} else {
				classes =[face == C.FRONT ? FX.slide_left : FX.slide_right, FX.hidden]
			}
		}
		if (method == "fade") classes = [FX.fade_out]
		if (method == "slide") classes = [face == C.FRONT ? FX.slide_left : FX.slide_right]
		if (method == "hide") classes = [FX.hidden]
		return classes.join(" ");
	},
	portionable: function(state) {
		if (state === true) $(this.DOM.list.back.box).addClass(XSM.menu.portionable);
		if (state === false) $(this.DOM.list.back.box).removeClass(XSM.menu.portionable);
		return $(this.DOM.list.back.box).hasClass(XSM.menu.portionable);
	},
	show_face: function(show_face, intervals) {
		var self = this;
		var hide_face = show_face == C.FRONT ? C.BACK : C.FRONT;
		setTimeout( function() { self.headers.hide(hide_face, "slide") }, 0);
		setTimeout( function() { self.headers.show(show_face, "hide") }, 300);
		setTimeout( function() { self.headers.hide(hide_face, "hide") }, 300);
		setTimeout( function() { self.headers.show(show_face, "slide") }, 600);
		setTimeout(function() { self.lists.hide(hide_face, "fade") }, 0);
		setTimeout( function() { self.lists.hide(hide_face, "hide") }, 300);
		setTimeout(function() { self.lists.show(show_face, "hide") }, 300);
		setTimeout( function() { self.lists.show(show_face, "fade"); }, 330)
	}
}
