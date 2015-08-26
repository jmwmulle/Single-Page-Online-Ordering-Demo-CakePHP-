$.fn.scrollTo = function (target, options, callback) {
	if (typeof options == 'function' && arguments.length == 2) {
		callback = options;
		options = target;
	}
	var settings = $.extend({
		scrollTarget: target,
		offsetTop: 50,
		duration: 500,
		easing: 'swing'
	}, options);
	return this.each(function () {
		var scrollPane = $(this);
		var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
		var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
		scrollPane.animate({scrollTop: scrollY }, parseInt(settings.duration), settings.easing, function () {
			if (typeof callback == 'function') {
				callback.call(this);
			}
		});
	});
}

function array_remove (array_ob, remove_from, remove_to)  {
	  var rest = array_ob.slice((remove_to || remove_from) + 1 || array_ob.length);
	  array_ob.length = remove_from < 0 ? array_ob.length + remove_from : remove_from;
	  return array_ob.push.apply(array_ob, rest);
}

/**
 * sprintf() method for String primitive that I jacked from stack overflow and then lost the URL to
 */
if (!String.prototype.format) {
	String.prototype.format = function () {
		var args = arguments;
		return this.replace(/{(\d+)}/g, function (match, number) {
			return typeof args[number] != 'undefined' ? args[number] : match ;
		});
	};
}

/**
 * toTitleCase method for String primitive
 */
if (!String.prototype.toTitleCase) {
	String.prototype.toTitleCase = function () {
		return this.replace(/\w\S*/g, function (txt) {return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	};
}

	/**
	 * isEvent method
	 *
	 * @desc Checks for presence of all required attributes of the W3C Specification for an event; returns true if found
	 *       see: http://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-Event
	 * @param obj
	 * @returns {boolean}
	 */
	function isEvent(obj, message) {
		if (message) {
			pr(obj, "isEvent:" + message);
		}

		if (typeof(obj) !== "object") {
			return false;
		}
		var w3cKeys = ["bubbles", "cancelable", "currentTarget", "eventPhase", "timeStamp", "type"];
		for (i in w3cKeys) {
			if (!(w3cKeys[i] in obj)) {
				return false;
//			pr(w3cKeys[i],"isEvent Failed key");
			} else {
				return true;
			}
		}
	}

	function is_object(obj) { return typeof obj === 'object' && obj != null;}

	function is_array(obj) { return obj instanceof Array; }

	function isFloat(n) { return n === +n && n !== (n | 0); }

	function isInt(n) { return n === +n && n === (n | 0); }

	function is_string(obj) { return typeof(obj) === 'string' }

	/**
	 * isFunction method
	 *
	 * @desc From Alex Grande, http://stackoverflow.com/questions/5999998/how-can-i-check-if-a-javascript-variable-is-function-type
	 * @param obj
	 * @returns {boolean}
	 */
	function is_function(obj) {
		return typeof(obj) === "function";
		var getType = {};
		return obj && getType.toString.call(obj) === '[object Function]';
	}

	/**
	 * isValidJSON method
	 * @param string
	 * @returns {*}
	 */
	function isValidJSON(string, parseIf) {
		try {
			var o = JSON.parse(string);
			/* Handle non-exception-throwing cases:
			 Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
			 but... JSON.parse(null) returns 'null', and typeof null === "object",
			 so we must check for that, too.
			 - Matt H, http://stackoverflow.com/questions/3710204/how-to-check-if-a-string-is-a-valid-json-string-in-javascript-without-using-try */
			if (o && typeof o === "object" && o !== null) {
				return parseIf === true ? o : true;
			}
		}
		catch (e) {
		}

		return false;
	}


	/**
	 * createCustomEvent method
	 *
	 * @desc Creates custom jQuery events with standard W3C properties
	 * @param eName
	 * @param eProperties
	 * @returns {*}
	 */
	function createCustomEvent(eName, eProperties) {
		var defaultProps = {"bubbles": true, "cancelable": false, "eventPhase": 0, "type": eName};
		if (typeof(eProperties) == "object") {
			for (var prop in eProperties) {
				if (eProperties.hasOwnProperty(prop)) {
					defaultProps[prop] = eProperties[prop];
				}
			}
		}
		return jQuery.Event(eName, defaultProps);
	}


	function is_mobile() {
		var check = false;
		(function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
		return check;
	}
	/**
	 * eTypeOf method
	 *
	 * @desc Provided argument is an event, returns the event's type attribute (string)
	 * @param e
	 * @returns {type|*}
	 */
	function eTypeOf(e) { return isEvent(e) ? e.type : false}

	function px_to_int(val) { return Number(val.split("px")[0]);}
	/**
	 * as_id method
	 *
	 * @desc
	 * @param selector
	 * @returns {*}
	 */
	function as_id(selector) {
		if (typeof(selector) === "string") {
			return selector.substring(0, 1) === "#" ? selector : "#" + selector;
		}
		return false;
	}

	function camelcase_to_pep8(str) {
		return str_to_lower(str.replace(/\W+/g, '-').replace(/([a-z\d])([A-Z])/g, '$1_$2').replace(/([a-z\d])([0-9]{1,})/g, '$1_$2'));
	}

	function array_intersect(a, b) {
		a = a.sort();
		b = b.sort();
		var ai = bi = 0;
		var result = [];

		while (ai < a.length && bi < b.length) {
			if (a[ai] < b[bi]) {
				ai++;
			} else if (a[ai] > b[bi]) {
				bi++;
			} else {
				result.push(ai);
				ai++;
				bi++;
			}
		}

		return result;
	}


	function as_class(selector) {
		if (typeof(selector) === "string") {
			return selector.substring(0, 1) === "." ? selector : "." + selector;
		}
		return false;
	}

	function sel_to_str(str) {
		try {
			return str.replace("-", " ").replace("_", " ").toTitleCase();
		} catch (e) {
			pr(e, "ERROR:sel_to_str()");
			return str;
		}
	}

	function now() { return new Date().getTime(); }

	function stripCSS(selector) {
		return selector.substring(0, 1) == "." || selector.substring(0, 1) == "#" ? selector.substring(1) : selector;
	}


	function matchWindowHeight(selector, padding) {
		var win_height = window.innerHeight;
		if (Object.prototype.toString.call(padding) === '[object Array]') {
			for (var i = 0; i < padding.length; i++) {
				win_height -= $(padding[i]).innerHeight();
			}
		} else if (typeof(padding) === "int") {
			win_height -= $(padding).innerHeight();
		}
		else if (typeof(padding) === "int") {
			win_height -= padding;
		}
		$(selector).css('min-height', win_height);
		return true;
	}


	function flash(message) {
		if (message == '@clear') {
			$("#" + 'flash-content').html('');
			return true;
		}
		var m =
			'   <div id="flash-message" class="text-center error">' +
				'       <span>' + message + '</span>' +
				'   </div>';
		$("#" + 'flash-content').html(m);
		return true;
	}


	function cakeUrl(controller, action, params, debug) {
		if (params) {
			if (params.constructor === Array) {
				var cakeurl = WWW + DS + APP + DS + controller + DS + action + DS + params.join(DS);
				debug ? pr(cakeurl) : null;
				return cakeurl;
			}
			if (params.constructor === Object) {
				var param_string = '';
				for (p in params) {
					param_string += DS + params[p];
				}
				var cakeurl = WWW + DS + APP + DS + controller + DS + action + param_string;
				debug ? pr(cakeurl) : null;
				return cakeurl;
			}
			var cakeurl = WWW + DS + APP + DS + controller + DS + action + DS + params;
			debug ? pr(cakeurl) : null;
			return cakeurl;
		}
		var cakeurl = WWW + DS + APP + DS + controller + DS + action;
		debug ? pr(cakeurl) : null;
		return cakeurl;
	}

	/**
	 * in_array()
	 * @param needle
	 * @param haystack
	 * @returns {boolean}
	 */
	function in_array(needle, haystack) { return haystack.indexOf(needle) > -1; }


	/**
	 * pr method
	 *
	 * @desc Elaborated print statement for use with Chrome console
	 * @param obj
	 * @param label
	 * @param message_type
	 * @returns {*}
	 */
	function pr(obj, label, message_type) {
		var stacktrace = new Error().stack.split("\n").slice(2);
//		console.log(stacktrace);
		var stack = [];
		var func = "Null";
		var file = "//nowhere";
		var line_no = "-1";
		for (var i = 0; i < stacktrace.length - 1; i++) {
			try {
				func = stacktrace[i].match( /^ at (.*) \(/ )[1];
			} catch (e) {}
			try {
				file = "/" + stacktrace[i].match(/^.*\/\/[a-z.-]*\/(.*):[0-9]*:[0-9]*/)[1];
			} catch (e) {};

			try {
				line_no = stacktrace[i].match(/^.*\/\/[a-z.-]*\/.*:([0-9]*):[0-9]*/)[1];
			} catch (e) {}
			stack.push(func + " in  " + file + ":" + line_no);
		}
		console.log("\nPrinting From: " + stack[0] + "\n");

		var method = message_type == 1 ? "error" : "log";
		label = !!label && typeof(label) === "string" ? "%c " + label + " " : '%c';
		var note_delim = "*";
		var note_delim_length = note_delim.length;
		var label_bg = "rgb(52, 61, 76)";
		var label_border = "rgb(28, 37 40)";
		var ins_color= "rgb(247, 126, 239)";
		switch (message_type) {
			case 1:
				var label_css = "color:rgb(255,0,0); text-transform:uppercase; background-color:rgb(255,245,245); border:1px solid rgb(255,0,0);";
				break;
			case 2:
				var label_left_css = "color:rgb(100,160,175); background-color:"+label_bg+"; border:1px solid rgb(35,55,63); border-right:none;";
				var label_center_css = "color:rgb(100,160,175); background-color:"+label_bg+"; border:1px solid rgb(35,55,63); border-left:none, border-right:none;";
				var label_right_css = "color:rgb(100,160,175); background-color:"+label_bg+"; border:1px solid rgb(35,55,63); border-left:none;";
				var label_ins_replace = label.replace("[", "__LBRAC__");
				var label_ins_replace = label_ins_replace.replace("]", "__RBRAC__");
				var label_ins_replace = label_ins_replace.replace(/__LBRAC__(.*)__RBRAC__/, "[%c$1%c]");
				var instances = false;
				var internal_to_method = false;
				if (label != label_ins_replace) {
					label =  label_ins_replace;
					instances = true;
				}
				label = label.replace(/\((.*)\)/, "(%c$1%c) %c");
				var meth_label = label.replace(/(#)/, "%c#%c %c");
				if (meth_label != label) {
					internal_to_method = true;
					label = meth_label;
				}
				break;
			default:
				var label_css = "color:rgb(0,150,0); text-transform:uppercase; background-color:rgb(245,245,245); border:1px solid rgb(220,220,220);";
			}
			var type_css = "color:rgb(200,200,200); font-style:italics; display:inline-block; width: 12px; min-width:12px; max-width:12px;";
			var num_css = "color:rgb(0,0,100);";
			var bool_css = "color:rgb(225,125,80); font-weight: bold";
			var str_css = "color:rgb(125,125,125); font-family:arial";
			var note_css = "color:#008cba; background-color:rgb(247,247,247); border:1px solid #008cba;";
			var arg_css = "color:rgb(252,122,0); background-color:"+label_bg+"; border-top:1px solid rgb(35,55,63); border-bottom:1px solid rgb(35,55,63);";
			var ins_css = "color:"+ins_color+"; background-color:"+label_bg+"; border-top:1px solid rgb(35,55,63); border-bottom:1px solid rgb(35,55,63);";
			var msg_css = "color:rgb(252,240,244); background-color:"+label_bg+"; border-top:1px solid rgb(35,55,63); border-bottom:1px solid rgb(35,55,63);";
			var debug = message_type == 2 || message_type == 3;
			if (debug) {
				switch (obj) {
					case obj === false:
						label += "%s";
						obj = "false";
						break;
					case obj === true:
						label += "%s";
						obj = "true";
						break;
					case obj === null:
						label += "%s"
						obj = "null"
						break;
					case typeof(obj) === 'undefined':
						label += "%s"
						obj = "undefined"
						break;
					case typeof(obj) === 'number':
						break;
					default:
						label += "%O";
				}
				if ( instances) {
				console[method](label, label_left_css, ins_css, label_center_css, arg_css, label_right_css, msg_css, obj);
				} else {
					console[method](label, label_left_css, arg_css, label_right_css, msg_css, obj);
				}
			} else {
			switch (obj) {
				case obj === 0:
					console[method](label + "%c(int) %c", label_css, type_css, num_css, 0);
					break;
				case obj === 1:
					console[method](label + "%c(int) %c%s", label_css, type_css, num_css, 1);
					break
				case obj === false:
					console[method](label + "%c(bool) %c%s", label_css, type_css, bool_css, "false");
					break;
				case obj === true:
					console[method](label + "%c(bool) %c%s", label_css, type_css, bool_css, "true");
					break;
				case obj === null:
					console[method](label + "%c(!def) %c%s", label_css, type_css, bool_css, "null");
					break;
				case typeof(obj) === 'undefined':
					console[method](label + "%c(!def) %c%s", label_css, type_css, bool_css, "undefined");
					break;
				case typeof(obj) === 'string':
					if (obj.substring(0, note_delim_length) === note_delim) {
						console[method](label + "%c%s", label_css, note_css, " " + obj.substring(1) + " ");
					} else {
						console[method](label + "%c(str) %c%s", label_css, type_css, str_css, obj);
					}
					break;
				case typeof(obj) === 'number':
					obj % 1 === 0 ? console[method](label + "%c(int) %c%s", label_css, type_css, num_css, obj) : console[method](label + "%c(float) %c%s", label_css, type_css, num_css, obj);
					break;
				default:
					console[method](label + "%c(obj) %O", label_css, type_css, obj);
			}
		}



		return null;
	}


	/**
	 * tr method
	 * @desc used for providing a quick and dirty stack trace
	 */
	function tr(args) { pr(args, "err", true); }


	/**
	 * split_path method
	 *
	 * @desc Splits a path by <separator> or else forward-slash, striping first trailing and leading slash if found
	 * @param path
	 * @param separator
	 * @returns {*}
	 */
	function split_path(path, separator) {
		if (typeof(path) != "string") return false;
		// clear trailing & leading slashes
		path = path.substr(-1, 1) === separator ? path.substring(0, path.length - 1) : path;
		path = path.substr(0, 1) === separator ? path.substring(1) : path;

		path = path.replace('\\' + separator, "$DIRECTORY_SEPARATOR").split(separator);
		for (var i = 0; i < path.length; i++) {
			path[i].replace("$DIRECTORY_SEPARATOR", separator);
		}

		return path;
	}


	function ucfirst(stringName) {
		return stringName.charAt(0).toUpperCase() + stringName.slice(1).toLowerCase();
	}

	function str_to_lower(stringName) {
		return stringName.charAt(0).toLowerCase() + stringName.slice(1).toLowerCase();
	}

	function str_to_upper(string_name) {
		return string_name.toUpperCase();
	}

	function camel_to_snake(str) {
		return str.replace(/([A-Z])/g, function($1){return "_"+$1.toLowerCase();});
	}

	function title_to_snake(str) {
		str = str_to_lower(str[0]) + str.slice(1);
		return str.replace(/([A-Z])/g, function($1){return "_"+$1.toLowerCase();});
	}

	function title_case(string_name) {
		string_name = string_name.replace('_', ' ').split(' ');
		for (var i = 0; i < string_name.length; i++) string_name[i] = ucfirst(string_name[i]);
		return string_name.join(' ');
	}

	function flip(bool) {
		if (bool === 0 || bool === 1) {
			return bool == 0;
		}

		if (bool === false || bool === true) {
			return bool == false;
		}

		if (bool === '0' || bool === '1') {
			return bool == '0';
		}

		if (bool === 'true' || bool === 'false') {
			return bool == 'false';
		}

		if (bool === 'True' || bool === 'False') {
			return bool == 'False';
		}

		if (bool === null || bool === 'undefined') {
			return "NULL_NOT_BOOL";
		}

		return "BOOLEAN_NOT_PASSED";
	}

	function integer_keys(obj, as_array) {
		if ( !is_object(obj) ) return false;
		if ( is_array(obj) ) return obj
		var new_obj = {}
		for (var key in obj) {
			try {
				new_obj[Number(key)] = obj[key];
			} catch(Exception) {
				new_obj[key] = obj[key];
			}
		}
		return new_obj;
	}

	function obj_values(obj, from, to) {
		var return_array = [];
		var count = -1;
		if ( !from ) from == 0;
		if ( from < 0 ) from = obj.length - Math.abs(from);
		if (!to) to == obj.length;
		if ( to < 0 ) to = obj.length - Math.abs(from);
		if (to > from) throw("ValueError: end index cannot precede start index");

		for (var key in obj) {
			count++;
			if (count >= from && count <= to) return_array.push(obj[key]);
		}
		return return_array;
	}

	function exists(varName) {
		return jQuery(varName).length > 0;
	}


	function clearForm(formId) {
		$("#" + formId)[0].reset();
	}

	function strpad(padstr, pad_length, padchar, direction, decimal_to) {
		if (typeof(padstr) == "number") padstr = padstr.toString();
		padchar = padchar ? padchar : "0";
		if (decimal_to) {
			var post_decimal = padstr.split(".")[1];
			if (post_decimal && post_decimal.length > 0) {
				padstr += padstr.substring(0, 1) != "." ? "." : null;
				for (var i = 0; i < decimal_to; i++) {
					padstr += toString(0);
				}
			}
		}
		var padchar_count = pad_length - padstr.length;
		if (padchar_count < 1) {
			return padstr;
		}
		var final = '';
		if (direction !== -1) {
			for (var j = 0; j < padchar_count; j++) {
				final += padchar;
			}
			return final + padstr;
		} else {
			for (var j = 0; j < padchar_count; j++) {
				padstr += padchar;
			}
			return padstr;
		}
	}


	/**
	 * form-reset listener
	 *
	 * @description Any button with the id 'reset-form' will reset the form indicated by it's data-formid attribute
	 */
	$("#" + "reset-form").click(function () {
		var refresh = $(this).attr('data-refresh');
		var formId = $(this).attr('data-formid');

		if (refresh == false) {
			clearForm();
		} else {
			location.reload();
		}
	});

	function copy(obj) {
		var new_obj = null;
		if (is_array(obj) ) {
			new_obj = Array();
		} else if (typeof(obj) === "string") {
			return "" + obj;
		} else if (is_object(obj) ) {
			new_obj = {};
		} else if (isInt(obj) || isFloat(obj) ) {
			return 0 + obj
		} else if (obj === true || obj === false) {
			return obj === true;
		} else {
			return null;
		}

		for (var i in obj) { new_obj[i] = obj[i] }
		for (var i in obj.prototype) { new_obj.prototype[i] = obj.prototype[i] }
		return new_obj
	}

	function obj_len(object) {
		var size = 0, key;
		for (key in object) {
			if (object.hasOwnProperty(key)) size++;
		}
		return size;
	}


	function b64JSON(jsobject) {
		return btoa(JSON.stringify(jsobject));
	}

	function die() {
		function ExitRequest(){ Error.apply(this, arguments); this.name = "ExitRequest"; }
		ExitRequest.prototype = Object.create(Error.prototype);
		throw new ExitRequest("Exiting..");
	}

/**
 * Returns an object with all the keys of both objects; values from vals_obj have precedence, otherwise keys_obj values
 * are used.
 *
 * @param keys_obj
 * @param vals_obj
 * @returns {{}}
 */
function obj_merge(keys_obj, vals_obj) {
	if (!is_object(keys_obj) || !is_object(vals_obj)) throw "keys_obj and vals_obj must both be javascript objects";
	var merged_obj = {};
	for (var key in keys_obj) merged_obj[key] = key in vals_obj ? vals_obj[key] : keys_obj[key];
	for (var key in vals_obj) if (!(key in merged_obj)) merged_obj[key] = vals_obj[key];

	return merged_obj;
}



function inherits(object) {
	function F () {};
	F.prototype = object.prototype;
	return new F;
}



/**
 * Created by jono on 8/22/14.
 * (X)treme(S)elector(M)anifest
 *
 */
if ( window.xtr == undefined ) window.xtr = {};

var XSM = {
	global:{
		activizing_list: "ul.activizing li",
		active_list_item: "li.active",
		ajaxLink:".ajax-link",
		available: ".available",
		unavailable: ".unavailable",
		footer: "footer",
		imageQueue: "#image-loading-queue",
		loadingScreen: "#loadingScreen",
		loading: "#topbar h4.loading",
		multi_activizing: ".multi-activizing",
		page_content: "#page-content",
		preserve_aspect_ratio: ".preserve-aspect-ratio",
		route: "*[data-route]",
		onchange_route: "*[data-changeroute]",
		store_status: "#store-status",
		delivery_status: "#delivery-status",
		unknown_status: "#delivery-status",
	},
	effects: {
		active: "active",
		activizing: "activizing",
		active_by_default: "default",
		breakout: ".breakout",
		checked: "icon-checked",
		cancel: "cancel",
		active_plus: "active-plus",
		detach: "detach",
		disabled: "disabled",
		enabled: "enabled",
		exposed: "exposed",
		fade_out: "fade-out",
		fastened: "fastened",
		fill_parent: ".fill-parent",
		flipped_x: "flipped-x",
		flipped_y: "flipped-y",
		float_label: "float-labeled",
		hidden: "hidden",
		inactive: "inactive",
		inelligible: "inelligible",
		launching: "launching",
		loading: "loading",
		lr_only: "lr-only",
		max_of_type: "max-of-type",
		overlay: "overlay",
		pressed: "pressed",
		slide_right: "slide-right",
		register_icon: "icon-orb-card-register",
		register_reveal: "icon-orb-card-register-reveal",
		secondary:"secondary",
		share_icon: "icon-orb-card-share",
		share_icon_reveal: "icon-orb-card-share-reveal",
		slide_down: "slide-down",
		slide_left: "slide-left",
		slide_up: "slide-up",
		solidify: "solidify",
		stash: "stash",
		success: "success",
		swap_width: "swap-width",
		transitioning: "transitioning",
		true_hidden: "true-hidden",
		unchecked: "icon-unchecked"
	},
	forms: {
		orb_order_form: "#orderOrbForm",
		order_address_form: "#orderAddressForm",
		users_form: "#UsersForm"
	},
	load: {
		dismissLSButton: "#dismiss-loading-screen",
		pizzaLoaderGIF: "#pizza-loader-gif",
		loadingMessage: "#loading-message",
		readyMessage: "#ready-message"
	},
	menu: {
		active_orb_name_3d_context: "#active-orb-name-3d-context",
		active_orb_name_front_face: "#active-orb-name-front-face",
		active_orb_name_back_face: "#active-orb-name-back-face",
		active_orbcat_menu_header: "#active-orbcat-menu-header",
		active_orb: ".active-orbcat-item.active",
		active_orbcat_item: ".active-orbcat-item",
		active_orbs_menu_item: "#active-orbs-menu li a",
		add_to_cart:".add-to-cart",
		cancel_order_button: "#cancel-order-button",
		confirm_order_button: "#confirm-order-button",
		double: ".double",
		full: ".full",
		left_side: ".left-side",
		right_side: ".right-side",
		float_label: "#float-label",
		inactive_sauce: ".orb-opt.sauce.inactive",
		sauce: ".orb-opt.sauce",
		orb_card_back: "#orb-card-back",
		ord_card_back_face: ".ord-card-back-face",
		orb_card_content_container: ".orb-card-content-container",
		orb_card_row_1: "#orb-card-row-1",
		orb_card_row_2: "#orb-card-row-2",
		orb_card_row_3: "#orb-card-row-3",
		orb_card_stage: "#orb-card-stage",
		orb_card_stage_menu: "#orb-card-stage-menu",
		orb_card_stage_menu_wrapper: "#orb-card-stage-menu-wrapper",
		orb_card_3d_context: "#orb-card-3d-context",
		orb_card_wrapper: "#orb-card-wrapper",
		orb_order_form: "#orderOrbForm",
		orb_order_form_orb_id: "#OrderOrbId",
		orb_order_form_orb_uid: "#OrderOrbUid",
		orb_order_form_inputs: "#orderOrbForm input",
		orb_order_form_orb_opts: "#orderOrbForm input.orb-opt-weight",
		orb_order_form_price_rank: "#OrderOrbPriceRank",
		orb_order_form_note: "#OrderOrbNote",
		orb_order_form_quantity: "#OrderOrbQuantity",
		orb_size_button: ".orb-size-button",
		orbcat_menu_title_header: "#orbcat-menu-title h1",
		orbcat_menu_title_subtitle: "#orbcat-menu-title h1 span",
		orbcat_menu: "#orbcat-menu",
		orb_opt: ".orb-opt",
		orb_opt_active: ".orb-opt.active",
		orb_opt_container: "#orb-opts-container",
		orb_opt_icon: ".orb-opt-coverage",
		orb_opt_icon_active: ".orb-opt-coverage.active",
		orb_opt_filter: ".orb-opt-filter",
		orb_opt_filters: "#orb-opt-filters",  // marked for deletion
		optflag_filter_header: "#optflag-filter-header",
		orb_opt_filter_span: ".orb-opt-filter span",
		orb_opt_filter_span_checked: ".orb-opt-filter span.icon-checked",
		orb_opt_filter_span_unchecked: ".orb-opt-filter span.icon-unchecked",
		orb_opt_filter_all: ".orb-opt-filter-all",
		orb_opt_weight: ".orb-opt-weight",
		orb_opts_menu_header: "#orb-opts-menu-header",
		registration_panel: "#orb-card-register-panel",
		register_button: "#register.orb-card-button",
		share_button: "#like.orb-card-button",
		self:"main#menu",
		social_panel: "#orb-card-social-panel",
		tiny_orb_opts_list: "#tiny-orb-opts-list",
		tiny_orb_opts_list_wrapper:"#tiny-orb-opts-list-wrapper",
		tiny_orb_opts_list_item: ".tiny-orb-opts-list-item",
		user_activity_panel: "#user-activity-panel",
		user_activity_panel_items: "#user-activity-panel li"
	},
	modal: {
		button: ".modal-button",
		close_modal: "#close-modal",
		confirm_address_login_panel: "#confirm-address-login-panel",
		default_content: ".default-content",
		deferred_content: ".deferred-content",
		flash: "#flash-modal",
		finalize_order_button: "#finalize-order-button",
		on_close: "#on-close",
		orb_card:"#orbcard-modal",
		overlay: "#modal-overlay-container",
		payment_method_input: "#OrderPaymentMethod",
		primary: "#primary-modal",
		primary_content: "#primary-modal-content",
		primary_deferred_content: "#primary-modal-content .deferred-content",
		social: "#social-modal",
		splash: "#splash-modal",
		submit_order_address: "#submit-order-address",
		submit_order_button_wrapper: "#submit-order-button-wrapper"
	},
	splash:{
		self:"#splash",
		bar_contents: "#splash-bar-content",
		content:"#splash > .content",
		circle:"#splash-circle",
		circleWrap:"#splash-circle-wrapper",
		detached: "#splash .detach",
		fastened:"#splash .fastened",
		logo:"#splash-logo",
		logo_wrapper:"#splash-logo-wrapper",
		logoClone:"#splash-logo_fasten-clone",
		menu:"#menu",
		menu_wrapper:"#menu-wrapper",
		menu_spacer:"#menu-wrapper .spacer",
		modal:"#splash-modal",
		modalWrap:"#splash-modal-wrapper",
		modalContent:"#splash-modal .content",
		openingDeal:"grand-opening-deal",
		order:"#order",
		order_delivery: "#splash-order-delivery",
		order_delivery_wrapper: "order-delivery-wrapper",
		order_pickup_wrapper: "order-pickup-wrapper",
		order_pickup: "#splash-order-pickup",
		order_spacer:"#order-wrapper .spacer",
		preserve_aspect_ratio: "#splash *.preserve-aspect-ratio",
		splash_bar:"#splash-bar",
		splash_bar_wrapper:"#splash-bar-wrapper",
		splash_link: ".splash-link"
	},
	topbar: {
		self: "#topbar",
		social_loading: "#social-loading",
		icon_row: "#topbar .icon-row",
		topbar_cart_button: "#top-bar-view-cart",
		hover_text_link: ".topbar-social  a",
		hover_text_label: "#topbar-hover-text-label",
		hover_text_label_incoming: "#topbar-hover-text-label span.incoming",
		hover_text_label_outgoing: "#topbar-hover-text-label span.outgoing"
	},
	vendor: {
		self: "body#vendor",
		back_splash: "#back-splash",
		customer_name: "#customer-name",
		error_pane: "#error-pane",
		food_list: "#food-list",
		next_order: "#next-order",
		new_order_tone: "files/new_order_tone.mp3",
		accept_acknowledge: "#accept-acknowledge",
		order_accept_button: "#order-accept-button",
		order_accept_button_pressed: "#order-accept-button.pressed",
		order_content_detail: "#order-content-detail",
		order_content_sample: "#order-content-sample",
		order_count: "#order-count",
		order_count_bubble: "#pending-orders-count",
		order_reject_confirmation: "#order-reject-confirmation",
		order_address: "#order-title",
		pending_orders_list: "#pending-orders-list"
	},
	vendor_ui: {
		add_orbopt_form: "#OrboptVendorUiForm",
		menu_table: "#menu-table",
		menu_options_tab: "#menu-options-tab",
		menu_tab: "#menu-tab",
		orbopts_table: "#menu-options-table",
		orb_attr_display: "div.orb-attr.display",
		orb_attr_edit: "div.orb-attr.edit",
		orbopt_attr_display: "div.orbopt-attr.display",
		orbopt_attr_edit: "div.orbopt-attr.edit",
		orbopt_add_breakout: "#orbopt-add-breakout",
		orbopt_pricelist_add_breakout: "#orbopt-pricelist-add-breakout",
		orbopt_pricelist_add: "#orbopt-pricelist-add",
		orb_add_form: "#OrbAddForm",
		orbopt_config_form_wrapper: ".orbopt-config-form",
		orbopt_optgroup_config_form: "#orbopt-optgroup-config-form",
		orbopt_selection_template: "#orbopt-selection-template",
		price_dict_update_form: ".price-dict-update-form",
		ui_tabs: "#ui-tabs"
	},
	footer: {
		self:"footer#footer"
	},
	generated: {
		vendor_ui_opts_input: function(orb_id) { return "#orb-" + orb_id + "-orbopts"; },
		vendor_ui_opts_config_id: function(orb_id) { return "#orb-" + orb_id + "-orbopts-config" },
		vendor_orb_desc: function(orb_desc, orb_details) {
			var orb_opts = orb_details.opts;
			var orb_str = "<li><ul class='orb'><li> &#8226; ";
			orb_str += "<span class='size'>" + orb_details.size.replace('in','"') + "</span>";
			orb_str += "<span class='title'>" + orb_desc + "</span></li>";
			for (var opt_id in orb_opts) {
				orb_str += "<li class='orb-opt'><span class='text'>" + orb_opts[opt_id].title + "</span>";
				switch ( orb_opts[opt_id].weight ) {
					case "D":
						orb_str += '<span class="icon-double"></span></li>';
						break;
					case "L":
						orb_str += '<span class="icon-left-side"></span></li>';
						break;
					case "R":
						orb_str += '<span class="icon-right-side"></span></li>';
						break;
					default:
						orb_str += '</li>';
				}
			}
			orb_str += "</ul></li>";
			return orb_str;
		},
		vendor_orb_title: function(size, title) {
		},
		order_form_opt_id: function(opt_id) {
			opt_id = opt_id.split("-")[2]
			return as_id("OrderOrbOrbopt" + opt_id)
		},
		orb_card_row_content: function(row) { return "#orb-card-row-" + row + " div.orb-card-content" },
		orb_opt_id: function(opt_id) { return as_id("orb-opt-coverage-" + opt_id); },
		order_address_button: function(context) {
			var classes;
			var route = "confirm_address/submit" + C.DS + context;
			var message;
			var wrapper = '<a href="#" id="submit-order-address" class="'
			var cancel = '<a href="#" class="modal-button lrg bisecting cancel left""';
				cancel += ' data-route="confirm_address/cancel' + C.DS + context +'">';
				cancel += '<span class="icon-circle-arrow-l"></span><span class="text">Cancel</span></a>';

			if (context == "menu") {
				classes = ["modal-button", "lrg", "bisecting", "confirm", "right"];
				message = '<span class="text">OK!</span><span class="icon-circle-arrow-r"></span></a>';
			}
			if (context == "review") {
				cancel = "";
				classes= ["modal-button", "lrg", "full-width", "confirm"];
				message = '<span class="text">BACK TO CHECKOUT!</span><span class="icon-circle-arrow-r"></span></a>';
			}
			wrapper += classes.join(" ") + '" data-route="' + route + '">';
			return cancel + wrapper + message;
		},
	},
	page_name: {
		vendor_ui: "Vendor Interface",
		splash: "Splash Page",
		menu: "Xtreme Menu"
	}
};

var FX = XSM.effects;

/**
 * Created by jono on 12/29/14.
 */
var constants = {
	PX: "px",
	POST: "POST",
	GET: "GET",
	CLK: "click",
	CHANGE: "change",
	CHECK: "check",
	DELIVERY: "delivery",
	PICKUP: "pickup",
	JUST_BROWSING:"just_browsing",
	UNCHECK: "uncheck",
	UNDEF: "undefined",
	MOUSEOVER: "mouseover",
	HOVER: "hover",
	MOUSEENTER: "mouseenter",
	MOUSEOUT: "mouseout",
	HIDE: 0,
	SHOW: 1,
	DS: "/",
	BODY: "body",
	ORDER_SPACER_FACTOR: 0.45,
	MENU_SPACER_FACTOR: 0.15,
	BACK_FACE: "back_face",
	FRONT_FACE: "front_face",
	MENU: "menu",
	DATABASE: "database",
	SESSION: "session",
	UPDATE_DB: "update_database",
	UPDATE_SESSION: "update_session",
	UNSET: -1,
	OPT: "opt",
	CANCEL: "cancel",
	ORB_CARD_REFRESH: "orb_card_refresh",
	ORDER_FORM_UPDATE: "order_form_update",
	ORDER_UI_UPDATE: "order_ui_update",
	ROUTE_LAUNCHED: "route_launched",
	ROUTE_REQUEST: "route_request",
	ORB_ROW_ANIMATION_COMPLETE:"orb_row_animation_complete",
	F: "",
	D: "<span class='icon-double tiny-opt icon-hnj-inline'></span>",
	R: "<span class='icon-right-side tiny-opt icon-hnj-inline'></span>",
	L: "<span class='icon-left-side tiny-opt icon-hnj-inline'></span>",
	ORBCARD: "orbcard",
	PRIMARY: "primary",
	SPLASH: "splash",
	FLASH: "flash",
	WEIGHT: "weight",
	REM: 16,
	STASH:1,
	OL:3,
	STASH_OL:4,
	STOP:5,
	STASH_STOP: 6,
	STOP_OL:8,
	STASH_OL_STOP:9,
	PENDING:0,
	ACCEPTED:1,
	REJECTED:2,
	ACCEPT: "accept",
	REJECT: "reject",
	CUT: "cut",
	FEED: 'feed',
	FEED_CUT: "feed_cut",
	XTREME_TABLET_USER_AGENT: 'xtreme-pos-tablet',
	EPOS_EXCEPTION: "com.epson.eposprint.EposException",
	OPEN: "OPEN",
	CLOSED: "CLOSED",
	DELIVERING: "DELIVERING",
	PICKUP_ONLY: "PICKUP ONLY",
	UNAVAILABLE: "UNAVAILABLE",
	CONFIGURING: "configuring",
	CONFIGURED: "configured"
};

var C = constants;
if ( window.xtr === undefined ) window.xtr = {};

window.xtr.events = {
		route_launched: createCustomEvent("route_launched"),
		orb_card_refresh: createCustomEvent(C.ORB_CARD_REFRESH),
		order_form_update: createCustomEvent(C.ORDER_FORM_UPDATE),
		order_ui_update: createCustomEvent(C.ORDER_UI_UPDATE),
		route_request: createCustomEvent(C.ROUTE_REQUEST),
		orb_row_animation_complete: createCustomEvent(C.ORB_ROW_ANIMATION_COMPLETE),
};

window.xtr.data = {
		debug:true,
		store_status: null,
		host_root_dirs: {
			xDev: "",
			xProd: "",
			xLoc: "/xtreme"
		},
		delays: {
			global_transition: 300,
			default_js_refresh: 30,
			orb_card_row_toggle: 800,
			menu_stash_delay: 900,
			fold_splash: 3000,
			splash_set_order_method: 3900
		},
		cfg: {
			root: null,
			developmentMode: true,
			minLoadTime: 1,
			store_status_inspection_timeout: 61000  // ie. 61 seconds
		}
};


/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.form_validation = function() {
		this.validate_form = {
			params: ['target', 'context', 'delegate_route'],
			callbacks: {
				params_set: function () {
					switch (this.read('target')) {
						case 'address':
							XBS.validation.submit_address(this.read('context'), this.read('delegate_route'));
							break;
					}
				}
			}
		};

		return this;
};
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
/**
 * Created by jono on 8/11/15.
 */
if (window.xtr === undefined) window.xtr = window.xtr;

window.xtr.route_collections.menu_ui = function() {
	this.favorite = {
				params: {context: {value: null, url_fragment: false}},
				url: {url: "favorite"},
				data: false,
				callbacks: {
					modal: XSM.modal.flash,
					params: function () { this.data = $(XSM.menu.orb_order_form).serialize(); },
					launch: function (e, resp) { pr(resp);}
				}
			};
	this.menu = {
				params: ['reveal_method'],
				modal: false,
				url: {url: "menu"},
				callbacks: {
					params_set: function () {
						if (this.read('reveal_method') == 'unstash') {
							this.unset('url');
							this.unset('launch_callback');
							window.xtr.layout.dismiss_modal(XSM.modal.primary);
							setTimeout(function () { window.xtr.menu.unstash();}, window.xtr.data.delays.global_transition);
						}
					},
					launch: function () { window.xtr.splash.fold();}
				}
			};
	this.menuitem = {
		url: {url: "menuitem"},
		params: {orb_id: {value: null, url_fragment: true}}
	};
	this.orbcat =  {
		url: {url: "menu", defer: true},
		params: {
			id: {value: null, url_fragment: true},
			name: {}
		},
		callbacks: {
			launch: function () { window.xtr.menu.refresh_active_orbcat_menu(this.deferral_data); }
		}
	};
	this.orb_opt = {
		params: ["context", "element", "title", "weight"],
		behavior: C.STOP,
		callbacks: {
			params_set: function () {
				switch (this.read('context')) {
					case "form_update":
						if (!this.read('weight')) this.write("params.title.value", title_case(this.read('title')));
						window.xtr.menu.set_orbopt_form_state(this.read('element'), this.read('title'));
						break;
					case "weight":
						var parent_opt = $(this.trigger.element).parents(XSM.menu.orb_opt)[0];
						if ($(parent_opt).hasClass(XSM.effects.active)) {
							this.trigger.event.stopPropagation();
						}
						if ($(this).hasClass(XSM.effects.inelligible)) return;
						orbcard.toggle_orb_opt_icon(this.trigger.element, true);
						break;
					case "opt":
						orbcard.toggle_orb_opt(this.read('element'), true);
						break;
				}
			}
		}
	};
	this.optflag = {
		params: ['target', 'action'],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "filter":
						window.xtr.menu.toggle_optflag(Number(this.read('target')), false)
						break;
				}
			}
		}
	};
	this.load_orb = {
		url: { url: "menu-item", defer: true },
		params:{id:{url_fragment:true} },
		callbacks: {
			launch: function () {
				// Note: response as a deleaget route to configure_orb/id
				window.xtr.routing.cake_ajax_response(this.deferral_data,{
					callback: function(response, id) {
						window.xtr.menu.load_orb(id, response.data.view_data)
					},
					data: { id: this.read('id') }
				}, true, true);
			}
		}
	};
	this.configure_orb = {
		params: ['id', 'rank'],
		callbacks: {
			params_set: function() {
				// populate form with any orb configurations in progress
				window.xtr.orbcard.load_orb_configuration( window.xtr.cart.configure( this.read('id'), this.read('rank') ) );
			},
			launch: function() {
				// flip to backface
				window.xtr.orbcard.show_back_face(this.read('rank'));
			}
		}
	};
	this.orb_card = {
		params: ["action", "action_arg", "data"],
		stop_propagation: true,
		callbacks: {
			params_set: function () {
				var launch = false;
				switch (this.read('action')) {
					case 'add_to_cart':
						switch (this.read('action_arg')) {
							case "confirm":
								this.url = {
									url: "add-to-cart",
									type: C.POST,
									defer: true,
									data: $(XSM.menu.orb_order_form).serialize()
								};
								launch = function () {
									try {
										var data = JSON.parse(this.deferral_data);
										if (data.success == true && window.xtr.cart.add_to_cart()) {
											window.xtr.layout.reveal_orb_card_modal();
										}
									} catch (e) {
										throw "Add to cart failed at server:\n " + this.deferral_data;
									}
								}
								break;
							case 'cancel':
								launch = window.xtr.menu.reset_orb_card_stage();
								break;
						}
						break;
					default:
						launch = function () { window.xtr.menu.toggle_orb_card_row_menu(this.read('action'), null);}
						break;
				}
				if (launch) this.set_callback("launch", launch)
			}
		}
	};

	return this;
};
/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.modal_api = function() {
	this.close_modal = {
			params: ["modal", "on_close"],
			callbacks: {
				launch: function () {
					var action = $(XSM.modal[this.read("modal")]).find(XSM.modal.on_close)[0];
					if (action) {
						window.xtr.layout.dismiss_modal(this.read("modal"), $(action).data('action'));
					} else {
						window.xtr.layout.dismiss_modal(this.read("modal"), false);
					}
				}
			}
		};
	this.orbcard_modal = {
			params: ["action", "target"],
			propagates:false,
			modal: XSM.modal.primary,
			callbacks: {
				params_set: function() {
					window.xtr.orbcard.show_front_face();
					pr(this.p);
					switch ( this.p.action ) {
						case "continue_ordering":
								setTimeout(function () {
									window.xtr.layout.dismiss_modal();
									window.xtr.orbcard.reset_stage();
								}, 900);
							break;
						case "view":
							this.url= {
								url: ["review", this.read('target')].join("-"),
								defer: false,
								type: C.GET
							};
							this.change_behavior(C.STASH_STOP);
							break;
					}
				}
			}
		};

	return this
};
/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.orders_api = function() {
	this.order = {
		params: ["method", "context"],
		url: { url: "review-order", type: C.GET, defer: false},
		modal: XSM.modal.primary,
		callbacks: {
			params_set: function () {
				switch (this.read('method')) {
					case "finalize":
						this.url = {
							url: "orders" + C.DS + "finalize",
							defer: true,
							data: $("#OrderReviewForm").serialize(),
							type: C.POST
						}
						this.set_callback("launch", function () {
							var data = $.parseJSON(this.deferral_data);
							var trigger = this.trigger;
							if (!data.error) {
								if (data.order_id) {
									var route = "pending_order" + C.DS + data.order_id + C.DS + "launching";
									$(window.xtr.router).trigger(C.ROUTE_REQUEST, {
										request: route,
										trigger: trigger.event
									});
								}
							}
						});
						break;
					case "review":
						if (this.read('context') == 'orb_card') {
							this.change_behavior(C.STASH_STOP);
						}
						break;
				}
			},
			launch: function (e, fired_from) {
				if (this.deferral_data) {
					var data = $.parseHTML(this.deferral_data);
					$(XSM.modal.primary).addClass(XSM.effects.slide_down);
					setTimeout(function () {
						var default_content = $(XSM.modal.primary).find(XSM.modal.default_content)[0];
						$(default_content).replaceWith(data);
						$(XSM.modal.primary).hide()
							.removeClass(XSM.effects.slide_down)
							.addClass(XSM.effects.slide_up).show();
						setTimeout(function () {
							$(XSM.modal.primary).removeClass(XSM.effects.slide_up);
						}, 30);
					}, 300);
				} else {
					window.xtr.cart.validate_order_review();
				}
			}
		}
	};
	this.order_accepted = {
					url: "order-accepted",
					modal: XSM.modal.primary
			};
	this.splash_order = {
				params: {method: { value: null, url_fragment: false}},
				callbacks: {
					launch: function () {
						$(XSM.splash.order_delivery).removeClass(XSM.effects.slide_left);
						$(XSM.splash.order_pickup).removeClass(XSM.effects.slide_right);
					}
				}
			};
	this.payment_method = {
				params: ['context', 'action'],
				callbacks: {
					params_set: function () {
						switch (this.read('context')) {
							case "review_modal":
								window.xtr.modal.payment_method(this.read('action'));
								break;
						}
					}
				}
			};
	this.pending_order = {
		params: {
			order_id: {value: null, url_fragment: true},
			status: {value: null, url_fragment: true}
		},
		modal: XSM.modal.primary,
		url: {url: "order-confirmation", defer: true, type: C.POST},
		callbacks: {
			params_set: function () {
				switch (this.read('status')) {
					case "launching":
						this.params.status = {value: true};
						this.url.defer = false;
						var request = {
							request: "pending_order" + C.DS + this.read('order_id') + C.DS + C.PENDING,
							trigger: this.trigger
						};
						setTimeout(function () { $(window.xtr.router).trigger(C.ROUTE_REQUEST, request)}, 3000);
						this.unset("launch_callback");
						break;
				}
			},
			launch: function () {
				var data = $.parseJSON(this.deferral_data);

				switch (Number(data.status)) {
					case C.REJECTED:
						break;
					case C.ACCEPTED:
						$(window.xtr.router).trigger(C.ROUTE_REQUEST, {request: "order_accepted", trigger: this.trigger});
						break;
					default:
						var request = {
							request: "pending_order" + C.DS + this.read('order_id') + C.DS + C.PENDING,
							trigger: this.trigger
						};
						setTimeout(function () {
							$(window.xtr.router).trigger(C.ROUTE_REQUEST, request);
						}, 3000);
						break;

				}

			}
		}
};
	this.order_update = {
		params: ['source'],
		callbacks: {
			params_set: function () {
				orbcard.load_orb_configuration();
			}
		}
	};
	this.confirm_address = {
				modal: XSM.modal.primary,
				url: {url: "confirm-address", type: C.GET, defer: true},
				params: ['context', 'restore'],
				callbacks: {
					params_set: function () {
						switch (this.read('context')) {
							case "menu":
								// ie. not from within the modal, so no need to defer
								this.url.defer = false;
								break;
							case "cancel":
								this.unset('url');
								this.unset('launch');
								switch (this.read('restore')) {
									case 'menu':
										window.xtr.menu.unstash_menu();
										$(window.xtr.router).trigger(C.ROUTE_REQUEST, {request:"set_order_method/menu/just_browsing", trigger:{}});
										window.xtr.menu.set_order_method(C.JUST_BROWSING);
										break;
									default:
										window.xtr.layout.dismiss_modal(this.modal);
										break;
								}
								break;
						}
					},
					launch: function () {
						if (is_object(this.deferral_data) && "modal" in this.deferral_data) { // super tired may be fuckin' wroooong
							window.xtr.router.cake_ajax_response(this.deferral_data, {
								data: {context: this.read('context'), modal: this.modal, modal_content: this.modal_content}
							})
						}
					}
				}
			};
	this.set_order_method = {
		modal: XSM.modal.primary,
		url: {url: "order-method", type: C.POST, defer: true},
		params: {
			context: {value: null, url_fragment: true},
			method: {value: null, url_fragment: true}
		},
		callbacks: {
			launch: function () {
				window.xtr.router.cake_ajax_response(this.deferral_data, {
					callback: function(response) {
						window.xtr.data.Service = response.data.Cart.Service;
						window.xtr.menu.set_order_method();
						window.xtr.cart.init(response.data.Cart);
					}
				}, true, true);
			}
		}
	};
	this.set_user_address = {
		params: ['id', 'action'],
		callbacks: {
			params_set: function() {
				switch (this.read('action') ) {
					case 'reveal':
						window.xtr.modal.order_method.reveal_user_addresses(this.read('id'));
						break;
					case 'set':
						window.xtr.modal.order_method.populate_address_form(this.read('id'));
						break;
				}
			}
		}
	};
	this.cart = {
				modal: XSM.modal.primary,
				params: ["action", "action_arg", "data"],
				propagates: false,
				callbacks: {
					params_set: function () {
						pr(this, "PARAMS_SET 0");
						switch ( this.read("action") )  {
							case "add":
								 if ( this.read('action_arg') == 'cancel') {
									this.unset(['url', 'launch']);
									 pr(this, "PARAMS_SET 1");
									 window.xtr.orbcard.reset_stage();
								 } else {
									 this.url = {
						                url: "add-to-cart",
						                type: C.POST,
						                defer: true,
						                data: $(XSM.menu.orb_order_form).serialize()
						            }
								 }
							break;
							case "clear":
								this.url = {
									url: "clear-cart",
									type: C.POST,
									defer: true
								};
								this.unset("launch");
							break;
							case "review":
								this.url =  {
									url: "review-cart",
									type: C.GET,
									defer: false
								};
								this.change_behavior(C.STASH_STOP);
								this.unset('launch');
								break;
						}
					},
					launch: function () {
						window.xtr.router.cake_ajax_response(this.deferral_data, {
							callback: function(response) {
								pr(response, "add to cart resp.");
								window.xtr.cart.init(response.data.Cart);
								// only "clear cart" has a delegate response
								if ( !("delegate_route" in response) ) window.xtr.layout.reveal_orb_card_modal();
							}
						}, true, true);
						try {
							var data = JSON.parse(this.deferral_data);
							if (data.success === true && window.xtr.cart.add_to_cart()) {

							} else { throw "Add to cart failed at server:\n " + this.deferral_data; }
						} catch (e) {
							try {
								pr(JSON.parse(this.deferral_data), "cart->order", true);
								throw "Add to cart failed at server:\n " + this.deferral_data;
							} catch (e) {
								throw "Add to cart failed at server:\n " + this.deferral_data;
							}
						}
					}
				}
			};
	return this
};
/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.tablet_ui = function() {
	this.tablet_print = {
		params: ['context'],
		callbacks: {
			params_set: function () {
				var debug_this = 0;
				if (debug_this > 0) pr(XBS.printer.queue, "ROUTE: print_from_queue");
				var response;
				switch (this.read('context')) {
					case 'vendor_accepted':  // ie. first entry into printing loop
						response = XBS.printer.queued() ? XBS.printer.print_from_queue() : false;
						break;
					case 'line_complete':
						response = XBS.printer.queued() ? XBS.printer.print_from_queue() : false;
						if (!XBS.printer.queued()) XBS.printer.cut(true);
						break;
				}
				if (response === false) this.unset('launch');
				//
				//							XBS.printer.tab_out(response, "print response");
				//							if ( response.error) {
				//								XBS.printer.queue.unshift({text:"WARNING: Receipt Line Dropped!", style:"h4"})
				//								pr(response, "DROPPED LINE", 1);
				//							}
				//							if (response.queue_empty) {
				//								if (!XBS.printer.printer_available) XBS.printer.render_virtual_receipt();
				//								this.unset('launch');
				//							}
			},
			launch: function () {
				try {
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "print_from_queue/line_complete", trigger: {}});
				} catch (e) {
					pr(e);
				}
			}
		}
	};
	this.tablet_fetch_pending = {
		url: {url: 'pending', defer: true, type: C.GET},
		callbacks: {
			params_set: function () {
				var debug_this = 0;
				if (debug_this > 0) pr("<no_args>", "XBS.routing.vendor_get_pending()::params_set", 2);
				if (!XBS.vendor.last_check) {
					XBS.vendor.last_check = now();
					this.unset('url');
				}
				if (now() - XBS.vendor.last_check < 3000) this.unset('url');
			},
			launch: function () {
				if (this.url.url) {
					XBS.vendor.last_check = now();
					var data = $.parseJSON(this.deferral_data);
					if (!data.error && data.orders.length > 0) {
						XBS.vendor.post_orders(data.orders);
					}
				}
				setTimeout(function () {
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger: {}});
				}, 500);
			}
		}

	};
	this.tablet_reject = {
		params: ['state'],
		callbacks: {
			params_set: function () {
				switch (this.read('state')) {
					case 'unconfirmed':
						$(XSM.vendor.order_reject_confirmation).removeClass(XSM.effects.slide_left);
						break;
					case 'confirm':
						this.url = {
							url: "vendor-reject" + C.DS + XBS.vendor.current().id + C.DS + C.REJECTED,
							type: C.POST,
							defer: true
						};
						this.set_callback("launch", function () {
							var data = $.parseJSON(this.deferral_data);
							$(XSM.vendor.order_reject_confirmation).addClass(XSM.effects.slide_left);
							// todo: make sure the rejection went well;
						});
						break;
					case 'cancel':
						$(XSM.vendor.order_reject_confirmation).addClass(XSM.effects.slide_left);
						break;
				}
			}
		}
	};
	this.tablet_accept = {
		params: ['method', 'context'],
		url: { url: "vendor-accept", type: C.POST, defer: true},
		callbacks: {
			params_set: function () {
				this.url.url += C.DS + XBS.vendor.current().id + C.DS + C.ACCEPTED;
				$(XSM.vendor.accept_acknowledge).show();
				setTimeout(function () {
					$(XSM.vendor.accept_acknowledge).addClass(XSM.effects.exposed);
					setTimeout(function () {
						$(XSM.vendor.accept_acknowledge).removeClass(XSM.effects.exposed).hide();
					}, 300);
				}, 30);
				XBS.vendor.last_check = -100000;
			},
			launch: function () {
				var data = $.parseJSON(this.deferral_data);
				if (data.success) {
					var order = XBS.vendor.current();
					XBS.vendor.pending_orders.shift()
					XBS.vendor.current_order = null;
					XBS.vendor.last_check = now();
					$(XSM.vendor.order_accept_button).removeClass(XSM.effects.launching);
					$(XSM.vendor.order_accepted).addClass(XSM.effects.fade_out);
					XBS.printer.queue_order(order);
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {
						request: "print_from_queue/vendor_accepted",
						trigger: {}
					});
					if (true) XBS.vendor.next();
					else $(XSM.vendor.error_pane).removeClass(XSM.effects.slide_up);
				}
			}
		}
	};

	return this;
};
/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.user_accounts = function() {
	this.login = {
		url: {url: false, type: C.POST},
		modal: XSM.modal.primary,
		params: {
			context: {},
			channel: {url_fragment: true},
			restore: {}
		},
		callbacks: {
			params_set: function () {
				var context = this.read('context');
				var channel = this.read('channel');
				var restore = this.read('restore');
				pr(this.params, "login::params");
				switch (context) {
					case "confirm-address":
						switch (channel) {
							case "email":
								this.unset("url");
								this.set_callback('launch', function () {
									$(XBS.routing).trigger(C.ROUTE_REQUEST, {
										request: "login/modal/email/confirm-address",
										trigger: this.trigger
									});
								});
								break;
							default:
								this.unset('url');
								this.unset('modal');
								this.set_callback('launch', function () {
									$(XSM.modal.confirm_address_login_panel).hide().removeClass(XSM.effects.true_hidden);
									setTimeout(function () {
										$(XSM.modal.confirm_address_login_panel).show('clip');
									}, 30);
								})
						}
						break;
					case "modal":
						this.url = {url: "login/email", defer: false, type: C.GET};
						if (restore) {
							this.set_callback('launch', function () {
								pr("got here");
								$("#on-close").replaceWith(
									'<div id="on-close" class="true-hidden" data-action="restore/confirm_address"></div>');
							});
						}
						break;
					default:
						if (channel == "email") {
							this.url.url = "login/email";
							this.url.type = C.GET;
							//											this.url.defer = true;
							this.set_callback("launch", function () {
								pr("login/email");
								pr(this.deferral_data);
							})
						}
				}
			},
			launch: function () {
				window.location = "http://development-xtreme-pizza.ca/auth/" + this.read('channel');
			}
		}
	};
	this.register = {
				modal: XSM.modal.primary,
				url: {url: "sign-up", type: C.POST, defer: true},
				params: {
					context: {},
					channel: {value: null, url_fragment: false},
					restore: {},
					hide_reg: {value: false}
				},
				callbacks: {
					params_set: function () {
						var channel = this.read('channel');
						switch (this.read('context')) {
							case "modal":
								this.url.defer = true;
								if (in_array(channel, ['email', "submit"])) this.url.url = false;
								if (in_array(channel, ["twitter", "facebook", "google"])) {
									this.add_param("hide-reg", true, false);
									this.url.url = "auth/" + channel;
								}
								if (channel == 'submit') {
									this.set_callback("launch", function () { XBS.validation.submit_register(this);})
								}
								break;
							case "topbar":
								this.url.defer = false;
								this.unset('launch');
								break;
							case "orb_card":
								this.set_callback("launch", function () {
									var data = this.deferral_data;
									$(XSM.modal.primary_content).html(data);
									$($(XSM.modal.primary).find(".register-link.email")[0]).addClass(XSM.effects.active);
									XBS.menu.toggle_orb_card_row_menu("register", C.HIDE);
									setTimeout(function () {
										$(XSM.modal.primary).removeClass(XSM.effects.slide_up);
										setTimeout(function () {
											$("#registration-method-bar").addClass("diminish");
											setTimeout(function () {
												$(XSM.modal.primary_deferred_content).removeClass(XSM.effects.slide_left);
											}, XBS.data.delays.global_transition);
										}, XBS.data.delays.global_transition);
									}, XBS.data.delays.orb_card_row_toggle);
								});
								break;
						}
					},
					launch: function () {
						var container = $(this.modal).find(XSM.modal.deferred_content)[0];
						var load_time = 30;
						$("#registration-method-bar").addClass("diminish");
						setTimeout(function () {
							$(".register-link.email").addClass(XSM.effects.active);
							if (this.deferral_data) {
								$(container).replaceWith(
									$("<div/>").addClass([XSM.modal.deferred_content, XSM.effects.slide_left].join(" "))
										.html(this.deferral_data)
								);
							}
							setTimeout(function () { $(container).removeClass(XSM.effects.slide_left);}, load_time);
						}, 300);
					}
				}
			};
	this.submit_registration = {
				modal: XSM.modal.primary,
				url: {url: false, type: C.POST, defer: true},
				params: {channel: {value: false, url_fragment: false}},
				callbacks: {
					launch: function () {
						if (this.read('channel') == "email") {
							XBS.validation.submit_register(this);
						} else {
							this.url.url = "auth" + C.DS + this.read('channel');
						}
					}
				}
			};

	return this;
};
/**
 * Created by jono on 8/11/15.
 */
window.xtr.route_collections.vendor_ui = function() {
	this.vendor_ui = {
		params: {target: {url_fragment: true}},
		url: {url: "vendor-ui", defer: true, type: C.POST},
		modal: XSM.modal.primary,
		callbacks: {
			params_set: function () {
				pr(this.read('target'), "vendor_ui");
			},
			launch: function () {
				if (this.read('target') == "opts") {
					$("#menu-options-tab").replaceWith(this.deferral_data);
				}
				if (this.read('target') == "menu") {
					$("#menu-tab").replaceWith(this.deferral_data);
				}
				$(document).foundation();
				//							XT.vendor_ui.init();
			}
		}
	};
	this.update_menu = {
		params: { target: {url_fragment: true}, attribute: {url_fragment: true} },
		url: { url: "orbs" + C.DS + "update_menu", type: C.POST, data: null },
		callbacks: {
			params_set: function () {
				var data = null;
				if (this.read('attribute') == "orbopts") {
					data = $(XSM.vendor_ui.orbopt_config_form_wrapper).serialize();
				} else {
					var cell_id = as_id(["orb", this.read('target'), this.read('attribute')].join("-"));
					data = $("form", cell_id).serialize();
				}
				this.url = {
					url: ["orbs", "update_menu", this.read('target'), this.read('attribute')].join(C.DS),
					type: C.POST,
					defer: true,
					data: data
				};
				switch (this.read('target')) {
					case 'pricedicts':
						if (this.read('attribute') == 'fetch') {
							this.url = {
								url: "add-price-labels",
								type: C.GET,
								defer: false
							};
							this.set_modal(XSM.modal.primary);
							this.unset('launch');
						}
						if (this.read('attribute') == 'save') {
							this.url = {
								url: "add-price-labels",
								type: C.POST,
								defer: true,
								data: $(XSM.vendor_ui.price_dict_update_form).serialize()
							};
							this.unset('modal');
						}
						break;
				}

			},
			launch: function () {
				XT.routing.cake_ajax_response(this.deferral_data, {
						callback: function (response, data) {
							if (data.target == 'pricedicts') $("#menu-tab").load("vendor-ui/menu");
							$(XT.routing).trigger(C.ROUTE_REQUEST, {request: "close_modal/primary", trigger: {}});
							XT.vendor_ui.save_orb(data.target, data.attribute, 'replace' in response ? response.replace : null);
						},
						data: { target: this.read('target'), attribute: this.read('attribute') }
					},
					true
				);
			}
		}
	};
	// ORBS
	this.orb_edit = {
				params: { id: {value: null}, action: {}, action_arg: {} },
				callbacks: {
					params_set: function () {
						switch (this.read('action')) {
							case "delete":
								var confirmation_box = as_id(['delete', 'orb', this.read('id')].join("-"));
								switch (this.read('action_arg')) {
									case "confirm":
										$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
										setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
										this.unset('launch');
										break;
									case "cancel":
										$(confirmation_box).addClass(XSM.effects.fade_out);
										setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
										this.unset('launch');
										break;
									case "delete":
										this.url = {
											url: ["delete-menu-item", this.read('id')].join(C.DS),
											type: C.POST,
											defer: true
										};
										this.set_callback("launch", function () {
											XT.routing.cake_ajax_response(this.deferral_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_tab).load("vendor-ui/menu", function () {
														XT.vendor_ui.data_tables('menu');
														XT.vendor_ui.fix_breakouts();
													});
												}
											}, true, true);
										});
								}
							case "add":
								switch (this.read('action_arg')) {
									case "create":
										this.set_modal(XSM.modal.primary);
										this.url = {
											url: "add-menu-item",
											type: C.GET,
											defer: false
										}
										this.unset('launch');
										break;
									case "save":
										this.unset('modal');
										this.url = {
											url: "add-menu-item",
											type: C.POST,
											defer: true,
											data: $(XSM.vendor_ui.orb_add_form, XSM.modal.primary).serialize()
										}
										this.set_callback("launch", function () {
											XT.routing.cake_ajax_response(this.deferral_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_options_tab).load("vendor-ui/menu",
														function () {
															XT.vendor_ui.data_tables('menu');
															XT.vendor_ui.fix_breakouts();
														});
												}
											}, true);
										});
										break;
								}
								break;

						}
					},
					launch: function () { XT.routing.cake_ajax_response(this.deferral_data, {}, true, true);}
				}
			};
	this.orb_config = {
		params: ["target", "action", "attribute"],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case 'edit':
						XT.vendor_ui.edit_cell('orb', this.read('target'), this.read('attribute'));
						break;
					case 'cancel':
						XT.vendor_ui.cancel_cell_editing('orb', this.read('target'), this.read('attribute'));
						break;
				}
			}
		}
	};

	// ORBOPTS
	this.orbopt_config = {
		url: {url: "orbopt-config", method: C.GET},
		modal: XSM.modal.primary,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				if (this.read('action') != "launch") {
					this.unset('url');
					this.unset('modal');
				}
				switch (this.read('action')) {
					case 'add':
						this.url = {
							url: "add-menu-option",
							type: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.add_orbopt_form).serialize()
						};
						this.set_callback("launch", function () {
							XT.routing.cake_ajax_response(this.deferral_data, {
								callback: function () { XT.vendor_ui.reload_tab('opts'); }
							}, true);
						});
					case 'delete':
						var confirmation_box = as_id(['delete', 'orbopt', this.read('id')].join("-"));
						switch (this.read('action_arg')) {
							case "confirm":
								$(confirmation_box).addClass(XSM.effects.fade_out).removeClass(XSM.effects.hidden);
								setTimeout(function () { $(confirmation_box).removeClass(XSM.effects.fade_out);}, 300);
								this.unset('launch');
								break;
							case "cancel":
								$(confirmation_box).addClass(XSM.effects.fade_out);
								setTimeout(function () { $(confirmation_box).addClass(XSM.effects.hidden); }, 300);
								this.unset('launch');
								break;
							case "delete":
								this.url = {
									url: ["delete-menu-option", this.read('id')].join(C.DS),
									type: C.POST,
									defer: true
								};
								this.set_callback("launch", function () {
									XT.routing.cake_ajax_response(this.deferral_data, {
										callback: function () {
											$(XSM.vendor_ui.menu_options_tab).load("vendor-ui/opts",
												function () {
													XT.vendor_ui.data_tables('opts');
													XT.vendor_ui.fix_breakouts();
												});
										}
									}, true);
								});

						}
						break;
					case 'toggle':
						this.set_callback('launch', function () {
							XT.vendor_ui.toggle_orbopt(this.read('id'));
						});
						break;
					case 'set_opt_state':
						this.set_callback('launch', function () {
							pr([this.read('id'), this.read('action_arg')]);
							XT.vendor_ui.set_orbopt_state(this.read('id'), this.read('action_arg'));
						});
						break;
					case 'toggle_group':
						this.set_callback('launch', function () {
							XT.vendor_ui.toggle_orbopt_group($(this.trigger.event.target).val());
						});
						break;
					case 'filter':
						this.set_callback('launch', function () {
							XT.vendor_ui.toggle_filter(this.read('id'));
						});
					default:
						break;
				}
			},
			launch: function () {
				var prim_con_h = $(XSM.modal.primary_content).innerHeight();
				var prim_mod_max = $(XSM.modal.primary).innerHeight() - 48;
				if (prim_con_h > prim_mod_max) {
					$(XSM.modal.primary_content).css({
						height: prim_mod_max,
						overflowY: "auto"
					});
				}
				;

				$(document).foundation();
			}
		}
	},
	// "edit" is to orbopts as "config" is to orbs in terms of routing names / functionality in vendor_ui
	this.orbopt_edit = {
		params: ['id', 'action', 'attribute'],
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case 'breakout':
						this.unset('launch');
						this.unset('url');
						var target = null;
						if (this.read('attribute') == 'add_opt') {
							target = XSM.vendor_ui.orbopt_add_breakout;
						} else {
							target = XSM.vendor_ui.orbopt_pricelist_add_breakout;
						}
						XT.vendor_ui.toggle_menu_options_breakout(target);
					case 'edit':
						pr(this.trigger);
						XT.vendor_ui.edit_cell('orbopt', this.read('id'), this.read('attribute'));
						break;
					case 'cancel':
						pr(this.trigger);

						XT.vendor_ui.cancel_cell_editing('orbopt', this.read('id'), this.read('attribute'));
						break;
					case 'save':
						this.url = {
							url: ["update-orbopt", this.read('id'), this.read('attribute')].join(C.DS),
							type: C.POST,
							defer: true,
							data: $("form", as_id(["orbopt", this.read('id'), this.read('attribute')].join("-"))).serialize()
						};
						this.set_callback("launch", function () {
							XT.routing.cake_ajax_response(this.deferral_data, {
									callback: XT.vendor_ui.save_orbopt,
									data: { id: this.read('id'), attribute: this.read('attribute') }
								},
								true, true);
						});
						break;
				}
			}
		}
	};
	this.orbopt_pricelist = {
		params: ['action', 'action-arg', 'id'], // by context can be either of pricelist.id or orbopt.id, usually former
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "set":
						XT.vendor_ui.set_orbopt_pricelist_focus();
						break;
					case 'launch':
						this.set('modal', XSM.modal.primary);
						this.url = {
							url:["launch-orbopt-pricelist-config", this.read('id')].join(C.DS),
							type: C.GET,
							defer:false
						};
						this.set_callback('launch', XT.vendor_ui.fix_breakouts);
						break;
					case 'add':
						XT.vendor_ui.toggle_pricelist_add("reveal");
						break;
					case "edit":
						if (this.read('action-arg') == "save") {
							var orbopt_id = $("#orbopt-pricelist-select").val();
							this.url = {
								url: ["save-orbopt-pricing-edit", this.read('id'), orbopt_id].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("#orbopt-pricelist-add-edit-form").serialize()
							};
						} else {
							XT.vendor_ui.edit_orbopt_pricelist();
						}
						break;
					case 'cancel-add':
						XT.vendor_ui.set_orbopt_pricelist_focus();
						break;
					case "cancel":
						$(XT.routing).trigger(C.ROUTE_REQUEST, {request: "orbopt_edit/-1/cancel/pricing", trigger: {}});
						break;
					case "save":
						if (this.read('action-arg') == "pricelist") {
							var orbopt_id = $("#orbopt-pricelist-select-form", C.BODY).data('opt');
							this.url = {
								url: ["add-orbopt-pricelist", orbopt_id].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("form", XSM.vendor_ui.orbopt_pricelist_add).serialize()
							};
						}
						if (this.read('action-arg') == "opt") {
							this.url = {
								url: ["update-orbopt", this.read('id'), 'pricing'].join(C.DS),
								type: C.POST,
								defer: true,
								data: $("#orbopt-pricelist-select-form", C.BODY).serialize()
							};
						}
						break;
					case "delete":
						//TODO: you wrote the *entire* delete method when you were a) fucking ballin' at code but b) fiending to leave; it's not tested AT ALL yet
						// delete.delete is reached on the second pass, after warning & confirmation
						if ( this.read('action-arg') == "delete" ) {
							this.url = {
								url: ["orbopt-pricing-delete", this.read('id')].join(C.DS),
								type: C.POST,
								defer:true
							}
						} else {
							this.unset("launch");
						}
						XT.vendor_ui.delete_orbopt_pricelist(this.read('action-arg'));
						break;

				}
			},
			launch: function() {XT.routing.cake_ajax_response(this.deferral_data, {}, true, true);}
		}
	};
	this.orbopt_optgroup_config = {
		url: {url: "orbopt-optgroup-config", method: C.GET},
		modal: XSM.modal.primary,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "toggle":
						this.unset('url');
						this.unset('modal');
						this.unset('launch');
						var label_id = as_id(["optgroup", this.read('id'), 'label'].join("-")) + " span";
						var field_sel = "input[name='OrboptOrbcat[" + this.read('id') + "]'";
						if ($(label_id).hasClass(XSM.effects.success)) {
							$(label_id).removeClass(XSM.effects.success);
							$(label_id).addClass(XSM.effects.secondary);
							$(field_sel).val(0);
						} else {
							$(label_id).addClass(XSM.effects.success);
							$(label_id).removeClass(XSM.effects.secondary);
							$(field_sel).val(1);
						}
						break;
					case "save":
						this.url = {
							url: this.url.url,
							method: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.orbopt_optgroup_config_form).serialize()
						};
						this.set_callback('launch', function () {
								XT.routing.cake_ajax_response(this.deferral_data, null, true, true);
							}
						);
						break;
				}
			},
			launch: function () { $(document).foundation(); }
		}
	};
	this.orbflag_config = {
		params: {orbopt: {url_fragment: true}, optflag: {url_fragment: true}},
		url: {url: "optflag-config", type: C.POST, defer: true},
		callbacks: {
			params_set: function () {
				this.url = {
					url: ["optflag-config", this.read('orbopt'), this.read('optflag')].join(C.DS),
					type: C.POST,
					defer: true}
			},
			launch: function () {
				XT.routing.cake_ajax_response(this.deferral_data, {
						callback: XT.vendor_ui.toggle_optflag,
						data: { orbopt: this.read('orbopt'), optflag: this.read('optflag') }
					},
					true, true);
			}
		}
	};

	return this
};
/**
 * Created by jono on 12/30/14.
 */

XtremeRoute = function (name, data) {
	this.__init(name, data);
	return this;
}

XtremeRoute.prototype = {
	constructor: XtremeRoute,
	route_data:  undefined,
	param_data: undefined,

	// data
	route_uid: undefined,
	route_name: undefined,
	request: undefined,

	trigger : {event: false, element: false},
	modal : undefined,
	url : {url: false, type: false, defer: false},
	params : undefined,
	p: this.params,
	deferral_data : undefined,

	// inferred data
	modal_content : undefined,

	// callbacks
	params_set_callback : undefined,
	launch_callback : undefined,
	post_init_callback : undefined,

	// behaviors
	stash : false,
	overlay : false,
	__stop_propagation : true,

	/**
	 * class initiation
	 *
	 * @param name
	 * @param data
	 * @returns {boolean}
	 * @private
	 */
	__init: function (request_obj, event) {
		var debug_this = 0;
		if (debug_this > 0) pr([request_obj, event], this.__debug("init", ["request_obj", "event"]));
		this.request = request_obj.request;
		this.trigger = {event: event, element: event.currentTarget};
		this.route_name = request_obj.request.split(C.DS)[0];
		this.param_data = request_obj.request.split(C.DS).slice(1);
		this.route_data = window.xtr.router.route_data[ this.route_name ];

		if ("modal" in this.route_data) {
			this.modal = this.route_data.modal;
			this.modal_content = this.route_data.modal + "-content";
		}
		if ("url" in this.route_data) { this.set_url(this.route_data.url) }

		if ("propagates" in this.route_data) {
			if (this.route_data.propagates == false) {
				this.__stop_propagation = true;
			}
		}
		this.stop_propagation();

		if ("params" in this.route_data) {
			this.params = {};
			if (is_array(this.route_data.params)) {
				for (var i = 0; i < this.route_data.params.length; i++) {
					this.params[this.route_data.params[i]] = {value: false, url_fragment: false, post_init: false};
				}
			} else {
				for (var param in this.route_data.params) {
					this.params[param] = {value: false, url_fragment: false, post_init: false};
					if ("value" in this.route_data.params[param]) this.params[param].value = this.route_data.params[param].value;
					if ("url_fragment" in this.route_data.params[param]) this.params[param].url_fragment = this.route_data.params[param].url_fragment === true;
					if ("post_init" in this.route_data.params[param]) this.params[param].post_init = this.route_data.params[param].post_init === true;
				}
			}
		}

		this.__set_behavior("behavior" in this.route_data ? this.route_data.behavior : false);

		if ("callbacks" in this.route_data) {
			if ("post_init" in this.route_data.callbacks) this.post_init_callback = this.route_data.callbacks.post_init
			if ("params_set" in this.route_data.callbacks) this.params_set_callback = this.route_data.callbacks.params_set
			if ("launch" in this.route_data.callbacks) {
				this.launch_callback = this.route_data.callbacks.launch;
				$(this).on("route_launched", this.launch_callback);
			}

		}
		if (this.post_init_callback) this.post_init_callback();
//		$(this).off();
//				var debug_this = 0;
//				if (debug_this > 0) pr(param_values, "XtremeRoute::init_instance(param_values)");
		this.route_uid = "*" + this.route_name + "[" + new Date().getTime() + "]";
		if (this.launch_callback) $(this).on("route_launched", this.launch_callback);
		this.__set_params();

		return true;
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

	__set_behavior: function (behavior_mask) {
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
	},


	/**
	 * __set_params() readies instance with param data pulled from data-route attr of initiating html element
	 * @param param_values
	 * @returns {boolean}
	 * @private
	 */
	__set_params: function () {
		if (this.param_data.length == 0) return
		var debug_this = 0;
		if (debug_this > 0) pr(this.param_data, "XtremeRoute::__set_params(param vals)");

		var param_keys = Object.keys(this.params);
		for (var i = 0; i < param_keys.length; i++) {
			if (!this.param_data[i]) continue;
			if (this.params[param_keys[i]].post_init) continue; // was dynamically set and won't be in route str.
			if (this.params[param_keys[i]].url_fragment) {
				if (!this.url_append(this.param_data[i]) && debug_this > 1) {
					pr({
							param: param_keys[i],
							value: this.param_data[i],
							params: this.params,
							values: this.param_data},
						"Route::url_append() failed", true);
				}
			}
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

		return true;
	},

	/**
	 * init() instance initiation
	 * @param param_values
	 */
	init: function (param_values) {
		$(this).off();
		var debug_this = 0;
		if (debug_this > 0) pr(param_values, "XtremeRoute::init_instance(param_values)");
		this.route_name = "*" + this.route_name + "[" + new Date().getTime() + "]";
		if (this.launch_callback) $(this).on("route_launched", this.launch_callback);
		if (param_values) this.__set_params(param_values);
		return this;
	},


	/**
	 * add_param()
	 * @param name
	 * @param value
	 * @param url_fragment
	 * @returns {boolean}
	 */
	add_param: function (name, value, url_fragment) {
		this.params[name] = {
			value: value != "undefined" ? value : false,
			url_fragment: url_fragment === true,
			post_init: true
		};
		return true;
	},


	change_behavior: function (behavior_mask) { this.__set_behavior(behavior_mask); },


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
		this.init()
	},


	set_deferral_data: function (data) { this.deferral_data = data},


	/**
	 * set_modal() setter for modal attr
	 * @param modal
	 */
	set_modal: function (modal) {
		this.modal = modal;
		this.modal_content = modal + "-content";
	},


	/**
	 * set_url() setter for url attr; can provide a string URL or full/partially complete url data hash
	 * @param url_hash
	 */
	set_url: function (url_hash) {
		try {
			this.url.url = 'url' in url_hash ? url_hash.url : false;
			this.url.type = "type" in url_hash ? url_hash.type : C.GET;
			this.url.defer = "defer" in url_hash ? url_hash.defer : false;
		} catch(e) {
			throw "Invalid url_hash supplied to Route.set_url:\n\t [" + e +"]";
		}
	},

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
	 * url_append() maintains internal url attr structure when working with an instance
	 * @param fragment
	 * @returns {boolean}
	 */
	url_append: function (fragment) {
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
	},


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
/**
 * Created by jono on 1/22/15.
 */

XtremeRouter = function() { this.init(); return this}

XtremeRouter.prototype = {
	constructor: XtremeRouter,
	route_data: {},
	XT: undefined,
	init: function () {
		this.XT = window.xtr;
		for (var rc in this.XT.route_collections) {
			rc = this.XT.route_collections[rc]();
			for (var route in rc ) this.route_data[route] = rc[route];
		}
		/* For launching routes via the DOM */
		var self = this;
		$(C.BODY).on(C.CLK, XSM.global.route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				$(self).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('route'), trigger: e});
			}
		});

		$(C.BODY).on(C.CHANGE, XSM.global.onchange_route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				$(self).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('changeroute'), trigger: e});
			}
		});

		/* For launching routes via ROUTE_REQUEST events */
		$(this).on(C.ROUTE_REQUEST, function (e, data) { this.launch(data, e) });
	},

	/**
	 * launch() creates and runs an instance of the class from passed param array
	 * @param params
	 * @param event
	 * @returns {boolean}
	 */
	launch: function (request_obj, event) {
		var debug_this = 0;
		if (debug_this > 0) pr([request_obj, event], "XtremeRouter.launch(request_obj, event)", 2);
		var route = new XtremeRoute( request_obj, event );
		var launch_delay = 0
		var hide_class = false;
		if (route.stash) {
			launch_delay += 900;
			if (this.XT.orb_card.exposed_face == C.BACK_FACE) launch_delay += 960;
		}

		if (in_array(route.modal, [XSM.modal.primary, XSM.modal.splash]))  hide_class = XSM.effects.slide_up;
		if (hide_class && !$(route.modal).hasClass(hide_class) && route.url.defer == false) {
			launch_delay += 300;
			this.XT.layout.dismiss_modal(route.modal, false);
		}
		// >>> RESIZE & POSITION PRIMARY IF NEEDED <<<
		this.XT.layout.resize_modal(route.modal)

		// >>> LAUNCH MODALS IF REQUIRED<<<
		if (route.url.url) {
			var launch_triggered = false;
			try {
				$.ajax({
					type: route.url.type ? route.url.type : C.POST,
					url: [this.XT.host, route.url.url].join(C.DS),
					data: "data" in route.url ? route.url.data : null,
					statusCode: {
						403: function () {
							this.launch("flash/fail");
							if (!launch_triggered) {
								launch_triggered = true;
								$(route).trigger("route_launched", "403_FORBIDDEN")
							}
						}
					},
					success: function (data) {
						// >>> DO PRE-LAUNCH EFFECTS <<<
						$(XSM.global.loading).addClass(XSM.effects.fade_out);
						if (route.stash) this.XT.menu.stash();

						setTimeout(function () {
							if (debug_this > 2) pr([route, data], route.__debug("launch/success"));
							if (route.url.defer) {
								route.set_deferral_data(data);
							} else {
								$(route.modal_content).html(data)
								if (hide_class) $(route.modal).removeClass(hide_class);
							}
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "TRIGGERED_POST_SUCCESS"});
						}, launch_delay);
					},
					fail: function () {
						$(XSM.global.loading).addClass(XSM.effects.fade_out);
						if ("fail_callback" in route) {
							route.fail_callback();
						} else {
							this.XT.layout.launch_route(XT.routes.fail_flash);
						}
						if (!launch_triggered) {
							launch_triggered = true;
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "FAIL_TRIGGER"})
						}
					},
					always: function () {
						$(XSM.global.loading).addClass(XSM.effects.fade_out);
						if (!launch_triggered) {
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "FALLBACK_TRIGGER"})
						}
					}
				});
			} catch (e) {
				if (!launch_triggered) {
					launch_triggered = true;
					$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "CAUGHT_EXCEPTION"})
				}
				$(this).trigger(C.ROUTE_REQUEST, {request: "flash"});
			}
		} else {
			$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "NO_AJAX"});
		}
		delete route;
		return true;
	},

	/**
	 *
	 * @param deferral_data
	 * @param success_handler
	 * @param print_response
	 * @param print_deferral_data
	 */
	cake_ajax_response: function (deferral_data, success_handler, print_response, print_deferral_data) {
		if (print_deferral_data) pr(deferral_data, "deferral DATA");
		if (deferral_data.substr(0, 4) == "<pre") {
			$(XSM.modal.primary_content).html(deferral_data);
			setTimeout(function() { $(XSM.modal.primary).removeClass(XSM.effects.slide_up); }, 30);
		}
		var response = $.parseJSON(deferral_data)
		if (print_response) pr(response, "RESPONSE");

		//TODO: 1) add error messages for the user on appropriate errors; 2) handle such messages via the flash/<msg> route

		// don't proceed to sucess handler if any part of the request failed
		if ("success" in response) {
			if (is_object(response.success)) {
				for (var controller in response.success) if (response.success[controller] != true) return;
			} else if (response.success !== true)  return
		}
		this.XT.layout.dismiss_modal(XSM.modal.primary);
		try {
			"data" in success_handler ? success_handler.callback(response, success_handler.data) : success_handler.callback(response);
		} catch (e) {};
		try {
			$(this).trigger(C.ROUTE_REQUEST, {request: response.delegate_route, trigger: {}});
		} catch (e) {}

	},


	/**
	 *
	 * @param route_string
	 * @param start_index
	 * @param stop_index
	 * @returns {Array}
	 */
	route_split: function (route_string, start_index, stop_index) {
		var route = route_string.split(C.DS);
		if (isInt(start_index)) {
			return isInt(stop_index) ? route.slice(start_index, stop_index) : route.slice(start_index);
		}
		return route;
	}
}
/**
 * Created by jono on 1/20/15.
 */
var allow_tab_out = false;

function tab_out(output, label, error_ob) {
	if (!allow_tab_out) return;
	try {
		if (output === null) output = "null";
		if (output === true) output = "true";
		if (output === false) output = "false";
		if (error_ob) {
			var error = {e_txt:output.message, e_stack:output.stack};
			output = sprintf("<pre>%s</pre>", JSON.stringify( error, null, "\t"));
		}
		if (typeof(output) != "string") output = sprintf("<pre>%s</pre>", (JSON.stringify(output, null, "\t")));
		var line = $("<div/>").addClass('jsc-line');
		if (!label) label = "----";
		$(line).append($("<div/>").addClass('tout-label').html(label));
		$(line).append($("<div/>").addClass('tout-text').html(output));
		$("main#js-console").append(line);
	} catch (e) {
		$('body').style({backgroundColor: 'yellow'});
		setTimeout(function() {
			$('body').css({backgroundColor: 'yellow'});
			$("main").hide();
			$("body").append($("<div/>").css({marginTop:'80px'}).html(e.message));
		}, 250);
	}
}

function tout_show(called_from) {
	if (!allow_tab_out) return;
	called_from = called_from ? called_from : "anon"
	$("h4#called-from").html(called_from);
	$("main").hide();
	setTimeout(function() {
	$("main#js-console").show();
		setTimeout(function() {
			$("main#js-console").css({position:'fixed', top:'80px', left:'0px', zIndex:9999999});
		}, 100);
	}, 50);
}


function XtremePrinter() {
	this.printer_available = false;
	this.ip = null;
	this.status = null;
	this.queue = {};
	this.styles = {}
	this.tab_out = function(text, label) { tab_out(text, label); };
	this.tout_show = function() {tout_show();};
	this.style_template = function() {
		return {
			indent:0,
			scale:1,
			line_h:1,
			align: C.LEFT,
			bold:false,
			underline: false
		}
	}
	this.virtual_receipts = [];

	this.init = function(ip) {
		this.status = this.open_printer(ip);
		this.add_style('default', 0, 'left', 3, 1.5, false, false);
		this.extend_style('center', 'default', {align:'center'});
		this.extend_style('right', 'default', {align:'right'});
		this.extend_style('orb_opt', 'default', {indent:2, scale:2});

		// headers
		this.add_style('h1', 0, 'center', 7.5, 5, true, false);
		this.extend_style('h2', 'h1', {scale:4, line_h:6});
		this.extend_style('h3', 'h1', {scale:3, line_h:4.5});
		this.extend_style('h4', 'h1', {scale:2, line_h:3});
		this.extend_style('h5', 'h1', {scale:1, line_h:2});

	}

	this.dequeue = function() {
		var line;
		var queue = {}
		var count = 0;
		for (var key in this.queue) {
			if (count == 0) {
				line = this.queue[key];
			} else {
				queue[key-1] = this.queue[key];
			}
			count++;
		}
		this.queue = queue;
		return line;
	}

	this.print_from_queue = function() {
		var response = {success: true, error:false, line:null, queue_empty:false, raw:null};
		if ( this.queued() ) {
			var line = this.dequeue();
			var cut = in_array(line.text, [C.CUT, C.FEED_CUT]);
			if (line.text != C.CUT) {
				if ( in_array(line.text, C.FEED, C.FEED_CUT) ) line.text = " \n";
				var print_response = this.print(line.text, line.style, cut);
				if (print_response.success === false) {
					response.success = false;
					response.error = print_response;
					response.line = line;
				}
			}
			if (cut) this.cut(true);
		} else {
			this.cut(true);
			response.queue_empty = true;
		}
		return response;
	}

	this.queued = function() { return obj_len(this.queue) > 0;}

	this.open_printer = function(ip) {
		var status;
		if ( XBS.printer.is_xtreme_tablet() || true) {
			status = ip ? Android.openPrinter(ip) : Android.openDefaultPrinter();
			status = status.split(":");
			status = {success: parseInt(status[0]) === 1, error:status[1] == 'false' ? false : status[1]};
			this.printer_available = status.success ? true : false;
			this.ip = status.ip;
			this.status = status;
		} else {
			this.printer_available = false;
			this.ip = false;
			this.status = {ip:false, success:false};
			status = this.status;
		}
		return status;
	}

	this.play_order_tone = function() {
		if ( XBS.printer.is_xtreme_tablet() ) {
			Android.playTone();
		} else {
			var audio = new Audio(XSM.vendor.new_order_tone);
			audio.play();
		}
	}

	this.feed_line = function(lines) {
		if (!lines && lines != 0) lines = 1;
		for (var i = 0; i < lines; i++) this.queue_line(" \n", 'default');
	}

	this.queue_line = function(line, style, feed) {
		if (!feed) feed = 0;
		var index = obj_len(this.queue);
		if (style) {
			this.queue[index] = {style:style, text:line};
		} else {
			this.queue[index] = {style:'default', text:line};
		}
		this.queue[index+1] = {style:'default', text:' '};
	}


	this.has_virtual_receipts = function() {
		if ( is_array(this.virtual_receipts) ) {
			return this.virtual_receipts.length > 0;
		} else {
			this.virtual_receipts = [];
			return false;
		}
	}

	/**
	 * queue_order()
	 *
	 * @param {obj} order
	 * @returns {void}
	 */
	this.queue_order = function(order) {
		pr(order, 'the order');
		tab_out("arrived", "queue_order()");
		try {
			this.queue_line(sprintf('Ordered At: %s', order.time), 'h4', 1);
			this.queue_line(sprintf("For: %s", order.order_method), 'h2', 1);
			if ( order.order_method == C.DELIVERY) {
				if (order.customer) this.queue_line(order.customer, 'center', 'h3');
				if (order.address) this.queue_line(order.address, 'center', 'h3');
				if (order.delivery_instructions ) this.queue_line(order.delivery_instructions, 'h4');
			}
			this.queue_line(sprintf("Payment Status: %s", order.paid ? "PAYED" : "OWED"), 'h3',1);
			this.queue_line(sprintf("Payment Type: %s", order.payment_method), 'h4');
			this.__queue_orb_list(order.food);
			this.queue_line(sprintf("TOTAL: $%s", order.price), 'h2');
		} catch(e) {
			// todo: handle this maybe?!
			tab_out(e, 'queue_order() error', true);
			return false;
		}
//		tout_show();
		pr(this.queue, 'the queue');
		return this.queue;
	};

	/**
	 * add_style()
	 * @param name
	 * @param indent
	 * @param scale
	 * @param line_space
	 * @param alignment
	 * @param bold
	 * @param underline
	 * @returns {boolean}
	 */
	this.add_style = function(name, indent, alignment, line_space, scale, bold, underline) {
		this.styles[name] = {
			align: alignment ? alignment : 'left',
			line_h: line_space ? line_space : 1,
			scale: scale ? scale : 1,
			indent: indent ? indent : 0,
			bold: bold ? bold :false,
			underline: underline ? underline : false
		}
		return true;
	}

	/**
	 * extend_style()
	 * @param {str} name
	 * @param {str} base
	 * @param {obj} ext
	 * @returns {boolean}
	 */
	this.extend_style = function(name, base, ext) {
		try {
			var style = this.style_template();
			for (var attr in style)  style[attr] = attr in ext ? ext[attr] : this.styles[base][attr];
			this.styles[name] = style
			return {name:name, style:style};
		} catch (e) {
			return e;
		}
	}

	/**
	 * show_dialog()
	 *
	 * @param {str} message
	 * @param {str} title
	 * @returns {void}
	 */
	this.show_dialog = function (message, title) { if (this.printer_available) Android.showDialog(message, title);}

	/**
	 *  print()
	 *
	 *  @param {str} text
	 *  @param {int} style
	 *  @returns {string}
	 */
	this.print = function (text, style, virtual_cut) {
		pr({text:text, style:style, virtual_cut:virtual_cut}, "XtremePrinter::print(text, style, virtual_cut)", 2);
		tab_out({text:text, style:style}, 'print()');
		var response = null;
		var s = XBS.printer.styles[style];
		try {
			if (this.printer_available) {
				response = Android.printText(text, 1, s.align, s.line_h, s.scale, s.scale, s.indent, s.bold, s.underline);
			} else {
				if (virtual_cut || this.virtual_receipts.length === 0) this.virtual_receipts.unshift([]);
				this.virtual_receipts[0].push(
					$("<li/>").addClass(["virtual-receipt", style]).html(text)
				);
				response = {success:true, error:false};
			}
		} catch (e) {
				tab_out(e, 'print error', true);
		}
		return response;
	}

	/**
	 * cut()
	 * @param {bool} feed
	 * @returns {void}
	 */
	this.cut = function (feed) { feed === true ? Android.cut(feed) : Android.cut(false); }

	/**
	 * __queue_orb_list()
	 * @param {obj} items
	 * @returns {string}
	 */
	this.__queue_orb_list = function (items) {
		try {
			for (var orb_name in items)  {
				var item = items[orb_name];
				try {
					this.queue_line(sprintf("$%s   (%s) x %s %s", item.price, item.quantity, item.size, orb_name), 'h4');
					for (var opt in item.opts) {
						this.queue_line(sprintf("%s: %s", item.opts[opt].weight, item.opts[opt].title), 'orb_opt');
						if (item.instructions) this.queue_line(item.instructions, 'opt_note');
					}
				} catch (e) {
					tab_out({
							e_txt: e.message,
							e_stack: sprintf("<pre>%s</pre>", JSON.stringify(e.stack, null, "\t"))
							}, "__format_orbs() error");
				}
			}
		} catch (e) {
			tab_out({
				e_txt: e.message,
				e_stack: sprintf("<pre>%s</pre>", JSON.stringify(e.stack, null, "\t"))
				}, "__queue_orb_list() error");
		}
		return true;
	}

	this.render_virtual_receipt = function() {
		pr(this.virtual_receipts, "XtremePrinter::render_virtual_receipt()", 2 );
		if (this.virtual_receipts.length) return;
		var receipts = $("<ul/>").attr('id', 'virtual-receipt').addClass('virtual-receipt-container');
		for (var i= 0; i < this.virtual_receipts.length; i++) {
			var receipt_wrapper = $("<li />");
			var receipt = $("<ul />").addClass('virtual-receipt receipt');
			for (var j = 0; j < this.virtual_receipts[i].length; j++) {
				$(receipt).append(this.virtual_receipts[i][j]);
			}
			$(receipt_wrapper).append(receipt);
			$(receipts).append(receipt_wrapper);
		}
		$(C.BODY).append(receipts);
		this.virtual_receipts = [];
		return true;
	}

	this.is_xtreme_tablet = function() { return navigator.userAgent == C.XTREME_TABLET_USER_AGENT }

	return this;
}



/**
 * Created by jono on 6/18/15.
 */

// First 4 functions are just utilities (some of which I've been informed are redundant, given jQuery





/**
 * Register an arbitrary number of timed class & attribute changes to an arbitrary set of DOM elements, including
 * dynamically assigned selectors. Essentially tidily contains aesthetic logic for reuse on dynamic sets of DOM elements
 * or stereotyped semantic relationships between DOM elements.
 *
 * @param config A hash of default values to be used in each step when not supplied by the step's description object.
 * @returns {*}
 * @constructor
 */
function EffectChain(config) {
	var effect_chain = Object();
	// Only includes params that every step *must* have, but others can be added
	effect_chain.defaults = {
		interval: undefined,  // for any step, time between it's commencement and the next step's execution
		context: undefined    // for constraining selectors to a parent context, vis-a-vis jQuery
	};
	effect_chain.step_complete = createCustomEvent("ECStepComplete");
	effect_chain.steps = [];
	effect_chain.step_template = {
		target: false,
		context: "body",
		state: undefined,
		interval: undefined,
		next: undefined
	}

	// I'm not 100% sure this feature is a good idea... basically the final step can return something if one likes, but
	// I am sort of just hijacking extant functionality for a purpose that wasn't intended
	effect_chain.exec_result = undefined;

	/**
	 * Optionally sets default values for the EffectChain object
	 * @param config
	 * @returns {effect_chain}
	 */
	effect_chain.init = function (config) {
		// assigns a listener to the object for responding to completed steps
		$(this).on("ECStepComplete", function (e, data) {
			this.exec_step(data.step_index, data.target)
		});

		if (is_object(config)) {
			this.defaults = obj_merge(this.defaults, config);
		}
		for (var key in this.defaults) {
			if (this.defaults[key]) this.step_template[key] = this.defaults[key];
		}
		return this;
	}

	/**
	 *  Adds one or more parsed 'step objects' that describe an effect to the EffectChain object's steps array.
	 *  Basically this is where most of the action goes down.
	 *  Here's a simple and an edge-case complexity sample of what an step object might look like:
	 *
	 *  simple_step = {
	 *      target: 'li.inactive',
	 *      context: 'ul.top_menu',
	 *      interval: 300,  // ms
	 *      state: {
	 *          add: [ 'active', 'focus' ],
	 *          remove: ['inactive'],
	 *          attr: {disabled: false}
	 *      },
	 *      // this could just as easily be supplied in the 'target' key of the next added step;
	 *      // this is a trivial example; the "next" key, in practice, would usually point to a function (see below)
	 *      next: ["div#breadcrumbs", "div#topbar]
	 *      };
	 *
	 *  complex_step = {
	 *      target: undefined,
	 *      context: undefined, // will default to 'body' if a different 'default' isn't supplied on constructon
	 *      interval: 150,
	 *      state: {  // in reality I'm imagining more complicated functions (ie. something $.toggleClass() wouldn't cover!)
	 *          add: [  'focus',
	 *                  function(target) {  return $(target).hasClass('active enabled') ? 'disabled' : undefined;}
	 *               ],
	 *          remove: [ 'inactive',
	 *                     function(target) {  return $(target).hasClass('inactive disabled') ? 'enabled' : undefined;}
	 *                   ]
	 *       },
	 *       next: function(target, context) { return $(target).find('span.icon', context)[0]; }
	 *  }
	 *
	 * @param step
	 * @returns {effect_chain}
	 */
	effect_chain.add = function (step) {
		// if passed an array of step objects the function calls itself on each one
		// if passed a step object function proceeds as normal
		if (is_array(step)) {
			while (step.length > 0) {
				this.add(step.shift());
			}
			return this;
		}
		// ensure all expected keys exist in the step object; undefined values for added keys
		step = obj_merge(this.step_template, step);
		step.exec = function (self, target) {
			/* so at each step, the "target" (ie. DOM element or set of elements) can either be explicitly provided
				by the step object, or passed a target by the previous step; in the case where both are true, the passed
				reference to a DOM element has precedence */
			if (!target) { target = self.target; }

			// a func. that actually performs the class & attribute changes gets written for use in the chain's execution
			var f = function () {
				if ("attr" in self.state) {
					// probably should optionally be an anonymous function, too, like the CSS class toggling logic below
					for (var attr in self.state.attr) $(target, self.context).attr(attr, self.state.attr);
				}
				if ("add" in self.state) {
					if (!is_array(self.state.add)) {
						self.state.add = [self.state.add];
					}
					for (var i = 0; i < self.state.add.length; i++) {
						try { $(target).addClass(self.state.add[i](target)); }
						catch (e) { $(target).addClass(self.state.add[i]); }
					}
				}
				if ("remove" in self.state) {
					if (!is_array(self.state.remove)) { self.state.remove = [self.state.remove]; }
					for (var i = 0; i < self.state.remove.length; i++) {
						try { $(target).removeClass(self.state.remove[i](target)); }
						catch (e) { $(target).removeClass(self.state.remove[i]); }
					}
				}
			};

			$(target, self.context).each(f);

			if (self.next) {
				// should I be using try...catch cascades like this in JS? it's a habit from Python tbh
				try { return self.next(target, self.context); }  // test: next is a function
				catch (e) {
					try {  // test: next is range or specific index of selected elements
						try { var refer_el = $(self.next.selector, context); }
						catch (e) { var refer_el = $(self.next.selector); }
						try { return refer_el.slice(self.next.from, "to" in self.next ? self.next.to : -1) }
						catch (e) { return refer_el[self.next.index]; }
					} catch (e) {
						return $(self.next); // test: next is a string selector
					}
				}
			}
			return;
		}
		this.steps.push(step);
		return this;
	};

	/**
	 * Executes a passed step in the effect chain; triggers ECStepComplete event on completion.
	 * @param step_index
	 * @param target
	 */
	effect_chain.exec_step = function (step_index, target) {
		var self = this;
		var step = this.steps[step_index]
		var result = step.exec(step, target);  // may be target of next step, or else some final output
		step_index++;
		if (this.steps.length > step_index) {
			setTimeout(function () {
				$(self).trigger("ECStepComplete", {step_index: step_index, target: result})
			}, step.interval);
		} else {
			this.exec_result = result;
		}

	}
	/**
	 *  Sums all step intervals except the last one; just useful information, not required for execution of the chain
	 * @returns {number}
	 */
	effect_chain.duration = function () {
		var t = 0;
		for (var i = 0; i < this.steps.length - 1; i++)  { t += this.steps[i].interval; }
		return t;
	}

	/**
	 * Starts the series of calls to exec_step(); allows for an initial element to be explicitly passed, which, depending
	 * on how the subsequent steps are configured, can dramatically alter the results of EffectChain object.
	 * @param initial_target
	 */
	effect_chain.run = function (initial_target) { this.exec_step(0, initial_target); }

	effect_chain.init(config);
	return effect_chain;
}


var eChain = new EffectChain();

eChain.add([
      {
        target: 'li.inactive',
        context: 'ul.top_menu',
        interval: 300, // ms
        state: {
          add: ['active', 'focus'],
          remove: ['inactive'],
          attr: { disabled: false}
        },
          // this could just as easily be supplied in the 'target' key of the next added step;
          // this is a trivial example; the "next" key, in practice, would usually point to a function (see below)
          next: ["div#breadcrumbs", "div#topbar"]
      },
      {
        target: undefined,
        context: undefined, // will default to 'body' if a different 'default' isn't supplied on constructon
        interval: 150,
        state: { // in reality I'm imagining more complicated functions (ie. something $.toggleClass() wouldn't cover!)
          add: ['focus',
            function(target) {
              return $(target).hasClass('active enabled') ? 'disabled' : undefined;
            }
          ],
          remove: ['inactive',
            function(target) {
              return $(target).hasClass('inactive disabled') ? 'enabled' : undefined;
            }
          ]
        },
        next: function(target, context) {
          return $(target).find('span.icon', context)[0];
        }
      }
]);

eChain.add([{
    target: 'li.active',
    context: 'div.topbar',
    interval: 150, // ms
    state: {
      add: ['inactive'],
      remove: ['active']
    },
    next: function(target, context) {
        return target
      } // ie. return whatever was passed to this step at runtime
  }, {
    target: undefined,
    context: 'li.active',
    interval: 150,
    state: {
      add: ['active']
    },
    next: function(target, context) {
      return $(target).data('pane');
    }
  }, {
    interval: 10,
    state: {
      add:['inbound'],
      remove: ['hidden']
    }
  },
    {
    target: ".content-pane.active",
    interval: 500, // can't remove display:none simultaneously with adding a transition
    state: {
      add:['shelved-left']
    }
  }, {
    target:".content-pane.active",
    interval:10,
    state: {
      add:['hidden'],
      remove:['active']
    }
  },

{
  target:'.conent-pane.inbound',
    interval: 500, // can't remove display:none simultaneously with adding a transition
    state: {
      add:['active'],
      remove: ['shelved-left', 'inbound']
    },
    next: function(target, context) {
      return $(target).children('h1')[0];
    }
  },
    {
      interval: 0, // last step doesn't matter
      state: {
           remove:['fade-out']
           }
    }

]);
/**
 * Created by jono on 1/24/15.
 */
XtremeLayout = function() {};

XtremeLayout.prototype = {
	page_name: undefined,
	constructor: XtremeLayout,
	page_height: undefined,
	init: function () {
		var self = this;
		this.jq_binds();
		if (this.page_name == window.page_name.splash) $(as_class(FX.detach)).each(function () { XBS.layout.detach(this);});
		this.page_height = window.innerHeight - ($(XSM.global.topbar).innerHeight() + 3 * C.REM) + C.PX;
		$(XSM.global.page_content).css({minHeight: self.page_height});
		$(FX.fill_parent).each(function() { self.match_parent_dimensions(this)});

		// TODO: this should be an effect chain
		setTimeout(function () {
			$(XSM.topbar.social_loading).addClass(FX.fade_out);
			setTimeout(function () {
				$(XSM.topbar.social_loading).hide();
				setTimeout(function () {
					$(XSM.topbar.icon_row).show();
					setTimeout(function () {
						$(XSM.topbar.icon_row).removeClass(FX.fade_out);
						$(XSM.topbar.icon_row).removeClass(FX.true_hidden);
					}, 30);
				}, 30);
			}, 300);
		}, 800);

		if ( is_mobile() ) return;

		$(XSM.global.footer).css({top: $(XSM.global.page_content).innerHeight()});
		this.fasten(XSM.menu.self).css({overflow: "hidden"});
	},
	jq_binds: function() {
		var self = this;
		// bind_activizing_lists
		$(C.BODY).on(C.CLK, XSM.global.activizing_list, function (e) { self.activize(e.currentTarget) });

		// bind_multi_activizing_siblings
		$(C.BODY).on(C.CLK, XSM.global.multi_activizing, function (e) { self.multi_activize(e.currentTarget) });

		// bind_float_labels:
		$(C.BODY).on(C.MOUSEENTER, as_class(FX.float_label), null, function (e) {
			self.toggle_float_label($(e.currentTarget).data('float-label'), C.SHOW);
		});
		$(C.BODY).on(C.MOUSEOUT, as_class(FX.float_label), null, function (e) {
			self.toggle_float_label($(e.currentTarget).data('float-label'), C.HIDE);
		});

		// bind_splash_links
		$(C.BODY).on(C.CLK, XSM.splash.splash_link, null, function (e) {
			XBS.splash.redirect($(e.currentTarget).data('url'));
		});

		// bind_topbar_hover_links
		$(C.BODY).on(C.MOUSEENTER, XSM.topbar.hover_text_link, null, function (e) {
			if (!$(e.currentTarget).hasClass(FX.disabled)) {
				self.toggle_topbar_hover_text($(e.currentTarget).data('hover_text'));
			}
		});
		$(C.BODY).on(C.MOUSEOUT, XSM.topbar.hover_text_link, null, function (e) {
			if (!$(e.currentTarget).hasClass(FX.disabled)) self.decay_topbar_hover();
		});
		// window_resize_listener
		if (window.page_name == XSM.page_name.splash) $(window).on("resize", XBS.splash.render);
	},

	/**
	 *  Uses 'respect' (ie. X or y) data-attribute of an element to force it's height/width to satisfy ratio given in
	 *  aspect-ratio data attribute.
	 *
	 * @param targets
	 * @returns {boolean}
	 */
	assert_aspect_ratio: function (targets) {
		$(targets).each(function () {
			$(this).removeAttr("style");
			var data = $(this).data("aspectRatio");
			var ratio = Number(data.x) / Number(data.y);
			var respect = data.respect;

			/* make sure the ratio is indeed a ratio */
			if (!isInt(ratio) && !isFloat(ratio)) {
				var ratioVals = ratio.split("/");
				if (ratioVals.length !== 2) throw "Invalid value for argument 'ratio'; must be int, float or string matching n/m";
				ratio = Number(ratioVals[0]) / Number(ratioVals[1]);
			}

			var dimensions = {width: $(this).innerWidth(), height: $(this).innerHeight()};
			if (respect != "y") {
				dimensions.height = dimensions.width / ratio;
			} else {
				dimensions.width = dimensions.height * ratio;
			}
			$(this).css(dimensions);
		});
		return true;
	},
	activize: function (element) {
		if (isEvent(arguments[0])) element = element.currentTarget;
		if ($(element).hasClass(FX.inactive)) {
			$(element).removeClass(FX.inactive)
				.addClass(FX.active)
				.siblings(XSM.global.active_list_item)
				.each(function () {
					$(this).removeClass(FX.active).addClass(FX.inactive);
				});
		}
	},
	detach: function (element) {
		var is_static = $(element).data('static');
		var height = $(element).innerHeight();
		$(element).css({height: height});
		if (is_static) XBS.layout.fasten(element);
		return $(element);
	},
	dismiss_modal: function (modal, action) {
		var debug_this = 0;
		if (debug_this > 0) pr([modal, action], "XBS.layout.dismiss_modal(modal, action)", 2);
		$(XSM.modal.primary).addClass(FX.slide_up);
		$(XSM.modal.flash).addClass(FX.slide_up);
		$(XSM.modal.splash).addClass(FX.slide_up);
		$(XSM.modal.orb_card).hide('clip');
		setTimeout(function () {
			$(XSM.modal.overlay).addClass(FX.fade_out);
			setTimeout(function () { $(XSM.modal.overlay).hide(); }, 300);
		}, 300);
		if (action) {
			switch (action) {
				case "reset-user-activity":
					$(XSM.menu.user_activity_panel).children().each(function () {
						if ($(this).hasClass(FX.active)) {
							$(this).removeClass(FX.active).addClass(FX.inactive);
						}
						if ($(this).hasClass(FX.active_by_default)) $(this).addClass(FX.active);
					});
					break;
				case "unstash":
					window.menu.init();
					window.menu.unstash_menu();
					break;
			}
		}
		return true;
	},
	fasten: function (selector) {
		var debug_this = 0;
		//todo: error handling & logic for objects vs arrays
		var selectors = ( is_array(selector) ) ? selector : [selector];
		for (var i in selectors) {
			var sel = selectors[i];
			var offset = $(sel).offset();

			var dims = {width: Math.floor( $(sel).outerWidth() + px_to_int($(sel).css("padding-left"))),
				height: Math.floor( $(sel).outerHeight() + px_to_int($(sel).css("padding-top")))};
			var styles = {position: "fixed", top: offset.top, left: offset.left, height: dims.height, width: dims.width};
			if (debug_this > 1) pr(styles);
			$(sel).css(styles).addClass(FX.fastened);
		}
		return  (is_array(selector) ) ? selector : $(selector);
	},
	hovertext_switch: function(target) {
		var incoming = $("span.text.inactive", target)[0];
		var outgoing = $("span.text.active", target)[0];
		$(outgoing, target).addClass(FX.fade_out);
		setTimeout(function() {
			$(incoming).removeClass([FX.hidden, FX.inactive].join(" ")).addClass(FX.active);
			$(outgoing).removeClass(FX.active).addClass([FX.hidden, FX.inactive].join(" "));
			setTimeout( function() {
				$(incoming).removeClass(FX.fade_out);
			}, 10)
		}, 300);
	},
	match_parent_dimensions: function(element) {
		var parent = $(element).parent();
		$(element).css({height: $(parent).innerHeight(), width: $(parent).innerWidth()});
	},
	multi_activize: function (element) {
		if ($(element).hasClass('active')) {
			$(element).removeClass('active').addClass('inactive')
				.children(as_class(FX.checked)).each(function () {
					$(this).removeClass(FX.checked).addClass(FX.unchecked);
				});
		} else if ($(element).hasClass('inactive')) {
			$(element).removeClass('inactive').addClass('active')
				.children(as_class(FX.unchecked)).each(function () {
					$(this).removeClass(FX.unchecked).addClass(FX.checked);
				});
		}
	},
	reveal_orb_card_modal: function() {
		$(XSM.modal.orb_card).show('clip');
	},
	resize_modal: function (modal) {
		if (!modal) return;
		var modal_width;
		var modal_max_height;
		var modal_left;
		var modal_top;
		if (modal == XSM.modal.primary) {
			modal_width = 1200 / 12 * 8;
			modal_max_height = 0.8 * $(window).innerHeight();
			modal_top = 0.1 * $(window).innerHeight();
			var pm_width = 0.8 * $(window).innerWidth();
			if (pm_width > modal_width) {
				modal_left = ($(window).innerWidth() - modal_width) / 2;
			} else {
				modal_left = 0.1 * $(window).innerWidth();
			}
			if (pm_width < modal_width) modal_width = pm_width;
		}
		if (modal == XSM.modal.flash) {
			modal_width = 40 * C.REM;
			modal_left = (window.innerWidth / 2) - ( modal_width / 2);
			modal_max_height = "default";
			modal_top = 2 * C.REM;
		}

		$(modal).css({
			top: modal_top,
			left: modal_left,
			width: modal_width,
			maxHeight: modal_max_height
		});
	},
	toggle_float_label: function (label, state) {
		if (state == C.SHOW) $(XSM.menu.float_label).html(str_to_upper(label)).addClass(FX.exposed);
		if (state == C.HIDE) $(XSM.menu.float_label).removeClass(FX.exposed).html('');

		return true;
	},
	toggle_loading_screen: function () { $(XSM.global.loadingScreen).fadeToggle(); },
	toggle_topbar_hover_text: function (hover_text, state) {
		var debug_this = false;
		if ($(XSM.topbar.hover_text_label_outgoing).html() == hover_text) return;
		if (debug_this) pr([hover_text, state], "toggle_topbar_hover_text(hover_text, state)");
		$(XSM.topbar.hover_text_label_outgoing).removeClass("decay").addClass(FX.slide_right);
		$(XSM.topbar.hover_text_label_incoming).html(hover_text).removeClass(FX.true_hidden);
		setTimeout(function () {
			$(XSM.topbar.hover_text_label_incoming).removeClass(FX.slide_left);
			setTimeout(function () {
				setTimeout(function () {
					$(XSM.topbar.hover_text_label_outgoing).remove();
					setTimeout(function () {
						$(XSM.topbar.hover_text_label_incoming).removeClass("incoming").addClass("outgoing")
						$(XSM.topbar.hover_text_label).append("<span class='incoming slide-left true-hidden'></span>");
					}, 10);
				}, 280);
			}, 10);
		}, 10);
	},
	decay_topbar_hover: function () {
		var id = (new Date).getTime();
		$(XSM.topbar.hover_text_label_outgoing).addClass("decay " + id);
		setTimeout(function () {
			$(XSM.topbar.hover_text_label_outgoing + ".decay." + id).addClass(FX.fade_out);
			setTimeout(function () {
				$(XSM.topbar.hover_text_label_outgoing + ".decay." + id).replaceWith(
					"<span class='outgoing'>Halifax loves pizza and we love halifax!</span>");
			}, 300);
		}, 1000);
		return true;
	}
};

/**
 * Created by jono on 1/24/15.
 */

XtremeMenu = function() {}

XtremeMenu.prototype = {
	constructor: XtremeMenu,
	XT: undefined,
	init: function() {
		this.XT = window.xtr;
		if (this.XT.page_name == XSM.page_name.vendor_ui) return;
		this.XT.orbcard.reset_filters(C.CHECK);
		this.XT.cart.set_order_method();
	},
	add_to_cart: function () {
		// todo: ajax fallbacks
		$.ajax({
			type: C.POST,
			url: "orders/add_to_cart",
			data: $(XSM.menu.orb_order_form).serialize(),
			success: function (data) {
				data = JSON.parse(data);
				if (data.success == true) {
					XT.cart.add_to_cart();
					$(XSM.modal.orb_card).show('clip');
					$(XSM.topbar.topbar_cart_button).show()
					setTimeout(function () {
						$(XSM.topbar.topbar_cart_button).removeClass(FX.fade_out);
					}, 300);
				}
			}
		});
	},
	stash: function () {
			var orb_card_timeout = 0;
			if (this.orb_card.exposed_face == C.BACK_FACE) {
				orb_card_timeout = 960;
				this.orb_card.show_front_face();
			}
			setTimeout(function () {
				$(XSM.menu.user_activity_panel).addClass(FX.slide_up);
				setTimeout(function () {
					$(XSM.menu.orb_card_wrapper).addClass([FX.slide_left, FX.fade_out].join(" "));
					setTimeout(function () {
						$(XSM.menu.orbcat_menu).addClass([FX.slide_right, FX.fade_out].join(" "));
					}, 300);
				}, 300);
			}, orb_card_timeout);
		},
	unstash: function () {
		$(XSM.menu.orbcat_menu).removeClass([FX.slide_right, FX.fade_out].join(" "));
		// todo: this is a bit of a hack; the over-all logic should preclude this next line, but,
		// todo: "activizing" gets toggled during the orbcard flip, and if it's in the wrong state, toggles inversely
		// todo: making orb-opts unselectable
		$(XSM.menu.orb_card_stage_menu).addClass(FX.activizing);
		setTimeout(function () {
			$(XSM.menu.orb_card_wrapper).removeClass([FX.slide_left, FX.fade_out].join(" "));
			setTimeout(function () {
				$(XSM.menu.user_activity_panel).removeClass(FX.slide_up);
			}, 300);
		}, 300);
		XT.menu.set_order_method();
		this.orbcard.reset_stage();
	}
}

XtremeCart = function( cart_id ) {
	this.init(cart_id);
	return this;
}
XtremeCart.prototype = {
	constructor: this.XtremeCart,
	configured: [], // CONFIRMED BY SERVER
	configuring: [],
	cart_id: undefined,
	initialized: false,
	session_data: undefined,
	XT: undefined,

	init: function (cart_id) {
		var debug_this = 0
		if (debug_this > 0 ) pr(cart_id, "XtremeCart.init(cart_id)", 2);
		this.XT = window.xtr;
		this.cart_id = cart_id;
		this.import_cart(false);
	},
	import_cart: function(session_data) {
		var self = this;
		if ( !session_data ) {
			$.get(["cart", this.cart_id].join(C.DS), function(response) { self.import_cart($.parseJSON(response).data)});
			return;
		}
		this.session_data = session_data;
		for (var uid in this.session_data.Order) {
			var orb =  new Orb(uid);
			orb.import_data(session_data.Order[uid]);
			if ( this.find_by_uid(uid) ) this.delete(uid, C.CONFIGURING);
			this.configured.push( orb );
		}

		this.configuring = [];
	},

	/**
	 * Infers whether identifier string is a Orb model id or Orb UID
	 * @param identifier
	 * @returns {boolean}
	 */
	is_uid: function(identifier) { return identifier.split("_").length > 1},

	/**
	 * Generates a unique id from Orb model id and current time
	 * @param orb_id
	 * @returns {*|string}
	 */
	generate_uid: function(orb_id) { return [orb_id, now()].join("_");},

	/**
	 * Gets Orb model id from UID str
	 * @param uid
	 * @returns {*}
	 */
	id_from_uid: function(uid) { return uid.split("_")[0] },

	/**
	 * Uhhhh... I don't think this current;y works
	 * @param orb_id
	 */
	cancel_config: function (orb_id) {
		var uid = this.generate_uid(orb_id);
		this.configuring[uid] = new Orb(uid);
	},

	/**
	 * Removes an Orb object from cart.configur(ed/ing) and reindexes array
	 *
	 * @param uid
	 * @param source
	 * @returns {boolean}
	 */
	delete: function(uid, source) {
		var search_in = source == C.CONFIGURED ? this.configured : this.configuring;
		for (var i = 0; i < search_in.length; i++) {
			if (search_in[i].uid == uid) {
				array_remove(search_in, i);
				return true;
			}
		}
		return false;
	},

	/**
	 * Searches cart.configuring for open configurations by either uid or id
	 * @param identifier
	 * @returns {*}
	 */
	find_configuration: function (identifier) {
		var orb_config =  this.is_uid(identifier) ? this.find_by_uid(identifier) : this.find_by_id(identifier, true);
		return orb_config ? orb_config[0] : false;
	},

	// DEPRECATED I THINK....
	has_orb: function (identifier, in_configuring, as_int) {
		var found = 0;
		var context = in_configuring ? this.configuring : this.orbs;
		if ( this.is_uid(identifier) ) {
			found = identifier in context
		} else {
			as_int === false; // ie. if not otherwise specified, as_int should be true for searching by ids
			for (var uid in context) { if (context[uid].id == identifier) found += 1 }
		}
		if (as_int === false) return found > 1 || found === true;
		if ( found === true  ) return 1;
		return found === false ?  0 : found;
	},

	add_to_cart: function (orb_id) {
		var uid = this.generate_uid(orb_id);
		if (!this.has_orb(uid, true)) {
			this.configuring.push( new Orb(uid) );
		}
		return true;
	},

	orb_attr: function (orb_uid, attribute, in_configuration) {
		if (!this.has_orb(orb_uid, in_configuration)) return false;
		if (!attribute) return false;
		attribute = this.html_id_from_attr(attribute)
		if (attribute.is_id) return this.id_from_uid(orb_uid);
		var context = in_configuration ? this.configuring : this.orbs;
		if (attribute.str in context[orb_uid]) return context[orb_uid][attribute.str]

		if (attribute.is_orbopt) {
			if (attribute.opt_id in context[orb_uid]["orbopts"]) {
				try {
					if ('weight' in  context[orb_uid].orbopts[attribute.opt_id]) {
						return context[orb_uid].orbopts[attribute.opt_id]['weight']; // orbs
					}
				} catch (e) {
					return context[orb_uid].orbopts[attribute.opt_id]; // configuring
				}
			} else {
				return -1;
			}
		}
		return false;

	},


	/**
	 * Returns orb with matching uid if found, else false.
	 * @param uid
	 * @returns {*}
	 */
	find_by_uid: function(uid, source) {
		var search_in = source == C.CONFIGURED ? this.configured : this.configuring;
		for (var i = 0; i < search_in.length; i++) {
			if (search_in[i].uid == uid) return [search_in[i]];
		}
		return false;
	},

	/**
	 * Returns array of Orbs in cart.configuring or first such Orb, else false.
	 * @param id
	 * @param first
	 * @returns {*}
	 */
	find_by_id: function(id, first, source) {
		var search_in = source == C.CONFIGURED ? this.configured : this.configuring;
		var found = [];
		for (var i = 0; i < search_in.length; i++) {
			if (search_in[i].id == id) {
				if (first) return [search_in[i]];
				found.push( search_in[i] );
			}
		}
		return found.length > 0 ? found : false;
	},
	/**
	 * Prepares Orbcard form for new Orb configuration or loads configuration in progress
	 * @returns {boolean}
	 */
	configure: function (id, price_rank) {
		var debug_this = 1;
		if (debug_this > 0) pr(id, "XT.cart.configure()", 2);
		var orb = this.find_configuration(id);
		if (!orb)  {
			orb = new Orb( this.generate_uid(id) );
			this.configuring.push(orb);
		}
		if (price_rank) orb.price_rank = price_rank;
		$(XSM.menu.orb_order_form_quantity).val(orb.quantity);
		$(XSM.menu.orb_order_form_price_rank).val( orb.price_rank );
		$(XSM.menu.orb_order_form_note).val(orb.note);
		$(XSM.menu.orb_order_form_orb_opts).each(function () {
			orb.orbopts.push = new Orbopt(new OrbInflector( $(this).attr('id')).opt_id(), $(this).val() );
		});
		this.current_orb = orb;
//      will deal with pricing and stuff, not updated to new cart methods/structure
//		XT.cart.inspect_configuration(uid);
		return orb; // for chaining
	},
	set_order_method: function (method) {
		if (method)  {
				this.XT.data.Service.order_method = method;
			} else {
				method = this.data.Service.order_method;
			}
			if (!method) {
				this.data.Service.order_method = C.JUST_BROWSING;
				method = C.JUST_BROWSING;
			}
			$(XSM.menu.user_activity_panel_items).each(function () {
				var route = $($(this).children()[0]).data('route');
				if (route) {
					if (route.split(C.DS)[2] == method) {
						$(this).removeClass(FX.inactive).addClass(FX.active);
					} else {
						$(this).removeClass(FX.active).addClass(FX.inactive);
					}
				}
			});

		},
	weight_to_int: function(weight) {
		switch (weight) {
			case "-1":
				return -1;
			case "F":
				return 1;
			case "D":
				return 2;
			default:
				return 0.5;
		}
	},
	inspect_configuration: function(uid) {
		var orb = this.find_by_uid(uid);
		var flags = new OptflagMap();

		for (var opt_id in orb.orbopts) {
			var opt = orb.orbopts[opt_id];
			for (var id in opt.optflags.length) {
				if ( opt.optflags[id] in opt_weights ) {
					var weight_val = this.weight_to_int(opt.weight);
					this.pricable_optflags[opt.optflags[id]] += weight_val > -1 ? weight_val : 0;
				}
			}
		}
		this.XT.menu.enforce_opt_rules(orb, opt_weights);
	},
	validate_order_review: function() {
		var valid = true;
		this.XT.data.order.payment = $(XSM.modal.payment_method_input).val();
		if (this.Service.order_method == C.JUST_BROWSING) valid = false;
		if (this.Service.order_method == C.DELIVERY ) {
			if ( !this.Service.address) valid = false;
			if ( !this.Service.payment) valid = false;
		}
		if (valid) {
			$(XSM.modal.finalize_order_button).removeClass(XSM.effects.disabled);
		}
	}

};


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
								XBS.layout.dismiss_modal(XSM.modal.primary);
								XBS.data.Service = response.cart.Service;
								XBS.menu.set_order_method();
							} catch (e) {
								pr(e, null, true);
								// todo: something... with... this... eror?
							}
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

/**
 * Created by jono on 1/24/15.
 */
var xbs_vendor = {
	pending_orders:[],
	last_check: 0,
	last_tone_play: 0,
	init: function() {
		var vendor_page = $("html").find(XSM.vendor.self)[0]

		if (vendor_page) {
			XBS.data.is_vendor_page = true;
			setTimeout(function() {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
			}, 3000);
		}

		$(XSM.vendor.order_accept_button).on(C.VMOUSEDOWN, function(e) { $(this).addClass(XSM.effects.pressed); });
		var bg_string = function(success_stop, ready_stop) {
			var success_color = "rgba(50,255,50,1)";
			var ready_color = "rgba(125,185,232,1)";
			var bg_str = "-webkit-linear-gradient(left, " + success_color + " " + success_stop + "%,";
			bg_str += ready_color + " " + ready_stop +"%," + ready_color + " 100%)";
			return bg_str;
		}
		$(C.BODY).on(C.VMOUSEMOVE, XSM.vendor.order_accept_button_pressed, null, function(e) {
			var success_stop = Math.round( 100 * (e.pageX - $(XSM.vendor.order_accept_button).offset().left) / 512);
			var ready_stop = Math.round( 100 * (e.pageX - $(XSM.vendor.order_accept_button).offset().left) / 512) + 1;
			if (success_stop < 95) {
				$(XSM.vendor.order_accept_button).css({ background: bg_string(success_stop, ready_stop) });
			} else {
				$(XSM.vendor.order_accept_button).attr("style", null);
				$(XSM.vendor.order_accept_button).addClass('launching');
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:"vendor_accept/print", trigger:this});
			}
		});
		$(XSM.vendor.order_accept_button).on(C.VMOUSEOUT, function(e) {
			$(this).removeClass(XSM.effects.pressed);
			$(XSM.vendor.order_accept_button).css({background: bg_string(0, 0) });
		});
		$(XSM.vendor.order_accept_button).on(C.VMOUSEUP, function(e) {
			$(this).removeClass(XSM.effects.pressed);
			$(XSM.vendor.order_accept_button).css({background: bg_string(0, 0) });
		});

		return true;
	},
	next: function() {
		var debug_this = 1;
		if (debug_this > 0) pr("<no_args>", "XBS.vendor.next()", 2);
		if (XBS.vendor.pending()) {
			$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
		} else {
			$(XSM.vendor.next_order).addClass(XSM.effects.slide_up);
			$(XSM.vendor.back_splash).show();
			setTimeout(function() {
				setTimeout( function() {
					$(XSM.vendor.back_splash).removeClass(XSM.effects.fade_out);
					$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: "vendor_get_pending", trigger:{}});
				}, 150);
			}, 150);
		}
		return true;
	},
	current: function(order) {
		if (order) XBS.vendor.current_order = order;
		return XBS.vendor.current_order === null ? false : XBS.vendor.current_order
	},
	pending: function() {
		var length = parseInt(XBS.vendor.pending_orders.length);
		return length > 0 ? length : false;
	},
	post_orders: function(orders) {
		var debug_this = 0;
		if (debug_this > 0) pr({orders:orders, orders_length:orders.length}, "vendor.post_orders(orders)", 2);
		XBS.vendor.pending_orders = orders;
		XBS.vendor.update_pending_display()
		XBS.vendor.update_current_order()
		return;
	},
	update_current_order: function() {
		if ( !XBS.vendor.current() && XBS.vendor.pending() ) {
			/* Get a fresh copy of the basic order display HTML from a hidden element in the page. */
			var order_content = $.parseHTML($(XSM.vendor.order_content_sample).html());
			var order = XBS.vendor.current(XBS.vendor.pending_orders[0]);
			var food = "";
			for (var i in order.food) food += XSM.generated.vendor_orb_desc(i, order.food[i]);
			$($(order_content).find(XSM.vendor.order_address)[0]).html(order.address);
			$($(order_content).find(XSM.vendor.customer_name)[0]).html(order.customer);
			$($(order_content).find(XSM.vendor.food_list)[0]).html(food);
			if ( $(XSM.vendor.next_order).hasClass(XSM.effects.slide_up) ) {
				$(XSM.vendor.order_content_detail).replaceWith(order_content)
				$(XSM.vendor.order_content_detail).removeClass(XSM.effects.fade_out);
				$(XSM.vendor.back_splash).addClass(XSM.effects.fade_out);
				setTimeout(function() {
					$(XSM.vendor.back_splash).hide();
					setTimeout(function() {
						if (now() - XBS.vendor.last_tone_play > 10000) {
							XBS.printer.play_order_tone();
							XBS.vendor.last_tone_play = now();
						}
						$(XSM.vendor.next_order).removeClass(XSM.effects.slide_up);
					}, 30);
				}, 300);
			} else {
				$(XSM.vendor.order_content_detail).addClass(XSM.effects.fade_out);
				setTimeout(function() {
					$(XSM.vendor.order_content_detail).replaceWith(order_content);
					setTimeout(function() {
						$(XSM.vendor.order_content_detail).removeClass(XSM.effects.fade_out);
					},30);
				}, 300)
			}
		}
	},
	update_pending_display: function() {
		var debug_this = 0;
		var current_displayed_count = parseInt($(XSM.vendor.order_count).html());
		var pending = XBS.vendor.pending_orders.length - 1;
		if (debug_this > 1) pr([pending, current_displayed_count], "vendor.update_pending_display()", 2);
		if (pending > 0 ) $(XSM.vendor.order_count_bubble).removeClass(XSM.effects.fade_out);
		else $(XSM.vendor.order_count_bubble).removeClass(XSM.effects.fade_out);

		if (current_displayed_count != pending) {
			$(XSM.vendor.order_count).addClass(XSM.effects.fade_out);
			setTimeout(function() {
				$(XSM.vendor.order_count).html(pending);
				setTimeout(function() {
					$(XSM.vendor.order_count).removeClass(XSM.effects.fade_out);
				}, 30);
			}, 150);
		}
		return true;
	}
};
/**
 * Created by jono on 4/30/15.
 * TODO: limit menu_options tab so that "premium", "cheese" & "sauce" are mutually exclusive
 *
 */
var xbs_vendor_ui = {
	orbopt_pricelist_id:-1,

	init: function () {
		XBS.vendor_ui.loading_screen(0);
		XBS.vendor_ui.fix_breakouts();
		$(XSM.vendor_ui.ui_tabs).tabs();
		for ( var table in {menu:null, opts:null } ) {
			XBS.vendor_ui.data_tables(table);
		}
	},
	loading_screen: function(last_height) {
		var ui_height = $(XSM.vendor_ui.ui_tabs).innerHeight();
		if (last_height == ui_height) {
			$("#loading-screen").addClass(FX.fade_out);
			setTimeout(function(){ $("#loading-screen").addClass(FX.hidden); }, 300);
		} else {
			setTimeout( function() { XBS.vendor_ui.loading_screen(ui_height)}, 300);
		}
	},
	fix_breakouts: function() {
		$(FX.breakout).each(function () {
			$(this).css({
				left: (0.5 * $(window).width() - 400) + "px", // all breakouts are 800px wide, vendor.scss ~L514
				top: "300px"
			})
		});
	},
	data_tables: function (table) {
		var tables = {
			menu: {
				id: XSM.vendor_ui.menu_table,
				cols: [
					{ width: 200},
					{ width: 400},
					{ width: 100},
					{ width: 350},
					{ width: 50}
				]
			},
			opts: {
				id: XSM.vendor_ui.orbopts_table,
				cols: [
					{ width: 100 },
					{ width: 100 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 },
					{ width: 76 }
				]
			}
		};
		$(tables[table].id).dataTable({
				bDestroy: true,
				bJQueryUI: true,
				bDeferRender: false,
				autoWidth: false,
				columns: tables[table]
		});
	},

	save_orb: function (orb_id, attribute, replacement) {
		var cell_id = as_id(["orb", orb_id, attribute].join("-"));
		var saved_value = null;
		switch (attribute) {
			case 'title':
				saved_value = $("input[name='Orb[title]']", cell_id).val();
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'orbcat':
				var orbcat_id = $("select", cell_id).val();
				$($("select", cell_id).find("option")).each(function () {
					if (Number($(this).attr('value')) == orbcat_id) {
						saved_value = $(this).text();
						$(this).attr('selected', 'selected');
					} else {
						$(this).removeAttr('selected');
					}
				});
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'description':
				saved_value = $("textarea", cell_id).val();
				$(XSM.vendor_ui.orb_attr_display, cell_id).html(saved_value);
				break;
			case 'prices':
				$(cell_id).html(replacement);
				break;
		}
		XBS.vendor_ui.cancel_cell_editing('orb', orb_id, attribute);
	},
	set_orbopt_pricelist_focus: function() {
		$("#orbopt-pricelist-add input[name='Pricelist[id]']", C.BODY).val($("#orbopt-pricelist-select").val());
		XBS.vendor_ui.toggle_pricelist_add("stow");
		$( "#orbopt-pricelist-buttons .modal-button.disabled", C.BODY).removeClass(FX.disabled).addClass(FX.enabled);
	},
	edit_orbopt_pricelist: function() {
		var url = ["edit-orbopt-pricing", $("#orbopt-pricelist-select").val()].join(C.DS);
		$("#orbopt-pricelist-add-container").load([XBS.data.cfg.root,url].join(C.DS), function() { XBS.vendor_ui.toggle_pricelist_add("reveal", true); });
	},
	toggle_pricelist_add: function(state, preserve_fields) {
		if (!preserve_fields || state != "reveal") {
			$("input", "#orbopt-pricelist-add-container").each(function() { $(this).val("");});
		}
		if (state == "reveal") {
			$( ".modal-button.enabled", "#orbopt-pricelist-buttons").removeClass(FX.enabled).addClass(FX.disabled);
			$("#orbopt-pricelist-add-container").removeClass(FX.hidden);
			setTimeout(function() { $("#orbopt-pricelist-add-container").removeClass(FX.fade_out); }, 10);
		}
		if (state == "stow") {
			$("#orbopt-pricelist-add-container").addClass(FX.fade_out);
			setTimeout(function() { $("#orbopt-pricelist-add-container").addClass(FX.hidden); }, 300);
		}
	},
	delete_orbopt_pricelist: function(action) {
		var pricelist_id = $("#orbopt-pricelist-select").val();
		switch (action) {
			case "confirm":
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:["orbopt_pricelist", "delete","delete", pricelist_id].join(C.DS), trigger:{}});
				break;
			case "warn":
				$("#delete-orbopt-pricelist-confirmation").removeClass(FX.hidden);
				setTimeout(function() { $("#delete-orbopt-pricelist-confirmation").removeClass(FX.fade_out); }, 10);
				break;
			case "cancel":
				$("#delete-orbopt-pricelist-confirmation").addClass(FX.fade_out);
				setTimeout(function() { $("#delete-orbopt-pricelist-confirmation").addClass(FX.hidden); }, 300);
				break;
			case "print_opts":
				XBS.vendor_ui.delete_orbopt_pricelist("cancel"); // close the cancellation warning
				XBS.vendor_ui.reload_orbopt_pricelist_config();
				break;
		};
	},
	reload_orbopt_pricelist_config: function() {
		var opt_id = $("#orbopt-pricelist-select-form", C.BODY).data('opt');
		$(XBS.routing).trigger(C.ROUTE_REQUEST, {request:["orbopt_pricelist","launch","false",opt_id].join(C.DS), trigger:{}});
	},
	edit_cell: function (table, id, attribute) {
		var display_element = XSM.vendor_ui.orb_attr_display;
		var edit_element = XSM.vendor_ui.orb_attr_edit;
		var cell_id = as_id([table, id, attribute].join("-"));
		if (table == "orbopt") {
			display_element = XSM.vendor_ui.orbopt_attr_display;
			edit_element = XSM.vendor_ui.orbopt_attr_edit;
		}
		pr([table, id, attribute, cell_id], "edit_cell");
		$(display_element, cell_id).addClass(FX.fade_out);
		setTimeout( function() {
			$(display_element, cell_id).addClass(FX.hidden);
			$(edit_element, cell_id).removeClass(FX.hidden);
			setTimeout(function () { $(edit_element, cell_id).removeClass(FX.fade_out); }, 30);
		}, 300);
	},
	cancel_cell_editing: function (table, id, attribute) {
		var display_element = XSM.vendor_ui.orb_attr_display;
		var edit_element = XSM.vendor_ui.orb_attr_edit;
		var cell_id = as_id([table, id, attribute].join("-"));
		if (table == "orbopt") {
			display_element = XSM.vendor_ui.orbopt_attr_display;
			edit_element = XSM.vendor_ui.orbopt_attr_edit;
		}
		pr([table, id, attribute, cell_id], "cancel_cell_editing");
		$(edit_element, cell_id).addClass(FX.fade_out);
		setTimeout(function() {
			$(edit_element, cell_id).addClass(FX.hidden);
			$(display_element, cell_id).removeClass(FX.hidden);
			setTimeout(function() { $(display_element, cell_id).removeClass(FX.fade_out);}, 30)
		}, 300);
	},
	save_orbopt: function(response, data) {
		var cell_id;
		if (data.attribute == "pricing") {
			cell_id = XSM.vendor_ui.orbopt_pricelist_add;
			$(cell_id, C.BODY).addClass([FX.hidden, FX.fade_out].join(" "));
		} else {
			cell_id = as_id(["orbopt", data.id, data.attribute].join("-"));
		}
		switch (data.attribute) {
			case "title":
				$(XSM.vendor_ui.orbopt_attr_display, cell_id).html(response.submitted_data.data.Orbopt.title);
				break;
			case "vendor-title":
				$(XSM.vendor_ui.orbopt_attr_display, cell_id).html(response.submitted_data.data.Orbopt.vendor_title);
				break;
		}
		if ("replacement" in data) $(XSM.vendor_ui.orbopt_attr_display, cell_id).html(data.replacement);
		XBS.vendor_ui.cancel_cell_editing("orbopt", data.id, data.attribute);
	},
	toggle_orbopt_group: function (orbcat_id) {
		orbcat_id = Number(orbcat_id);
		$("li.orbopt", XSM.modal.primary).each(function () {
			var data = $(this).data();
			pr([orbcat_id, data]);
			if (in_array(orbcat_id, data.groups)) {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt , "set_opt_state", "active"].join(C.DS), trigger: {}});
			} else {
				$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: ["orbopt_config", data.orbopt, "set_opt_state", "inactive"].join(C.DS), trigger: {}});
			}
		});
	},


	set_orbopt_state: function (opt_id, state, label, input) {
		var opt_label_wrapper = label ? label : "#orbopt-" + opt_id + "-label";
		var opt_label = opt_label_wrapper + " span";
		var opt_input = input ? input : "li[data-orbopt='" + opt_id + "'] input";
		if (state == FX.active) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active_plus).removeClass(FX.inactive).addClass(FX.active);
			$(opt_input, XSM.modal.primary).val(1);
		}
		if (state == FX.inactive) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active).removeClass(FX.active_plus).addClass(FX.inactive);
			$(opt_input, XSM.modal.primary).val(0);
		}
		if (state == FX.active_plus) {
			$(opt_label_wrapper, XSM.modal.primary).removeClass(FX.active).removeClass(FX.inactive).addClass(FX.active_plus);
			$(opt_input, XSM.modal.primary).val(2);
		}
	},


	toggle_filter: function (filter_id) {
		var filter = "#orbopt-flag-" + filter_id;
		if ($(filter, XSM.modal.primary).hasClass(FX.active)) {
			$(filter).removeClass(FX.active);
		} else {
			$(filter).addClass(FX.active);
		}
		XBS.vendor_ui.filter_opts()
	},

	toggle_optflag: function (response, data) {
		var orbopt_id = data.orbopt;
		var optflag_id = data.optflag;
		var cell_id = as_id(["orbopt", orbopt_id, "optflag", optflag_id].join("-"));
		if ($("span", cell_id).hasClass(FX.active)) {
			$("span", cell_id).removeClass(FX.active).addClass(FX.inactive);
		} else {
			$("span", cell_id).removeClass(FX.inactive).addClass(FX.active);
		}
	},

	toggle_orbopt: function (opt_id) {
		var opt_label = "#orbopt-" + opt_id + "-label";
		var opt_input = "li[data-orbopt='" + opt_id + "'] input";
		var state = null;
		if ( $(opt_label, XSM.modal.primary).hasClass(FX.active) ) {
			state = FX.active_plus;
		} else if ($(opt_label, XSM.modal.primary).hasClass(FX.active_plus) ) {
			state =  FX.inactive;
		} else {
			state = FX.active;
		}
		XBS.vendor_ui.set_orbopt_state(opt_id, state, opt_label, opt_input)
	},


	filter_opts: function () {
		var active_flags = new Array();
		$("dd.orbopt-flag.active").each(function () { active_flags.push(Number($(this).data('id'))); });
		$("li.orbopt", XSM.modal.primary).each(function () {
			var flags = $(this).data('flags');
			var active = false;
			for (var i in flags) {
				if (active_flags.indexOf(flags[i]) > -1) active = true;
			}
			if (active) {
				$(this).removeClass(FX.hidden);
			} else {
				$(this).addClass(FX.hidden)
			}
			$(document).foundation();
		});
	},

	reload_tab: function( tab ) {
		if (tab == "opts") {
			$(XSM.vendor_ui.menu_options_tab).html('');
			$(XSM.vendor_ui.menu_options_tab).load(["vendor-ui", "opts"].join(C.DS), function() {
																	XBS.vendor_ui.data_tables(tab);
																	XBS.vendor_ui.fix_breakouts();
																});
		}
		$(XSM.vendor_ui.menu_tab).load(["vendor-ui", "menu"].join(C.DS), function() {
														XBS.vendor_ui.data_tables(tab);
														XBS.vendor_ui.fix_breakouts();
													});
	},

	toggle_menu_options_breakout: function( id ) {
		if ( $(id, XSM.vendor_ui.menu_options_tab).hasClass(FX.hidden) ) {
			$(id, XSM.vendor_ui.menu_options_tab).removeClass(FX.hidden);
		} else {
			$(id, XSM.vendor_ui.menu_options_tab).addClass(FX.hidden);
		}
	}

}
/**
 * J. Mulle, for XtremePizza, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */



window.XBS = {
//	init: function (is_splash, page_name, host, cart) {
//		XBS.cart = xbs_cart;
//		XBS.data = xbs_data;
//		XBS.event = xbs_events;
//		XBS.layout = xbs_layout;
//		XBS.modal = xbs_modal;
//		XBS.menu = xbs_menu;
//		XBS.printer = new XtremePrinter();
//		XBS.routing = xbs_routing;
//		XBS.splash = xbs_splash;
//		XBS.validation = xbs_validation;
//		XBS.vendor = xbs_vendor;
//		XBS.vendor_ui = xbs_vendor_ui;
//		XBS.setHost(host);
//		XBS.data.Service = cart.Service;
//		XBS.data.Order = cart.Order;
//		XBS.data.User = cart.User;
//		XBS.data.Invoice = cart.Invoice;
//		XBS.data.store_status = store_status;
//		XBS.data.cfg.page_name = page_name;
//		XBS.data.cfg.is_splash = is_splash === true;
//		var init_status = {
//			cart: XBS.cart.init(cart),
//			layout: XBS.layout.init(),
//			menu: XBS.menu.init(),
//			modal: XBS.modal.init(),
//			printer: XBS.printer.is_xtreme_tablet() ? XBS.printer.init() : 'not_tablet',
//			routing: XBS.routing.init(),
//			store: XBS.store_status(),
//			splash: XBS.splash.init(),
//			vendor: XBS.vendor.init()
//		};
//
//		if (XBS.data.cfg.page_name == XSM.page_name.vendor_ui) XBS.vendor_ui.init();
//
//		if (XBS.data.debug === false && XBS.data.cfg.page_name != XSM.page_name.vendor_ui) {
//			$(XBS.routing).trigger(C.ROUTE_REQUEST, {request: 'launch_apology', trigger: {}});
//		}
//	},

	store_status: function() {
		for (var key in XBS.data.store_status) {
			if (XBS.data.store_status[key] == "true") XBS.data.store_status[key] = true;
			if (XBS.data.store_status[key] == "false") XBS.data.store_status[key] = false
		};
		var store_status_text;
		var store_status_class;
		var delivery_status_text;
		var delivery_status_class;


		if (XBS.data.store_status.delivering) {
			delivery_status_text = C.DELIVERING;
			delivery_status_class = stripCSS(XSM.global.available);
		} else {
			delivery_status_text = C.PICKUP_ONLY;
			delivery_status_class = stripCSS(XSM.global.unavailable);
		}

		if (XBS.data.store_status.open) {
			store_status_text = C.OPEN;
			store_status_class = stripCSS(XSM.global.available);
		} else {
			store_status_text = C.CLOSED;
			store_status_class = ['store-closed', stripCSS(XSM.global.unavailable)].join(" ");
			delivery_status_text = null;
		}
		$(XSM.global.store_status).html(store_status_text).addClass(store_status_class);
		$(XSM.global.delivery_status).html(delivery_status_text).addClass(delivery_status_class);

//		var inspected_recently = (Date.UTC() - Date.parse( XBS.data.store_status.time ) ) > XBS.data.cfg.store_status_inspection_timeout;
//		pr(Date.UTC() - Date.parse( XBS.data.store_status.time));
//		if ( !inspected_recently || !XBS.data.store_status.reachable && 2+3 < 1) {
//			$(XSM.global.store_status).hide();
//			$(XSM.global.delivery_status).hide();
//			$(XSM.global.unknown_status).show();
//		}
	}
};