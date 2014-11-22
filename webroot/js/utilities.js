$.fn.scrollTo = function( target, options, callback ){
  if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
  var settings = $.extend({
    scrollTarget  : target,
    offsetTop     : 50,
    duration      : 500,
    easing        : 'swing'
  }, options);
  return this.each(function(){
    var scrollPane = $(this);
    var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
    var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
    scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
      if (typeof callback == 'function') { callback.call(this); }
    });
  });
}

// adds formatting methods to the String primitive
if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) {
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}


if (!String.prototype.toTitleCase) {
	String.prototype.toTitleCase = function () {
        return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
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

	if (typeof(obj) !== "object") { return false;}
	var w3cKeys = ["bubbles","cancelable","currentTarget","eventPhase","timeStamp","type"];
	for (i in w3cKeys) {
		if (!(w3cKeys[i] in obj) ){
			pr(w3cKeys[i],"isEvent Failed key");
		}
	}
	return true;
}

function isArray(obj) {
	return obj instanceof Array;
}

function isFloat(n) {
    return n === +n && n !== (n|0);
}

function isInt(n) {
    return n === +n && n === (n|0);
}


/**
 * isFunction method
 *
 * @desc From Alex Grande, http://stackoverflow.com/questions/5999998/how-can-i-check-if-a-javascript-variable-is-function-type
 * @param obj
 * @returns {boolean}
 */
function isFunction(obj) {
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
	catch (e) { }

	return false;
}


/**
 * eCustom method
 *
 * @desc Creates custom jQuery events with standard W3C properties
 * @param eName
 * @param eProperties
 * @returns {*}
 */
function eCustom(eName, eProperties) {
			var defaultProps = {"bubbles":true, "cancelable":false, "eventPhase":0, "type":eName};
			if (typeof(eProperties) == "object") {
				for (var prop in eProperties) {
					if (eProperties.hasOwnProperty(prop) ) {
						defaultProps[prop] = eProperties[prop];
					}
				}
			}
			return jQuery.Event(eName, defaultProps);
}


/**
 * eTypeOf method
 *
 * @desc Provided argument is an event, returns the event's type attribute (string)
 * @param e
 * @returns {type|*}
 */
function eTypeOf(e) { return isEvent(e) ?  e.type : false}


/**
 * asId method
 *
 * @desc
 * @param selector
 * @returns {*}
 */
 function asId(selector) {
	if (typeof(selector) === "string") {
		return selector.substring(0,1) === "#" ? selector : "#"+selector;
	}
	return false;
}


function selToStr(str) {
	try {
	  return str.replace("-"," ").replace("_"," ").toTitleCase();
	} catch (e) {
		pr(e,"ERROR:selToStr()");
		return str;
	}
}


function stripCSS(selector) {
	return selector.substring(0,1) == "." || selector.substring(0,1) == "#" ? selector.substring(1) : selector;
}


function matchWindowHeight(selector, padding) {
	var win_height = window.innerHeight;
	if ( Object.prototype.toString.call( padding ) === '[object Array]' ) {
		for (var i =0; i<padding.length; i++) {
			win_height -= $(padding[i]).innerHeight();
		}
	} else if ( typeof(padding) === "int") {
		win_height -= $(padding).innerHeight();
	}
	else if ( typeof(padding) === "int") {
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
			debug ?  pr(cakeurl) : null;
			return cakeurl;
		}
		if (params.constructor === Object) {
			var param_string = '';
			for (p in params) {
				param_string += DS + params[p];
			}
			var cakeurl = WWW + DS + APP + DS + controller + DS + action + param_string;
			debug ?  pr(cakeurl) : null;
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
 * pr method
 *
 * @desc Elaborated print statement for use with Chrome console
 * @param obj
 * @param label
 * @param as_error
 * @returns {*}
 */
function pr(obj, label, as_error) {
	var method = as_error === true ? "error" : "log";
	label = !!label && typeof(label) === "string" ? "%c "+ label +" " : '%c';
	var note_delim = ".";
	var note_delim_length = note_delim.length;
	if (!as_error) {
		var label_css = "color:rgb(0,150,0); text-transform:uppercase; background-color:rgb(245,245,245); border:1px solid rgb(220,220,220);";
	} else {
		var label_css = "color:rgb(255,0,0); text-transform:uppercase; background-color:rgb(255,245,245); border:1px solid rgb(255,0,0);";
	}
	var type_css = "color:rgb(200,200,200); font-style:italics; display:inline-block; width: 12px; min-width:12px; max-width:12px;";
	var num_css = "color:rgb(0,0,100);";
	var bool_css = "color:rgb(225,125,80); font-weight: bold";
	var str_css = "color:rgb(125,125,125); font-family:arial";
	var note_css = "color:#008cba; background-color:rgb(247,247,247); border:1px solid #008cba;";
	var note_css = "color:#008cba; background-color:rgb(247,247,247); border:1px solid #008cba;";

	if (obj === 0)  {
		console[method](label + "%c(int) %c", label_css, type_css, num_css,0);
		return true;
	}
	if (obj === 1) { console[method](label + "%c(int) %c%s", label_css, type_css, num_css,1); return true;}
	if (obj === false) { console[method](label + "%c(bool) %c%s", label_css, type_css, bool_css, "false"); return true;}
	if (obj === true) { console[method](label + "%c(bool) %c%s", label_css, type_css, bool_css, "true"); return true;}
	if (obj === null) { console[method](label + "%c(!def) %c%s", label_css, type_css, bool_css, "null"); return true;}
	if (typeof(obj) === 'undefined') { console[method](label + "%c(!def) %c%s", label_css, type_css, bool_css, "undefined"); return true;}
	if (typeof(obj) === 'string') {
		if (obj.substring(0,note_delim_length) === note_delim) {
			console[method](label + "%c%s",label_css, note_css, " " + obj.substring(1) + " ");
		} else {
			console[method](label + "%c(str) %c%s", label_css, type_css, str_css, obj);
		}
		return true;
	}
	if (typeof(obj) === 'number') {
		obj % 1 === 0 ? console[method](label + "%c(int) %c%s", label_css, type_css, num_css, obj) : console[method](label + "%c(float) %c%s", label_css, type_css, num_css, obj);
		return true;
	}

	console[method](label + "%c(obj) %O", label_css, type_css, obj);

	return null;
}


/**
 * tr method
 * @desc used for providing a quick and dirty stack trace
 */
function tr(args) { pr(args, "err", true); }


/**
 * splitPath method
 *
 * @desc Splits a path by <separator> or else forward-slash, striping first trailing and leading slash if found
 * @param path
 * @param separator
 * @returns {*}
 */
function splitPath(path, separator) {
	if (typeof(path) != "string") {
		return false;
	}
	// clear trailing & leading slashes
	path = path.substr(-1, 1) === separator ? path.substring(0, path.length - 1) : path;
	path = path.substr(0, 1) === separator ? path.substring(1) : path;

	path = path.replace('\\' + separator,"$DIRECTORY_SEPARATOR").split(separator);
	for (var i = 0; i < path.length; i++) {
		path[i].replace("$DIRECTORY_SEPARATOR",separator);
	}

	return path;
}



function ucfirst(stringName) {
	return stringName.charAt(0).toUpperCase() + stringName.slice(1).toLowerCase();
}

function strtolower(stringName) {
	return stringName.charAt(0).toLowerCase() + stringName.slice(1).toLowerCase();
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

function exists(varName) {
	return jQuery(varName).length > 0;
}



function clearForm(formId) {
	$("#" +formId)[0].reset();
}

function strpad(padstr, pad_length, padchar, direction, decimal_to) {
	if (typeof(padstr) == "number") padstr = padstr.toString();
	padchar = padchar ? padchar : "0";
	if (decimal_to) {
		var post_decimal = padstr.split(".")[1];
		if (post_decimal && post_decimal.length > 0) {
			padstr += padstr.substring(0,1) != "." ? "." : null;
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
		for (var j =0; j < padchar_count; j++) {
			final += padchar;
		}
		return final + padstr;
	} else {
		for (var j =0; j < padchar_count; j++) {
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


function obLen(object) {
	    var size = 0, key;
	    for (key in object) {
	        if (object.hasOwnProperty(key)) size++;
	    }
	    return size;
}


function b64JSON(jsobject) {
	return btoa(JSON.stringify(jsobject));
}

//
//
//$("#developer-output").draggable()
//	.mouseover( function() {
//	$(this).css({
//		"background-color":"rgba(255,255,255,.75",
//		width:"800px",
//		height:"auto",
//		color:"rgba(45,45,45,1)"});
//})  .mouseout(function() {
//	$(this).css({
//		"background-color":"rgba(255,255,255,.1",
//		width:"100px",
//		height:"100px",
//		color:"rgba(45,45,45,.1)"});
//});
//
