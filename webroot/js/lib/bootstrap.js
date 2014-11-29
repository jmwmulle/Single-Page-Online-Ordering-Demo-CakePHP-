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
	HOVER: "hover",
	MOUSEENTER: "mouseenter",
	MOUSEOUT: "mouseout",
	HIDE: 0,
	SHOW: 1,
	DS: "/",
	BODY: "body"
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
			xDev: "development-xtreme-pizza.ca",
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
		developmentMode:false,
		minLoadTime: 1
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
	routes: {
		menu: "menu",
		menuitem:"menuitem"
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
		//	XBS.splash.init();
			//XBS.cache.init();
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
			return true;
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
		/*fetchImages: function() {
			//todo: callbacks for errors
			XBS.stopwatch.start();
			$(XSM.global.imageQueue).loadImages({
				allLoadedClb:function() { $(window).trigger(XBS.evnt.assetsLoaded);},
				imgErrorClb: function() {pr(this, "ImageError", true)},
				imgLoadedClb: function() {pr(this, "ImageLoaded");},
				noImgClb: function() {pr(this, "No Images!", true)}
			});
		}*/
	},
	layout: {
		init: function() {
			try {
				XBS.fn.execInitSequence(XBS.layout.jq_binds);
				if (XBS.cfg.isSplash) {
					XBS.layout.detachAnimationTargets();
				}
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
		jq_binds: {
			has_init_sequence:true,
			bind_confirm_order: function() {
				$("body").on(C.CLK, ".confirm-order", null, function(e) {
						XBS.layout.toggle_orb_card()
				});
			},
			bind_activizing_lists: function() {
					$("body").on(C.CLK, XSM.global.activizing_list, function(e) {
						XBS.layout.activize(e.currentTarget);
				});

				return true
			},
			bind_multi_activizing_siblings: function() {
					$("body").on(C.CLK, XSM.global.multi_activizing, function(e) {
						XBS.layout.multi_activize(e.currentTarget);
				});

				return true
			},
			bind_ajax_nav: function() {
				$(XSM.global.ajaxLink).each(function() {
					$(this).on(C.CLK, null, $(this).data(), XBS.load);
				});

				return true;
			},
			bindAssetsLoadedListener: function() {
				if (XBS.cfg.developmentMode) return true;
				$(window).on(XBS.evnt.assetsLoaded.type, function() {
//						if (XBS.cfg.execInitSequenced = true) {
							XBS.layout.readyLoadingScreen();
//						}
					});
				return true;
			},
			bind_cart_hooks: function() {
//				$(XSM.menu.add_to_cart_hook).on(C.CLK, null, null, XBS.fn.configure_orb);
				$("#orb-card-wrapper ").on(C.CLK, ".add-to-cart", null, function(e) {
						var data = $(e.currentTarget).data('orbId');
						XBS.fn.configure_orb(data.orbId, data.priceRank)
				});
			},
			bind_float_menus: function() {
				$(C.BODY).on(C.MOUSEENTER, XSM.effects.float_label, null, function(e) {
					var data = $(e.currentTarget).data();
					XBS.layout.toggle_float_label(data.floatLabel, C.SHOW);
				});
				$(C.BODY).on(C.MOUSEOUT, XSM.effects.float_label, null, function(e) {
					XBS.layout.toggle_float_label($(e.currentTarget).data('floatLabel'), C.HIDE);
				});
			},
			bind_orbcard_refresh: function() {
				$(C.BODY).on(C.CLK, XSM.menu.orb_card_refresh, null, function(e) {
					XBS.layout.refresh_orb_card_stage($(e.currentTarget).data('orb'));
				});
			},
			bind_orbcat_refresh: function() {
				$(C.BODY).on(C.CLK, XSM.menu.orbcat_refresh, null, function(e) {
					var data = $(e.currentTarget).data();
					XBS.layout.refresh_active_orbs_menu(data.orbcat, data.orbcatName);
				});
				return true;
			},
			windowResizeListener: function() {
				if (XBS.cfg.isSplash) {
					$(window).on("resize", XBS.splash.render);
				}
				return true;
			}
		},
		style_effects: {
			has_init_sequence: true,
			solidify: function () {
				$(XSM.effects.solidify).each(function() {

				});
				return true;
			}
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
		activize: function(element) {
			if (isEvent(arguments[0]) ) element = element.currentTarget;
			if ($(element).hasClass("inactive")) {
				$(element).removeClass('inactive')
					.addClass('active')
					.siblings("li.active")
					.each(function() {
						$(this).removeClass('active').addClass('inactive');
				});
			}
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
		multi_activize: function(element) {
			if ($(element).hasClass('active') ) {
				$(element).removeClass('active').addClass('inactive');
			} else if ($(element).hasClass('inactive')) {
				$(element).removeClass('inactive').addClass('active');
			}

		},
		readyLoadingScreen: function() {
			if (XBS.cfg.developmentMode) {
				XBS.fn.layout.toggleLoadingScreen();
				return true;
			}
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
		toggle_float_label: function(float_label, state) {
			if (state == C.SHOW) $(asId(float_label)).addClass(XSM.effects.exposed);
			if (state == C.HIDE) $(asId(float_label)).removeClass(XSM.effects.exposed);

			return true;
		},
		toggleLoadingScreen: function() { $(XSM.global.loadingScreen).fadeToggle(); },
		toggle_orb_card: function() {
			$(["favorite-label","order-label","like-label"]).each(function() {
				XBS.layout.toggle_float_label(this, C.HIDE);});
			$(XSM.menu.orb_card_3d_context).toggleClass(stripCSS(XSM.effects.flipped_y));
			$(XSM.menu.active_orbs_menu).slideToggle(500, function() {
					$(XSM.menu.toppings_list).slideToggle(500);
			});

		},
		refresh_active_orbs_menu: function(orbcat_id, orbcat_name) {
			// todo: fallback on ajax fail
			var url  = C.DS + XBS.cfg.root + C.DS + XBS.routes.menu + C.DS + orbcat_id
			$.get(url, function(data) {
				if ( $(XSM.menu.active_orb_name_3d_context).hasClass(XSM.effects.flipped_x) ) {
					$(XSM.menu.active_orb_name_front_face).html(orbcat_name);
					$(XSM.menu.active_orb_name_3d_context).removeClass(XSM.effects.flipped_x);
				} else {
					$(XSM.menu.active_orb_name_back_face).html(orbcat_name);
					$(XSM.menu.active_orb_name_3d_context).addClass(XSM.effects.flipped_x);
				}
				$(XSM.menu.active_orbs_menu_item).each(function() { $(this).addClass('fade-out');});

				$(XSM.menu.orbcat_menu_title_subtitle).fadeToggle();
				setTimeout(function() {
					$(XSM.menu.active_orbs_menu).replaceWith(data);
					$(XSM.menu.orbcat_menu_title_subtitle).html(orbcat_name);
					$(XSM.menu.orbcat_menu_title_subtitle).fadeToggle();
					setTimeout(function() {
						$(XSM.menu.orbcat_menu_title_header).animate({'width':"100%"}, 300)
//						$(XSM.menu.orbcat_menu_title_header).removeClass(XSM.effects.transitioning);
						$(XSM.menu.active_orbs_menu_item).each(
							function() { $(this).removeClass('fade-out');}
						)}, 300);
				}, 600);
			});
		},
		refresh_orb_card_stage: function(orb_card_id) {
			// todo: fallback on ajax fail
			$.get(C.DS + XBS.cfg.root + C.DS + XBS.routes.menuitem + C.DS + orb_card_id, function(data) {
				$(XSM.menu.orb_card_stage).replaceWith(data);
			});
		}
	},
	splash: {
		init: function() {
			if (!XBS.cfg.isSplash) return true;
			XBS.fn.execInitSequence(XBS.splash.jq_binds);
			$(XSM.splash.self).on(C.MOUSEOVER, function() {
					$(XSM.splash.openingDeal).slideDown();
					$(this).unbind(C.HOVER);
			});
			XBS.splash.render();

			return true;
		},
		jq_binds: {
			has_init_sequence: true,
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
			if (!exists(initOb.has_init_sequence) ) {
				throw new TypeError("Object passed to XBS.fn.execInitSequence() does not contain 'has_init_sequence' property.");
			}
			var sitRep = {};
			for (fn in initOb) { if (fn != "has_init_sequence") sitRep[fn] = initOb[fn](); }
			return sitRep;
		},
		configure_orb: function(orb_id, price_rank) {
			if (isEvent(arguments[0])) {
				var data = $(arguments[0].currentTarget).data();
				orb_id = data.orbId;
				price_rank = data.priceRank;
			}

			$(".orb-size-button").each( function() {
				var this_price = $(this).data('priceRank');
				if (  this_price == price_rank) {
					XBS.layout.activize(this); }
			});
			XBS.layout.toggle_orb_card()
		},

		add_to_cart: function() {
			// ajax out to cart
			return true;
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
		}
	}
};