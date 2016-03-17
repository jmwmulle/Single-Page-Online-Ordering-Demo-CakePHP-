/**
 * Created by jono on 12/30/14.
 */

XtremeRoute = function (name, data, request) {
	this.route_data =  undefined;
	this.param_data = undefined;

	// data
	this.route_uid = undefined;
	this.route_name = undefined;
	this.request = undefined;

	this.trigger  = undefined;
	this.modal  = undefined;
	this.params  = undefined;
	this.deferral_data  = undefined;
	this.suppress_loader = false;

	// callbacks
	this.params_set_callback  = undefined;
	this.launch_callback  = undefined;
	this.post_init_callback  = undefined;

	// behaviors
	this.stash  = false;
	this.overlay  = false;
	this.__stop_propagation  = true;
	this.init(name, data, request);
	return this;
}

XtremeRoute.prototype = {
	constructor: XtremeRoute,


	/**
	 * class initiation
	 *
	 * @param name
	 * @param data
	 * @returns {boolean}
	 * @private
	 */
	init: function (name, route_data, request_obj) {
		var debug_this = 0;
		if (debug_this > 0) pr([name, route_data, request_obj], "XtremeRoute::__init( request_obj )", 2);
		this.route_data = $.extend(true, {}, route_data);
		this.request = request_obj.request;
		this.trigger = {event: request_obj.trigger, element: request_obj.trigger.currentTarget};
		this.param_data = request_obj.request.split(C.DS).slice(1);
		try {
			if ("stash" in route_data) this.stash = route_data.stash;
			if ("modal" in route_data) this.modal = new Modal(route_data.modal);
			this.url = {url:undefined, type: undefined, defer: undefined};
			if ("url" in route_data) {this.set_url(route_data.url) }
			if ("loading_animation" in route_data) this.suppress_loader = !route_data.loading_animation;
		} catch (e) {}

		try {
			this.__stop_propagation = "propagates" in route_data ? !route_data.propagates : true;
		} catch(e) {
			throw("Route not found");
		}
		this.stop_propagation();

		if ("params" in route_data) {
			this.params = {};
			if (is_array(route_data.params)) {
				for (var i = 0; i < route_data.params.length; i++) {
					this.params[route_data.params[i]] = {value: false, url_fragment: false, post_init: false};
				}
			} else {
				for (var param in route_data.params) {
					this.params[param] = {value: false, url_fragment: false, post_init: false};
					if ("value" in route_data.params[param]) this.params[param].value = route_data.params[param].value;
					if ("url_fragment" in route_data.params[param]) this.params[param].url_fragment = route_data.params[param].url_fragment === true;
					if ("post_init" in route_data.params[param]) this.params[param].post_init = route_data.params[param].post_init === true;
				}
			}
		}

		if ("callbacks" in route_data) {
			if ("post_init" in route_data.callbacks) this.post_init_callback = route_data.callbacks.post_init
			if ("params_set" in route_data.callbacks) this.params_set_callback = route_data.callbacks.params_set
			if ("launch" in route_data.callbacks) {
				this.launch_callback = route_data.callbacks.launch;
				$(this).on(C.ROUTE_LAUNCHED, this.launch_callback);
			}

		}
		if (this.post_init_callback) this.post_init_callback();
		$(this).off();  // I don't understand why but this is fucking crucial to prevent executing every ajax call twice
		this.route_uid = "*" + this.route_name + "[" + new Date().getTime() + "]";
		this.set_params();
		if (this.launch_callback) $(this).on("route_launched", this.launch_callback);
	},

	/**
	 * __debug() gives method string for printing to console
	 * @param method_str
	 * @param args
	 * @returns {string}
	 * @private
	 */
	__debug: function (method_str, args) {
		if (!!args) {
			if (is_array(args)) args = args.join(", ");
			return this.toString() + "::" + method_str + "(" + args + ")"
		} else {
			return this.toString() + "::" + method_str + "#"
		}
	},



	/**
	 * __set_params() readies instance with param data pulled from data-route attr of initiating html element
	 * @param param_values
	 * @returns {boolean}
	 * @private
	 */
	set_params: function () {
		if (this.param_data.length == 0) return this.params_set_callback != undefined ? this.params_set_callback() : undefined;
		var debug_this = 0;
		if (debug_this > 0) pr(this.param_data, "XtremeRoute::__set_params(param vals)");

		var param_keys = Object.keys(this.params);
		for (var i = 0; i < param_keys.length; i++) {
			if (!this.param_data[i]) continue;
			if (this.params[param_keys[i]].post_init) continue; // was dynamically set and won't be in route str.
			if (this.params[param_keys[i]].url_fragment) this.url.url += C.DS + this.param_data[i];
			// convert bool strings to bools proper, convert escaped bool strings to basic strings
			if (this.param_data[i] === "true") this.param_data[i] = true;
			if (this.param_data[i] === "\true") this.param_data[i] = "true";
			if (this.param_data[i] === "false") this.param_data[i] = false;
			if (this.param_data[i] === "\false") this.param_data[i] = "false";
			this.params[param_keys[i]].value = this.param_data[i];
		}

		if (this.params_set_callback) {
			if (debug_this > 1) pr("Executing params_set callback.", "XtremeRoute::__set_params()");
			if (debug_this > 2) pr(this.params, "XtremeRoute::__set_params()");
			this.params_set_callback();
		}

	},


	/**
	 * read() returns param value if it is found in the params attr.
	 * @param param_str
	 * @returns {*}
	 */
	read: function (param_str) {
		var debug_this = 0;
		if (debug_this > 0) pr(param_str, "XtremRoute::read(param_str)");
		if (param_str in this.params) {
			if (debug_this > 1) pr("param_str found in this.params", "XtremeRoute::read()");
			return this.params[param_str].value;
		} else {
			return null;
		}
	},


	set: function (attr, value) {
		var debug_this = 1;
		if (debug_this > 0) pr({attr: attr, value: value}, "XtremeRoute::set(attr, value)");
		if (attr in this) {
			if (debug_this > 1) pr(attr + " in 'this'");
			switch (attr) {
				case "url":
					this.url = {url: false, type: false, defer: false};
					break;
				case "launch_callback":
					$(this).off(C.ROUTE_LAUNCHED);
					this.launch_callback = false;
					break;
				case 'modal':
					this.set_modal(value);
					break;
			}
			return this[attr];
		}
	},


	/**
	 * set_callback() set a callback post-initialization and re-init
	 * @param callback
	 * @param callback_function
	 * @returns {*}
	 */
	set_callback: function (callback, callback_function) {
		switch (callback) {
			case "launch":
				$(this).off(C.ROUTE_LAUNCHED);
				this.launch_callback = callback_function;
				break;
			case "params_set":
				this.params_set_callback = callback_function;
				break;
		}
//		this.init()
	},


	set_deferral_data: function (data) { this.deferral_data = data},


	/**
	 * set_url() setter for url attr; can provide a string URL or full/partially complete url data hash
	 * @param url_hash
	 */
	set_url: function (url_hash) { for (var key in url_hash) this.url[key] = url_hash[key] },


	/**
	 *
	 * @returns {boolean|*}
	 */
	stop_propagation: function () {
		// if execute from jQuery.trigger() event will be an empty object
		if (this.__stop_propagation && isEvent(this.trigger.event) ) {
			this.trigger.event.stopPropagation();
		}
		return this.__stop_propagation;
	},


	/**
	 * toString() re-implementing prototype method
	 * @returns {string}
	 */
	toString: function () { return "XtremeRoute[" + this.route_name + "]"; },


	/**
	 * unset() safely unsets instance attrs
	 * @param attr
	 * @returns {*}
	 */
	unset: function (attr) {
		if ( is_array(attr) ) {
			for (var i in attr) {
				this.unset(attr[i]);
			}
			return;
		}
		if (in_array(attr, ["launch", "params_set", "post_init"])) attr += "_callback";
		// todo: make this recursive one day so you can unset url.url.etc.
		if (attr in this) {
			switch (attr) {
				case "url":
					this.url = {url: false, type: false, defer: false};
					break;
				case "launch_callback":
					$(this).off(C.ROUTE_LAUNCHED);
					this.launch_callback = false;
					break;
				case 'modal':
					this.modal = false;
					this.modal_content = false;
					break;
			}
			return this[attr];
		}
		return false;
	},


	write: function (path, value, context) {
		if (!context) context = this;
		path = path.split(".");
		if (path[0] in context) {
			if (path.length > 1) {
				var context = context[path[0]]
				return this.write(path.slice(1).join("."), value, context);
			} else {
				context[path[0]] = value;
				return true;
			}
		}
		return false;
	}
}