/**
 * Created by jono on 12/30/14.
 */

function XtremeRoute(data) {
	this.initialized_ok = false;

	// data
	this.modal = false;
	this.url = false;
	this.params = false;

	// inferred data
	this.modal_content = false;
	this.param_count = 0;

	// callbacks
	this.params_set_callback = false;
	this.launch_callback = false;
	this.post_init_callback = false;

	// behaviors
	this.stash = false;
	this.overlay = false;
	this.stop_propagation = false;

	this.__set_params = function(param_values) {
		if (param_values.length > 0) {
			var param_keys = Object.keys(this.params);
			for (var i = 0; i < param_keys.length; i++) {
				if (this.params[param_keys[i]].is_url) this.url += C.DS + param_values[i]
				this.params[param_keys[i]].value = param_values[i];
			}
			if (this.params_set_callback) this.params_set_callback();
		}
		return true;
	}

	this.init = function(data) {
		if ("modal" in data) {
			this.modal = data.modal;
			this.modal_content = data.modal + "-content";
		}
		if ("url" in data) this.url = data.url;
		if ("params" in data) this.params = data.params;
		switch ("behavior" in data ? data.behavior : false) {
			case C.STASH:
				this.stash = true;
				this.overlay = false;
				this.stop_propagation = false;
				break;
			case C.OL:
				this.stash = false;
				this.overlay = true;
				this.stop_propagation = false;
				break;
			case C.STASH_OL:
				this.stash = true;
				this.overlay = true;
				this.stop_propagation = false;
				break;
			case C.STOP:
				this.stash = false;
				this.overlay = false;
				this.stop_propagation = true;
				break;
			case C.STASH_STOP:
				this.stash = true;
				this.overlay = false;
				this.stop_propagation = true;
				break;
			case C.OL_STOP:
				this.stash = false;
				this.overlay = true;
				this.stop_propagation = true;
				break;
			case C.STASH_OL_STOP:
				this.stash = true;
				this.overlay = true;
				this.stop_propagation = true;
				break;
			default:
				this.stash = false;
				this.overlay = false;
				this.stop_propagation = false;
				break;
		}

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

	this.init_instance = function(param_values) {
		pr(param_values, "XtremeRoute.init_instance(param_values)");
		if (this.launch_callback) $(this).on("route_launched", this.launch_callback);
		if (param_values) this.__set_params(param_values);
	}
	this.initialized_ok = this.init(data);
	return this;
}