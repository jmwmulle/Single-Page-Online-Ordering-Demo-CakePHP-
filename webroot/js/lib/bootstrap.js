/**
 * J. Mulle, for XtremePizza, 9/5/14 1:03 AM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */
var constants = {
	PX:"px",
	CLK:"click",
	UNDEF:"undefined",
	MOUSEOVER:"mouseover",
	HOVER: "hover"
};
var C = constants;


 function InitError(raisedError, message) {
	 this.prototype = Error.prototype;
	 this.name = "InitError";
	 this.message = message ? message : "No additional message provided";
	 this.raisedError = raisedError;
	 this.read = function() {
		 if (raisedError.stackHistory != C.UNDEF) { this.raisedError.stack = this.raisedError.stackHistory; }
	    //todo: make this not rely on pr() being defined!!
         pr(this.raisedError.message+". \n\tWith trace: {0}".format(this.raisedError.stack), "InitError > {0}".format(this.raisedError.name), true);
	 };
 }
window.XBS = {
	data: {
		hostRootDirs: {
			xDev:"",
			xProd:"",
			xLoc:"xtreme"
		},
		scrollableElements:[XSM.main.primaryContent, XSM.main.toc, XSM.main.subNav],
		images:["..img/splash/bluecircle.png",
		        "..img/splash/deal.png",
		        "..img/splash/email_icon.png",
		        "..img/splash/email_icon_hover.png",
		        "..img/splash/facebook_icon.png",
		        "..img/splash/facebook_icon_hover.png",
		        "..img/splash/twitter_icon.png",
		        "..img/splash/twitter_icon_hover.png",
		        "..img/splash/gplus_icon.png",
		        "..img/splash/gplus_icon_hover.png",
		        "..img/splash/logo.png",
		        "..img/splash/logo_mini.png",
		        "..img/splash/menu.png",
		        "..img/splash/menu_hover.png",
		        "..img/splash/order.png",
		        "..img/splash/order_soon.png",
		        "..img/splash/pizza-bg.jpg",
		        "..img/splash/logo_mini.png"]
	},
	cfg: {
		root: null,
		minLoadTime: 1000
	},
	evnt: {
		wakeFromSleep: eCustom("wakeFromSleep"),
		assetsLoaded: eCustom("assetsLoaded")
	},
	stopwatch: {
		__sw: new Date(),
		reset:function() { XBS.stopwatch.startTime = 0},
		start: function() {
			var now = XBS.stopwatch.__sw.getTime();
			XBS.stopwatch.startTime = now;
			return now;
		},
		read: function() { return XBS.stopwatch.__sw.getTime() - XBS.stopwatch.start;},
		now: function() { return XBS.stopwatch.__sw.getTime();},
		elapsed: function(period) {
			var diff = XBS.stopwatch.now() - XBS.stopwatch.startTime;
			return (period !== C.UNDEF) ? diff > period : diff;
		},
		startTime: 0
	},


	/*******************************************************************************************************************
	 *                                                                                                                 *
	 *                                                     INIT                                                        *
	 *                                                                                                                 *
	 *******************************************************************************************************************/
	init: function(isSplash, host) {
		XBS.cfg.isSplash = isSplash === true;
		XBS.fn.setHost(host);
//		try {
			XBS.layout.init();
			XBS.splash.init();
			XBS.cache.init();
//		} catch(e) {
//			if (e instanceof InitError) {
//				e.read();
//			} else {
//				pr(e.message, e.name, true);
//			}
//		}
	},
	cache: {
		preloadDone:false,
		cbDone:false,
		failed: [],
		init: function() {
			//todo: dynamically tie load-screen behavior to template/caching situation
				$("img.image-loader.preload").each(function() {
						$(this).cacheImages({
							debug:true,
							url:$(this).data("src"),
							storagePrefix:"xtreme",
							fail: function() {
								XBS.cache.failed.push( $(this).data("src") );
							}
						});
				});
			$(window).trigger(XBS.evnt.assetsLoaded);
		}
//		fetchImages: function() {
//			//todo: callbacks for errors
//			XBS.stopwatch.start();
//			$(XSM.global.imageQueue).loadImages({
//				allLoadedClb:function() { $(window).trigger(XBS.evnt.assetsLoaded);},
//				imgErrorClb: function() {pr(this, "ImageError", true)},
//				imgLoadedClb: function() {/*pr(this, "ImageLoaded");*/},
//				noImgClb: function() {pr(this, "No Images!", true)}
//			});
//		}
	},
	layout: {
		init: function() {
			try {
				XBS.fn.execInitSequence(XBS.layout.jqBinds);
				XBS.layout.detachAnimationTargets();
				XBS.layout.initializeScrolling();
			} catch(e) {
				e.stackHistory = e.stack;
				pr(e.stackHistory);
				//todo: list all init exceptions if needed
				throw new InitError(e);
			}
			return true;
		},
		detachAnimationTargets: function() {
			$(XSM.global.detachable).each(function() { XBS.layout.detach(this);});
		},
		jqBinds: {
			hasInitSequence:true,
			bindAjaxNav: function() {
				$(XSM.global.ajaxLink).each(function() {
					$(this).on(C.CLK, null, $(this).data(), XBS.load);
				});

				return true;
			},
			bindAutoScrolling: function() {
				$(XSM.global.autoScrollers).each(function() {
					$(this).on(C.CLK,null, $(this).data(), XBS.layout.autoScroll);
				});

				return true;
			},
			bindAssetsLoadedListener: function() {
				$(window).on(XBS.evnt.assetsLoaded.type, function() {
//						if (XBS.cfg.execInitSequenced = true) {
							XBS.layout.readyLoadingScreen();
//						}
					});
				return true;
			},
			bindJsLinks: function() {
				$(XSM.global.jsLink).each(function() {
					$(this).on(C.CLK, function() { window.location.replace($(this).data('url'))});
				});

				return true;
			},
			bindScrolling: function() {
				$(XSM.global.scrollable).each(function() {
					// get and store within the element it's initial offset for later reference
					var initialOffset = $(this).offset().top;
					var lastContentEl= $(this).find(".last-content-element");
					if (lastContentEl.length == 0) {
						var children = $(this).children();
						if (children.length != 0) {
							lastContentEl = children[children.length - 1];
						} else {
							lastContentEl = false;
						}
					} else {
						lastContentEl = lastContentEl[0];
					}
					// try to set maxOffset according to the last scrollable element; use last child if need be
					var maxOffset = null;
					if (lastContentEl)  {
						var lastElTop = $(lastContentEl).offset().top;
						// if scroll isn't even needed, don't allow it
						maxOffset = lastElTop > window.innerHeight ? lastElTop : 0;
					} else {
						maxOffset = 0;
					}
					$(this).data({initialOffset:initialOffset, maxOffset:maxOffset});
					$(this).css({height:$(this).innerHeight() + window.innerHeight});
					$(this).on("mousewheel", null, null, XBS.layout.scroll);
				});
				return true;
			},
			windowResizeListener: function() {
				$(window).on("resize", XBS.splash.render);

				return true;
			}
		},
		autoScroll: function() {
			if (isEvent(arguments[0]) ) {
				var e = arguments[0];
				var scrollTarget = e.data.scrollTarget;
				var scrollTo = e.data.scrollTo;
			}
			var delta = $(scrollTarget).data('autoScrollTo') - $(scrollTo).offset().top;
			var direction = delta > 1 ? -1 : 1;
			XBS.layout.scroll(scrollTarget, Math.abs(delta), direction);
		},
		assertAspectRatio: function(targets) {
			if (!targets) targets = XSM.global.preserveAS;
			$(targets).each(function() {
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

				var dimensions = {width:$(this).innerWidth(), height:$(this).innerHeight()};
				if (respect != "y") {
					dimensions.height = dimensions.width / ratio;
				} else {
					dimensions.width = dimensions.height * ratio;
				}
				$(this).css(dimensions);
			});

			return true;
		},
		initializeScrolling: function() {
			/* assert a autoscroll-to attribute for each scrollable area, ie. a vertical scroll destination */
			$(XSM.global.scrollable).each( function() {
				var height = $(this).innerHeight();
				var offset = $(this).offset().top;

				/* Find out if the element extends off the screen or not, and then get it's visible, vertical center */
				var visibleCenter = (height + offset > window.innerHeight ) ? (window.innerHeight - offset)/2 : height/2;
				$(this).data({autoScrollTo:visibleCenter});
			});

			return true;

//			$(XSM.global.autoScrollers).each(function() {
//				var offset = $("ul.orbcard-list").offset().top + 16;
//				var targetTop = $(asId($(this).data("scrollTo"))).offset().top;
//				$(this).data({scrollToPost:top});
//			});
//			$(scroll.scrollTarget).animate({marginTop:String(offset+(-1 * scroll.top))+ C.PX});
		},
		detach: function(element) {
			var isStatic = $(element).data('static');
			var height = $(element).innerHeight();
			$(element).css({height:height});
			if (isStatic) XBS.layout.fasten(element);
			return $(element);
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
		readyLoadingScreen: function() {
			if (XBS.cfg.isSplash) {
				$(XSM.load.pizzaLoaderGIF).fadeOut(500, function() {
					$(XSM.load.loadingMessage).hide("slide",{direction:"right"},300, function() {
						$(XSM.load.readyMessage).show("slide",300, function() {
							$(XSM.load.dismissLSButton).fadeToggle();
						});
					});
				});
			} else {
				$(XSM.load.pizzaLoaderGIF).fadeOut(500, function() {
						XBS.layout.toggleLoadingScreen();
				});
			}
		},
		scroll: function(target, distance, direction) {
				var e = arguments[0];
				var offsetData = $(this).data();
				var marginTopString = $(this).css('marginTop');
				var marginVal = Number(marginTopString.replace("px", ""));
				var margin = marginVal - e.originalEvent.deltaY;
				if (margin > 0) margin = 0;
				if (Math.abs(margin) > offsetData.maxOffset) margin = -1 * offsetData.maxOffset;
				$(this).css({marginTop:margin});
		},
		toggleLoadingScreen: function() {
			$(XSM.global.loadingScreen).fadeToggle();
		}
	},
	splash: {
		hasInitSequence: true,
		init: function() {
			if (!XBS.cfg.isSplash) return true;
			XBS.fn.execInitSequence(XBS.splash.jqBinds);
			$(XSM.splash.self).on(C.MOUSEOVER, function() {
					$(XSM.splash.openingDeal).slideDown();
					$(this).unbind(C.HOVER);
			});
			XBS.splash.render();

			return true;
		},
		jqBinds: {
			hasInitSequence: true,
			splashRedirectListener: function() {
							$("section#splash *[data-splash-redirect]").each(function() {
								var data = $(this).data();
								$(this).on(data.on,null,{route:data.splashRedirect}, XBS.splash.redirect);
							});

							return true;
						},
			splashModalListener: function() {
				$(XSM.splash.modalLoad).each(function() {
					$(this).on(C.CLK, null, $(this).data(), XBS.splash.modal);
				});

				return true;
			}
		},
		render: function() {
				var splashbarTop = $(XSM.splash.splashBar).offset().top;
				var scaleFactor = 570/splashbarTop;
				var dealDim = [splashbarTop, scaleFactor * 400];
				var dealLeft = String( (window.innerWidth / 2) + (.8 * $(XSM.splash.order).innerWidth()) ) +"px";

				/* ---------------- opening deal temp code ------------------*/
				$(XSM.splash.openingDeal).css({
					height:String(1.08 * dealDim[0])+"px",
					width:String(1.08 * dealDim[1])+"px",
					left:dealLeft
				});
				$(XSM.splash.fastened).attr('style', '').removeClass('fastened');
				$(XSM.splash.detach).attr('style', '');
				/* ---------------- opening deal temp code ------------------*/

				$(XSM.splash.orderSpacer).css({height: $(XSM.splash.menuWrap).innerHeight() * 0.45});
				$(XSM.splash.menuSpacer).css({height: $(XSM.splash.menuWrap).innerHeight() * 0.15});
				XBS.layout.assertAspectRatio(XSM.splash.preserveAS);
				return true;
			},
		redirect: function(route) {
			if (isEvent(route) ) route = route.data.route;
			XBS.splash.fold(route);
		},
		modal: function(modalSource) {
			if (isEvent(arguments[0]) ) modalSource = arguments[0].data.source;

			//window.location.replace(modalSource);
			$(XSM.splash.modalContent).load(modalSource, function() {
				$(XSM.splash.modalWrap).fadeIn(500, function() {
					$(XSM.splash.modal).show('slide');
				}).on(C.CLK, function() {
					$(XSM.splash.modal).slideUp(300, function() {
						$(this).html('');
						$(XSM.splash.modalWrap).fadeOut();
					})
				});
			});
		},
		fold: function(route) {
			XBS.layout.fasten([XSM.splash.splashBar,XSM.splash.logo]);
			var logo = $(XSM.splash.logo).clone().attr('id', stripCSS(XSM.splash.logoClone));
			var logoLoc = $(XSM.splash.logo).offset();
			$(logo).css({position:"fixed",top:logoLoc.top, left:logoLoc.left,zIndex:"9999999"});
			$(XSM.splash.self).append(logo);
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
	},
	fn: {
		execInitSequence: function(initOb) {
			if (!exists(initOb.hasInitSequence) ) {
				throw new TypeError("Object passed to XBS.fn.execInitSequence() does not contain 'hasInitSequence' property.");
			}
			var sitRep = {};
			for (fn in initOb) { if (fn != "hasInitSequence") sitRep[fn] = initOb[fn](); }
			return sitRep;
		},
		offsetToMargin: function(target, offset) {

		},
		marginToOffset: function(target, margin) {

		},
		/**
		 * setHost method
		 * @desc  Sets the root directory for AJAX references given <host>
		 * @host <str> string key for host in XBS.data.hostRootDirs
		 * @throws
		 * @returns <boolean>
		 */
		setHost: function(host) {
			if (XBS.data.hostRootDirs[host] == C.UNDEF ) {
				throw new ReferenceError("Error: host '{0}' not found in XBS.data.hostRootDirs.".format(host));
			} else {
				XBS.cfg.root = XBS.data.hostRootDirs[host];
			}
			return true;
		},

		/**
		 * sleep method
		 *
		 * @period {integer} duration (ms) for which program should wait
		 * @wakeEvent {mixed} Either an event object or a string name of standard event
		 * @onWake {function} Function to be executed after sleep period has elapsed
		 * @target {mixed} CSS selector string or DOM element to be target of wakeEvent
		 * @returns {boolean}
		 */
		sleep: function(period, wakeEvent, onWake, target) {
			if (wakeEvent == C.UNDEF) wakeEvent = XBS.evnt.wakeFromSleep;
			if (isFunction(onWake) ) {
				// confirmed code reaches these lines and all vars are as expected
				if (target == C.UNDEF) target = window;
				$(target).on(wakeEvent.type, onWake);
			}
			setTimeout(function() {$(target).trigger(wakeEvent) }, period);

			return target;
		},

		/**
		 * sleep method
		 *
		 * @period {integer} duration (ms) for which program should wait
		 * @wakeEvent {mixed} Either an event object or a string name of standard event
		 * @onWake {function} Function to be executed after sleep period has elapsed
		 * @target {mixed} CSS selector string or DOM element to be target of wakeEvent
		 * @returns {boolean}
		 */
		load: function(targets, sources) {
			if (isEvent(arguments[0]) ) {
				var e = arguments[0];
				targets = e.data.for;
				sources = e.data.get;
			}
			if (!isArray(targets)) targets = [targets];
			if (!isArray(sources)) sources = [sources];
			for (var i = 0; i < targets.length; i++) {

				var target = targets[i];
				var source = sources[i];
				$(target).fadeOut(300, function() {
					if ($(target).data('scrollTarget') != C.UNDEF) {
						var initialOffset = $(target).data("initialOffset");
						var currentOffset = $(target).offset().top;
						// reset scrolling first
							var topMargin = initialOffset + (initialOffset - currentOffset);
							$(this).attr('style','').html('');
					}
					$(target).load(source, function() { $(this).fadeIn(300);});
				});
			}
			return true;
		}
	}
};
/* JONO: USE THIS WHEN THE TIME COMES TO ASSESS THE HEIGHT OF ORBCARS ON SCREENL: https://github.com/imakewebthings/jquery-waypoints */