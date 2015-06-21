/**
 * Created by jono on 12/30/14.
 */

function XtremeRoute(name, data) {
	this.route_data = data;
	// data
	this.route_name = "";
	this.request = "";

	this.trigger = {event: false, element: false};
	this.modal = false;
	this.url = {url: false, type: false, defer: false};
	this.params = false;
	this.deferal_data = false;

	// inferred data
	this.modal_content = false;

	// callbacks
	this.params_set_callback = false;
	this.launch_callback = false;
	this.post_init_callback = false;

	// behaviors
	this.stash = false;
	this.overlay = false;
	this.__stop_propagation = false;

	/**
	 * class initiation
	 *
	 * @param name
	 * @param data
	 * @returns {boolean}
	 * @private
	 */
	this.__init = function (request, name, e) {
		this.trigger = {event: e, element: e.currentTarget};
		this.request = request.join(C.DS);
		data = this.route_data;
		var debug_this = 0;
		if (debug_this > 0) pr([name, data], this.__debug("init", ["name", "data"]));
		this.route_name = name;
		if ("modal" in data) {
			this.modal = data.modal;
			this.modal_content = data.modal + "-content";
		}
		if ("url" in data) { this.set_url(data.url) }

		if ("params" in data) {
			this.params = {};
			if (is_array(data.params)) {
				for (var i = 0; i < data.params.length; i++) {
					this.params[data.params[i]] = {value: false, url_fragment: false, post_init: false};
				}
			} else {
				for (var param in data.params) {
					this.params[param] = {value: false, url_fragment: false, post_init: false};
					if ("value" in data.params[param]) this.params[param].value = data.params[param].value;
					if ("url_fragment" in data.params[param]) this.params[param].url_fragment = data.params[param].url_fragment === true;
					if ("post_init" in data.params[param]) this.params[param].post_init = data.params[param].post_init === true;
				}
			}
		}

		this.__set_behavior("behavior" in data ? data.behavior : false);

		if ("callbacks" in data) {
			if ("post_init" in data.callbacks) this.post_init_callback = data.callbacks.post_init
			if ("params_set" in data.callbacks) this.params_set_callback = data.callbacks.params_set
			if ("launch" in data.callbacks) {
				this.launch_callback = data.callbacks.launch;
				$(this).on("route_launched", this.launch_callback);
			}
		}
		if (this.post_init_callback) this.post_init_callback();

		return true;
	}

	/**
	 * __debug() gives method string for printing to console
	 * @param method_str
	 * @param args
	 * @returns {string}
	 * @private
	 */
	this.__debug = function (method_str, args) {
		if (!!args) {
			if (is_array(args)) args = args.join(", ");
			return this.toString() + "::" + method_str + "(" + args + ")"
		} else {
			return this.toString() + "::" + method_str + "#"
		}
	}

	this.__set_behavior = function (behavior_mask) {
		var debug_this = 0;
		if (debug_this > 0) pr(behavior_mask, "Route::__set_behavior(behavior_mask)", 2);
		switch (behavior_mask) {
			case C.STASH:
				this.stash = true;
				this.overlay = false;
				this.__stop_propagation = false;
				break;
			case C.OL:
				this.stash = false;
				this.overlay = true;
				this.__stop_propagation = false;
				break;
			case C.STASH_OL:
				this.stash = true;
				this.overlay = true;
				this.__stop_propagation = false;
				break;
			case C.STOP:
				this.stash = false;
				this.overlay = false;
				this.__stop_propagation = true;
				break;
			case C.STASH_STOP:
				this.stash = true;
				this.overlay = false;
				this.__stop_propagation = true;
				break;
			case C.OL_STOP:
				this.stash = false;
				this.overlay = true;
				this.__stop_propagation = true;
				break;
			case C.STASH_OL_STOP:
				this.stash = true;
				this.overlay = true;
				this.__stop_propagation = true;
				break;
			default:
				this.stash = false;
				this.overlay = false;
				this.__stop_propagation = false;
				break;
		}
		return true;
	}


	/**
	 * __set_params() readies instance with param data pulled from data-route attr of initiating html element
	 * @param param_values
	 * @returns {boolean}
	 * @private
	 */
	this.__set_params = function (param_values) {
		var debug_this = 0;
		if (debug_this > 0) pr(param_values, "XtremeRoute::__set_params(param vals)");
		if (param_values.length > 0) {
			var param_keys = Object.keys(this.params);
			for (var i = 0; i < param_keys.length; i++) {
				if (!param_values[i]) continue;
				if (this.params[param_keys[i]].post_init) continue; // was dynamically set and won't be in route str.
				if (this.params[param_keys[i]].url_fragment) {
					if (!this.url_append(param_values[i]) && debug_this > 1) {
						pr({
								param: param_keys[i],
								value: param_values[i],
								params: this.params,
								values: param_values},
							"Route::url_append() failed", true);
					}
				}
				// convert bool strings to bools proper, convert escaped bool strings to basic strings
				if (param_values[i] === "true") param_values[i] = true;
				if (param_values[i] === "\true") param_values[i] = "true";
				if (param_values[i] === "false") param_values[i] = false;
				if (param_values[i] === "\false") param_values[i] = "false";
				this.params[param_keys[i]].value = param_values[i];
			}
		}

		if (this.params_set_callback) {
			if (debug_this > 1) pr("Executing params_set callback.", "XtremeRoute::__set_params()");
			if (debug_this > 2) pr(this.params, "XtremeRoute::__set_params()");
			this.params_set_callback();
		}

		return true;
	}

	/**
	 * init() instance initiation
	 * @param param_values
	 */
	this.init = function (param_values) {
		$(this).off();
		var debug_this = 0;
		if (debug_this > 0) pr(param_values, "XtremeRoute::init_instance(param_values)");
		this.route_name = "*" + this.route_name + "[" + new Date().getTime() + "]";
		if (this.launch_callback) $(this).on("route_launched", this.launch_callback);
		if (param_values) this.__set_params(param_values);
		return this;
	}


	/**
	 * add_param()
	 * @param name
	 * @param value
	 * @param url_fragment
	 * @returns {boolean}
	 */
	this.add_param = function (name, value, url_fragment) {
		this.params[name] = {
			value: value != "undefined" ? value : false,
			url_fragment: url_fragment === true,
			post_init: true
		};
		return true;
	}


	this.change_behavior = function (behavior_mask) { this.__set_behavior(behavior_mask); }


	/**
	 * read() returns param value if it is found in the params attr.
	 * @param param_str
	 * @returns {*}
	 */
	this.read = function (param_str) {
		var debug_this = 0;
		if (debug_this > 0) pr(param_str, "XtremRoute::read(param_str)");
		if (param_str in this.params) {
			if (debug_this > 1) pr("param_str found in this.params", "XtremeRoute::read()");
			return this.params[param_str].value;
		} else {
			return null;
		}
	};


	this.set = function (attr, value) {
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
	}


	/**
	 * set_callback() set a callback post-initialization and re-init
	 * @param callback
	 * @param callback_function
	 * @returns {*}
	 */
	this.set_callback = function (callback, callback_function) {
		switch (callback) {
			case "launch":
				$(this).off(C.ROUTE_LAUNCHED);
				this.launch_callback = callback_function;
				break;
			case "params_set":
				this.params_set_callback = callback_function;
				break;
		}
		return this.init();
	}


	this.set_deferal_data = function (data) { this.deferal_data = data};


	/**
	 * set_modal() setter for modal attr
	 * @param modal
	 */
	this.set_modal = function (modal) {
		this.modal = modal;
		this.modal_content = modal + "-content";
	};


	/**
	 * set_url() setter for url attr; can provide a string URL or full/partially complete url data hash
	 * @param url_hash
	 */
	this.set_url = function (url_hash) {
		try {
			this.url.url = 'url' in url_hash ? url_hash.url : false;
			this.url.type = "type" in url_hash ? url_hash.type : C.GET;
			this.url.defer = "defer" in url_hash ? url_hash.defer : false;
		} catch(e) {
			throw "Invalid url_hash supplied to Route.set_url:\n\t [" + e +"]";
		}
	}

	/**
	 *
	 * @returns {boolean|*}
	 */
	this.stop_propagation = function () {
		if (this.__stop_propagation) this.trigger.event.stopPropagation();
		return this.__stop_propagation;
	}


	/**
	 * toString() re-implementing prototype method
	 * @returns {string}
	 */
	this.toString = function () { return "XtremeRoute[" + this.route_name + "]";}


	/**
	 * url_append() maintains internal url attr structure when working with an instance
	 * @param fragment
	 * @returns {boolean}
	 */
	this.url_append = function (fragment) {
		var debug_this = 0;
		if (debug_this > 0) pr(fragment, "Route::url_append(fragment)");
		if (typeof(fragment) == "string") {
			if (debug_this > 1) pr("fragment length > 0; proceeding", "Route::url_append()");
			if (typeof(fragment) == "string") {
				if (debug_this > 1) pr("this.url.url length > 0; appending", "Route::url_append()");
				this.url.url += C.DS + fragment;
			} else {
				if (debug_this > 1) pr("this.url.url length didn't exist; creating", "Route::url_append()");
				this.url.url = fragment;
			}
			return true;
		}
		return false;
	}


	/**
	 * unset() safely unsets instance attrs
	 * @param attr
	 * @returns {*}
	 */
	this.unset = function (attr) {
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
					break;
			}
			return this[attr];
		}
		return false;
	};


	this.write = function (path, value, context) {
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

	return this;
}