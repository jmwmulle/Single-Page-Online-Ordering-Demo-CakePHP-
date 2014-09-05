/**
 * Created by jono on 8/22/14.
 *
 * (X)treme(B)oot(S)trap
 * - global, one-off or otherwise external to Ember functions, vars, layout initialization, etc.
 */

window.XBS = {
	init: function(isSplash) {
		var initStatus = true;
		var sitRep = {
			layoutInit: XBS.layoutInit(isSplash),
			jqBinds:XBS.jqBinds()
		};

		for (initEl in sitRep) {
			if (!(sitRep[initEl] === true)) {
				initStatus = false;
				/* I do not know if throwing an error is the best approach, here (I've also made no attempt to catch it).
				   I do know that if this has failed, the site isn't going any further; could just as easily return false
				   and be handled elsewhere/later. */
				throw "Bootstrap failed: "+initEl+" failed.";
			}
		}


		return initStatus;
	},
	/**
	 * jqBinds method
	 *
	 * @desc jQuery event binding on page load
	 * @returns {boolean}
	 */
	jqBinds: function() {
		$(window).on("resize", XBS.scaleSplash);

		//splash redirects
		$("section#splash *[data-splash-redirect]").each(function() {
			var data = $(this).data();
			$(this).on(data.on,null,{route:data.splashRedirect}, XBS.splashRedirect);
		});

		$("*[data-scroll-to]").each(function() {
				$(this).on("click", function() {
					var scroll = $(this).data();
					var top = $(asId(scroll.scrollTo)).offset().top;
					$(scroll.scrollTarget).animate({marginTop:String(-1 * top)+"px"});
				});
		});


		//contingent aspect ratios
//		$(".preserve-aspect-ratio").on("resize",XBS.assertAspectRatio);
		return true;
	},
	layoutInit: function(isSplash) {
		//fix menu & order margins
		var opad = String($(XSM.splash.menuWrap).innerHeight() * 0.45)+"px";
		var mpad = String($(XSM.splash.menuWrap).innerHeight() * 0.15)+"px";
		$(XSM.splash.orderSpacer).css({height:opad});
		$(XSM.splash.menuSpacer).css({height:mpad});

		$(".preserve-aspect-ratio").each(function() {
			$(this).removeAttr("style");
			var data = $(this).data("aspectRatio");
			XBS.assertAspectRatio(this, Number(data.x) / Number(data.y), data.respect);
		});


		$(".detach").each(function() {
			XBS.detach(this);
		});

		if (isSplash) {
			XBS.scaleSplash();
		}

		return true;
	},
	scaleSplash: function() {
		var splashbarTop = $(XSM.splash.splashBar).offset().top;
		var scaleFactor = 570/splashbarTop;
		var dealDim = [splashbarTop, scaleFactor *400];
		var dealLeft = String( (window.innerWidth / 2) + (.33 * $("#order").innerWidth()) ) +"px";
		$("#grand-opening-deal").css({
			height:String(1.08 * dealDim[0])+"px",
			width:String(1.08 * dealDim[1])+"px",
			left:dealLeft
		});
		$(".fastened").attr('style', '').removeClass('fastened');
		$(".detach").attr('style', '');
		XBS.layoutInit();
		return true;
	},
	detach: function(element) {
		var isStatic = $(element).data('static');

		var height = $(element).innerHeight();
		$(element).css({height:height});
		if (isStatic) XBS.fasten(element);
		return $(element);
	},
	assertAspectRatio: function(selector, ratio, respect) {
		// make sure the ratio is indeed a ratio
		if (!isInt(ratio) && !isFloat(ratio)) {
			var ratioVals = ratio.split("/");
			if (ratioVals.length !== 2) throw "Invalid value for argument 'ratio'; must be int, float or string matching n/m";
			ratio = Number(ratioVals[0]) / Number(ratioVals[1]);
		}

		var dimensions = {width:$(selector).innerWidth(), height:$(selector).innerHeight()};
		if (respect != "y") {
			dimensions.height = dimensions.width / ratio;
		} else {
			dimensions.width = dimensions.height * ratio;
		}
		$(selector).css(dimensions);
	},
	fasten: function(selector) {
		//todo: error handling & logic for objects vs arrays
		var selectors = ( isArray(selector) ) ? selector : [selector];
		for (i in selectors) {
			var sel = selectors[i];
			var offset = $(sel).offset();
			var dims = $(sel).css(['height','width']);

			$(sel).css({position:"fixed", top:offset.top, left:offset.left, height:dims.height, width:dims.width}).addClass("fastened");
		}
		return  (isArray(selector) ) ? selector : $(selector);
	},
	splashRedirect: function(route) {
		if (isEvent(route) ) route = route.data.route;
		XBS.foldSplash(route);
	},
	foldSplash: function(route) {
		XBS.fasten([XSM.splash.splashBar,XSM.splash.logo]);
		var logo = $(XSM.splash.logo).clone().attr('id', stripCSS(XSM.splash.logoClone));
		var logoLoc = $(XSM.splash.logo).offset();
		$(logo).css({position:"fixed",top:logoLoc.top, left:logoLoc.left,zIndex:"9999999"});
		$("#splash").append(logo);
		$(XSM.splash.logo).remove();
		$(XSM.splash.splashBar).animate({left:String(-1.1 * window.innerWidth)+"px"}, 300, "easeInCubic",
			function() {
				$(this).hide();
				$(XSM.splash.circleWrap).css({});
							$(XSM.splash.circle).addClass("flipped");
				//todo: use modernizr to do this more effectively
					//		$(XSM.splash.circle).animate({transform:"rotafdteY(640deg)"}, 1000,"linear", function() {
								$(XSM.splash.logoClone).hide("puff", function() {window.location.replace(route)});
//								$("#splash").fadeOut();
					//		});
			});
		return true;
	}
}

/* JONO: USE THIS WHEN THE TIME COMES TO ASSESS THE HEIGHT OF ORBCARS ON SCREENL: https://github.com/imakewebthings/jquery-waypoints */