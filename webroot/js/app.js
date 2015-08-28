var test = "hi mom";
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

	function obj_keys(obj) {
		var keys = [];
		for (var k in obj) keys.push(k);
		return keys;
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

function strip_orphan_text_nodes(parsed_html_obj) {
	var return_array = [];
	for (var i = 0; i < parsed_html_obj.length; i++) {
		if (parsed_html_obj[i].nodeName != "#text") return_array.push( parsed_html_obj[i] );
	}
	return return_array.length > 1 ? return_array : return_array[0];
}


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

XT.route_collections.menu_ui = function() {
	this.favorite = {
				params: {context: {value: null, url_fragment: false}},
				url: {url: "favorite"},
				data: false,
				callbacks: {
					modal: C.FLASH,
					params: function () { this.data = $(XSM.menu.orb_order_form).serialize(); },
					launch: function (e, resp) { pr(resp);}
				}
			};
	this.menu = {
				params: ['reveal_method'],
				modal: C.PRIMARY,
				url: {url: "menu"},
				callbacks: {
					params_set: function () {
						if (this.read('reveal_method') == 'unstash') {
							this.unset('url');
							this.unset('launch_callback');
							this.modal.hide();
							setTimeout(function () { XT.menu.unstash() }, 300);
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
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response) { XT.orbcard.load_menu(response.data) }
				}, false, false)
			}
		}
	};
	this.price_rank = {
		params: ["rank"],
		propagates: false,
		callbacks: {
			params_set: function () { XT.orbcard.orb.price_rank.set( this.read('rank') ) }
		}
	};
	this.orb_opt = {
		params: ["action", "id"],
		propagates: false,
		callbacks: {
			params_set: function () { XT.orbcard.orb.toggle_opt( this.read('id') ) }
		}
	};
	this.orb_opt_weight = {
		propagates:false,
		params: ["id", "weight"],
		propagates: false,
		callbacks: {
			params_set: function () { XT.orbcard.orb.toggle_opt( this.read('id'), this.read("weight") ) }
		}
	};
	this.optflag = {
		params: ['target', 'action'],
		callbacks: {
			launch: function () { XT.orbcard.toggle_filter(this.read('target')); }
		}
	};
	this.load_orb = {
		url: { url: "menu-item", type: C.GET, defer: true },
		params:{ id:{ url_fragment:true }, fart:{url_fragment:true, value:"poop"} },
		propagates: false,
		callbacks: {
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response, data) {
						setTimeout( function() {
							XT.orbcard.load_orb(data.id, response.data.view_data)
						}, XT.orbcard.reset_stage() );
					},
					data: { id: this.read('id') }
				}, false, false);
			}
		}
	};
	this.configure_orb = {
		params: ['id', 'rank'],
		callbacks: {
			params_set: function() {
				// populate form with any orb configurations in progress
				 XT.orbcard.configure(this.read('id'), this.read('rank'));
			},
			launch: function() { XT.orbcard.show_face(C.BACK) }
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
											XT.layout.reveal_orb_card_modal();
										}
									} catch (e) {
										throw "Add to cart failed at server:\n " + this.deferral_data;
									}
								}
								break;
							case 'cancel':
								launch = XT.menu.reset_orb_card_stage();
								break;
						}
						break;
					default:
						launch = function () { XT.menu.toggle_orb_card_row_menu(this.read('action'), null);}
						break;
				}
				if (launch) this.set_callback("launch", launch)
			}
		}
	};
	this.validate_form = {
				params: ['target', 'restore', 'delegate'],
				callbacks: {
					params_set: function () {
						pr(this.params, "vfrom");
						XT.validate(this.read('target'), this.read('restore'), this.read('delegate')) }
				}
			};
	this.close_modal = {
			params: ["modal"],
			callbacks: { launch: function () { new Modal(this.read('modal')).hide(true) } }
		};
		this.orbcard_modal = {
				params: ["action", "target"],
				propagates:false,
				modal: C.PRIMARY,
				callbacks: {
					params_set: function() {
						XT.orbcard.show_face(C.FRONT);
						switch ( this.read('action') ) {
							case "continue_ordering":
								setTimeout(function () { XT.orbcard.modal.hide(); XT.orbcard.reset_stage(); }, 900);
								break;
							case "view":
								XT.orbcard.modal.hide();
								this.url= {
									url: ["review", this.read('target')].join("-"),
									defer: false,
									type: C.GET
								};
								this.stash = true;
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
window.xtr.route_collections.orders_api = function() {
	this.finalize_order =  {
		url: { url: "orders" + C.DS + "finalize", defer: true, data: $("#OrderReviewForm").serialize(), type: C.POST },
		callbacks: {
			launch: function () {
					var data = $.parseJSON(this.deferral_data);
					var trigger = this.trigger;
					if (!data.error) {
						if (data.order_id) {
							var route = "pending_order" + C.DS + data.order_id + C.DS + "launching";
							$(XT.router).trigger(C.ROUTE_REQUEST, { request: route, trigger: trigger.event });
						}
					}
				}
		}
	},
	this.review_order = {
		url: { url: "review-order", type: C.GET, defer: false},
		params: ["context"],
		modal: C.PRIMARY,
		callbacks: { params_set: function() { this.modal.hide() } }
	};
	this.order_accepted = {
		url: "order-accepted",
		modal: C.PRIMARY
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
		modal: C.PRIMARY,
		callbacks: {
			params_set: function () {
				switch (this.read('context')) {
					case "review_modal":
						this.modal.payment_method(this.read('action'));
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
		modal: C.PRIMARY,
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
	this.set_delivery_address = {
		modal: C.PRIMARY,
		url: {url: "set-address", type: C.GET, defer: false},
		params: {context: {url_fragment:true}, restore:{url_fragment:false}},
		callbacks: {
			params_set: function () {
				if (this.read('context') == 'cancel' ) {
					this.unset('url');
					this.unset('launch');
					switch (this.read('restore')) {
						case 'menu':
							this.modal.hide();
							XT.menu.unstash();
							setTimeout(function() {
							$(XT.router).trigger(C.ROUTE_REQUEST, {request:"set_order_method/menu/just_browsing", trigger:{}});
							XT.cart.set_order_method(C.JUST_BROWSING);
							}, 300);
							break;
						case 'review':
							$(XT.router).trigger(C.ROUTE_REQUEST, {request:"review_order", trigger:{}});
						default:
							this.modal.hide();
							break;
					}
				}
			}
		}
	};
	this.set_order_method = {
		modal: C.PRIMARY,
		url: {url: "order-method", type: C.POST, defer: true},
		stash: true,
		params: {
			context: {value: null, url_fragment: true},
			method: {value: null, url_fragment: true}
		},
		callbacks: {
			params_set: function() {
				if (this.read('context') == "menu" && this.read('method') != "delivery") this.stash = false;
			},
			launch: function () {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response) { XT.cart.import_cart(response.data.Cart) }
				}, true, true);
			}
		}
	};
	this.set_user_address = {
		params: ['id', 'action'],
		modal: C.PRIMARY,
		callbacks: {
			params_set: function() {
				switch (this.read('action') ) {
					case 'reveal':
						this.modal.reveal_user_addresses(this.modal, this.read('id'));
						break;
					case 'set':
						this.modal.populate_address_form(this.modal, this.read('id'));
						break;
				}
			}
		}
	};
	this.clear_cart = {
		url: { url: "clear-cart", type:C.POST, defer: true},
		callbacks: { launch: function() { XT.router.cake_ajax_response(this.deferral_data) }
		}
	};
	this.edit_orb = {
		url: {url:"update", type: C.POST, defer:true},
		params: {uid: {url_fragment: true}, opt_id:{url_fragment: true}},
		modal: C.PRIMARY,
		callbacks: {
			launch: function() {
				XT.router.cake_ajax_response(this.deferral_data, {
					callback: function(response, data) {
						XT.cart.import_cart(response.data.cart);
						if ( !(XT.cart.session_data.Order.length > 0) ) {
							$(XT.router).trigger(C.ROUTE_REQUEST, {request:"orbcard_modal/view/cart", trigger: {}});
						} else {
							XT.cart.update(data.uid, data.target);
						}
					},
					data: {
						uid: this.read('uid'),
						target: this.trigger.element
					}
				}, true, true);
			}
		}
	},
	this.cart = {
				modal: C.PRIMARY,
				params: ["action", "action_arg", "data"],
				propagates: false,
				url: {url: "add-to-cart", type: C.POST, defer: true},
				callbacks: {
					params_set: function () {
						switch ( this.read("action") )  {
							case "add":
								 this.url.data = $(XSM.menu.orb_order_form).serialize();
								break;
							case "review":
								this.url =  {
									url: "review-cart",
									type: C.GET,
									defer: false
								};
								this.stash = true;
								this.unset('launch');
								break;
							default: // ie. "cancel"
								this.unset(['url', 'launch']);
								 XT.orbcard.reset_stage( true );
								break;
						}
					},
					launch: function () {
						XT.router.cake_ajax_response(this.deferral_data, {
							callback: function(response) {
								XT.cart.import_cart(response.data.Cart);
								if ( exists(response.delegate_route) ) return;
								pr(XT.orbcard.modal);
								XT.orbcard.modal.show();
							}
						}, true, true);
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
		modal: C.PRIMARY,
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
//				XT.vendor_ui.init();
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
							this.modal = new Modal(C.PRIMARY);
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
				var self = this;
				XT.router.cake_ajax_response(this.deferral_data, {
						callback: function (response, data) {
							if (data.target == 'pricedicts') $("#menu-tab").load("vendor-ui/menu", function() {
									XT.vendor_ui.fix_breakouts()
							});
							if (self.modal) self.modal.hide();
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
				modal: C.PRIMARY,
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
											XT.router.cake_ajax_response(this.deferral_data, {
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
										this.url = {
											url: "add-menu-item",
											type: C.GET,
											defer: false
										}
										this.unset('launch');
										break;
									case "save":
										this.modal.hide();
										this.url = {
											url: "add-menu-item",
											type: C.POST,
											defer: true,
											data: $(XSM.vendor_ui.orb_add_form, XSM.modal.primary).serialize()
										}
										this.set_callback("launch", function () {
											XT.router.cake_ajax_response(this.deferral_data, {
												callback: function () {
													$(XSM.vendor_ui.menu_tab).load("vendor-ui/menu",
														function () {
															XT.vendor_ui.data_tables('menu');
															XT.vendor_ui.fix_breakouts();
														});
												}
											}, true, true);
										});
										break;
								}
								break;

						}
					},
					launch: function () { XT.router.cake_ajax_response(this.deferral_data, {}, true, true);}
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
		modal: C.PRIMARY,
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
							XT.router.cake_ajax_response(this.deferral_data, {
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
									XT.router.cake_ajax_response(this.deferral_data, {
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
				var labels = $(".content .orbopt span");
				var i,j,temparray,chunk = 6;
				for (i=0,j=labels.length; i<j; i+=chunk) {
				    temparray = labels.slice(i,i+chunk);
				    var max_h = 0;
					for (var l =0; l < 6; l++) {
						if ( $(temparray[l]).innerHeight() > max_h) max_h = $(temparray[l]).innerHeight();
					}
					for (var l =0; l < 6; l++) $(temparray[l]).css( { height:max_h} );
				}

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
							XT.router.cake_ajax_response(this.deferral_data, {
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
						this.modal = new Modal(C.PRIMARY);
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
							var orbopt_id = $("#orbopt-pricelist-select-form").data('opt');

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
						$(XT.router).trigger(C.ROUTE_REQUEST, {request: "orbopt_edit/-1/cancel/pricing", trigger: {}});
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
			launch: function() {
				if (this.deferral_data != undefined) {
					XT.router.cake_ajax_response(this.deferral_data, {}, true, true);
				}
			}
		}
	};
	this.orbopt_optgroup_config = {
		url: {url: "orbopt-optgroup-config", type: C.GET},
		modal: C.PRIMARY,
		params: {id: {url_fragment: true}, action: {}, action_arg: {}},
		callbacks: {
			params_set: function () {
				switch (this.read('action')) {
					case "toggle":
						this.unset('url');
						this.unset('modal');
						this.unset('launch');
						var label_id = as_id(["optgroup", this.read('id'), 'label'].join("-"));
						var field_sel = "input[name='OrboptOrbcat[" + this.read('id') + "]'";
						$(label_id).toggleClass(XSM.effects.active)
						$(field_sel).val( $(field_sel).val() == 0 ? 1 : 0 );
						break;
					case "save":
						this.url = {
							url: this.url.url,
							method: C.POST,
							defer: true,
							data: $(XSM.vendor_ui.orbopt_optgroup_config_form).serialize()
						};
						break;
				}
			},
			launch: function () {
				if (this.deferral_data != undefined) {
					var self = this;
					XT.router.cake_ajax_response(self.deferral_data, {
						callback: function() { self.modal.hide()}
					}, true, true);
				}
				var labels = $(".content .orbopt-optgroup span");
				var i,j,temparray,chunk = 6;
				for (i=0,j=labels.length; i<j; i+=chunk) {
				    temparray = labels.slice(i,i+chunk);
				    var max_h = 0;
					for (var l =0; l < 6; l++) {
						if ( $(temparray[l]).innerHeight() > max_h) max_h = $(temparray[l]).innerHeight();
					}
					for (var l =0; l < 6; l++) $(temparray[l]).css( { height:max_h} );
				}
				$(document).foundation();
			}
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
				XT.router.cake_ajax_response(this.deferral_data, {
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
		if (debug_this > 0) pr(request_obj, "XtremeRoute::__init( request_obj )", 2);
		this.route_data = $.extend(true, {}, route_data);
		this.request = request_obj.request;
		this.trigger = {event: request_obj.trigger, element: request_obj.trigger.currentTarget};
		this.param_data = request_obj.request.split(C.DS).slice(1);
		if ("stash" in route_data) this.stash = route_data.stash;
		if ("modal" in route_data) this.modal = new Modal(route_data.modal);
		this.url = {url:undefined, type: undefined, defer: undefined};
		if ("url" in route_data) {this.set_url(route_data.url) }

		this.__stop_propagation = "propagates" in route_data ? !route_data.propagates : true;
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
		if (this.param_data.length == 0) return
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
/**
 * Created by jono on 1/22/15.
 */

XtremeRouter = function() { this.init(); return this}

XtremeRouter.prototype = {
	constructor: XtremeRouter,
	route_data: {},

	init: function () {
		for (var rc in XT.route_collections) {
			rc = XT.route_collections[rc]();
			for (var route in rc ) this.route_data[route] = rc[route];
		}
		/* For launching routes via the DOM */
		var self = this;
		$(C.BODY).on(C.CLK, XSM.global.route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
//				e.stopPropagation();
				$(self).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('route'), trigger: e});
			}
		});

		$(C.BODY).on(C.CHANGE, XSM.global.onchange_route, null, function (e) {
			if ( !$(e.currentTarget).hasClass(XSM.effects.disabled) ) {
				$(self).trigger(C.ROUTE_REQUEST, {request: $(e.currentTarget).data('changeroute'), trigger: e});
			}
		});

		/* For launching routes via ROUTE_REQUEST events */
		$(this).on(C.ROUTE_REQUEST, function (e, data) {
			this.launch(data) });
	},

	/**
	 * launch() creates and runs an instance of the class from passed param array
	 * @param params
	 * @param event
	 * @returns {boolean}
	 */
	launch: function (request_obj) {
		var debug_this = 2;
		if (debug_this > 0) pr([request_obj], "XtremeRouter.launch(request_obj, event)", 2);
		var route_name = request_obj.request.split(C.DS)[0];
		var route_data = this.route_data[ route_name ];
		var route = new XtremeRoute( route_name, route_data, request_obj);
		var launch_delay = 0
		if (route.stash) launch_delay += XT.orbcard.exposed_face == C.BACK ? 1860 : 900;
		if (route.modal) {
			pr(route.modal);
			if ( !route.modal.hidden() && route.url.url && !route.url.defer) launch_delay += route.modal.hide();
			route.modal.resize();
		}
		// >>> LAUNCH MODALS IF REQUIRED<<<
		if (route.url.url) {
			var launch_triggered = false;
			try {
				$.ajax({
					type: route.url.type ? route.url.type : C.POST,
					url: [XT.host, route.url.url].join(C.DS),
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
						if (route.stash) XT.menu.stash();

						setTimeout(function () {
							if (debug_this > 1) pr([route, data], route.__debug("launch/success"));
							if (route.url.defer) {
								route.set_deferral_data(data);
							} else {
								route.modal.content.set(data);
								route.modal.show();
							}
							launch_triggered = true;
							$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "TRIGGERED_POST_SUCCESS"});
						}, launch_delay);
					},
					fail: function () {
						$(XSM.global.loading).addClass(XSM.effects.fade_out);
						if ("fail_callback" in route) {
							route.fail_callback();
						} else {
							XT.layout.launch_route(XT.routes.fail_flash);
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
				pr(e);
				if (!launch_triggered) {
					launch_triggered = true;
					$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "CAUGHT_EXCEPTION"})
				}
				$(this).trigger(C.ROUTE_REQUEST, {request: "flash"});
			}
		} else {
			$(route).trigger(C.ROUTE_LAUNCHED, {launch_msg: "NO_AJAX"});
		}

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

		// don't proceed to success handler if any part of the request failed
		if ("success" in response) {
			if (is_object(response.success)) {
				for (var controller in response.success) if (response.success[controller] != true) return;
			} else if (response.success !== true)  return
		}

	//	XT.layout.dismiss_modal(XSM.modal.primary);
		if ( success_handler && success_handler.callback != undefined ) {
			"data" in success_handler ? success_handler.callback(response, success_handler.data) : success_handler.callback(response);
		}
		if ( response.delegate_route != undefined ) {
			$(this).trigger(C.ROUTE_REQUEST, {request: response.delegate_route, trigger: {}});
		}
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
	this.init(config);
	return this;
}
EffectChain.prototype = {
	constructor: EffectChain,
	// Only includes params that every step *must* have, but others can be added
	defaults: {
		interval: undefined,  // for any step, time between it's commencement and the next step's execution
		context: undefined    // for constraining selectors to a parent context, vis-a-vis jQuery
	},
	step_complete: createCustomEvent("ECStepComplete"),
	steps: [],
	step_template: {
		target: false,
		context: "body",
		state: undefined,
		interval: undefined,
		next: undefined
	},

	// I'm not 100% sure this feature is a good idea... basically the final step can return something if one likes, but
	// I am sort of just hijacking extant functionality for a purpose that wasn't intended
	exec_result: undefined,

	/**
	 * Optionally sets default values for the EffectChain object
	 * @param config
	 * @returns {effect_chain}
	 */
	init: function (config) {
		// assigns a listener to the object for responding to completed steps
		$(this).on("ECStepComplete", function (e, data) { this.exec_step(data.step_index, data.target) });

		if ( !config ) return

		this.defaults = obj_merge(this.defaults, config);
		for (var key in this.defaults) {
			if (this.defaults[key]) this.step_template[key] = this.defaults[key];
		}
	},

	add: function (step) {
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
	},

	/**
	 * Executes a passed step in the effect chain; triggers ECStepComplete event on completion.
	 * @param step_index
	 * @param target
	 */
	exec_step: function (step_index, target) {
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

	},

	/**
	 *  Sums all step intervals except the last one; just useful information, not required for execution of the chain
	 * @returns {number}
	 */
	duration: function () {
		var t = 0;
		for (var i = 0; i < this.steps.length - 1; i++)  { t += this.steps[i].interval; }
		return t;
	},

	/**
	 * Starts the series of calls to exec_step(); allows for an initial element to be explicitly passed, which, depending
	 * on how the subsequent steps are configured, can dramatically alter the results of EffectChain object.
	 * @param initial_target
	 */
	run: function (initial_target) { this.exec_step(0, initial_target); }

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
				url: "confirm-address/session",
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