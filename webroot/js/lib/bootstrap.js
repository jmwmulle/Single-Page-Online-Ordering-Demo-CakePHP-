/**
 * Created by jono on 8/22/14.
 *
 * (X)treme(B)oot(S)trap
 * - global, one-off or otherwise external to Ember functions, vars, layout initialization, etc.
 */

window.XBS = {
	init: function() {
		var initStatus = true;
		var sitRep = {
			scaleSplash: XBS.scaleSplash(),
			jqBinds:XBS.jqBinds()
		};

		for (initEl in sitRep) {
			if (!(sitRep[initEl] === true)) {
				initStatus = false;
				/* I do not know if throwing an error is the best approach, here (I've also made no attempt to catch it).
				   I do know that if this has failed, the site isn't going any further; could just as easily return false
				   and be handled elsewhere/later. */
				throw "Bootstrap failed. We're probably under attack. Hide 'ya kids."
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

		return true;
	},
	scaleSplash: function() {
		var splashbarTop = $(".splash-bar").offset().top;
		var scaleFactor = 570/splashbarTop;
		var dealDim = [splashbarTop, scaleFactor *400];
		var dealLeft = String(((window.innerWidth - dealDim[1]) / 2)+( 1.25 * $("#order").innerWidth())) +"px";
		$("#grand-opening-deal").css({
			height:dealDim[0]+"px",
			width:dealDim[1]+"px",
			left:dealLeft
//			paddingTop:scaleFactor * 120,
//			paddingLeft:scaleFactor * 60
		}).on("click", function() {
			pr("wtf?");
			$("body").append("<div id='hide-pane'><div id='register-now'></div></div>");
			$('#hide-pane').css({
				position:"fixed",
				display:"none",
				width:"100%",
				height:"100%",
				zIndex: "99999",
				backgroundColor:"rgba(0,0,0,.3)"
			}).fadeIn();
			$('#register-now').css({
				display:"none",
				position:"relative",
			   width:"800px",
			   height:"550px",
				padding:"24px",
			   margin:"10% auto",
			   zIndex:"9999",
				background: "#feffff",
				background: "-moz-linear-gradient(top,  #feffff 0%, #ddf1f9 35%, #a0d8ef 100%)",
				background: "-webkit-gradient(linear, left top, left bottom, color-stop(0%,#feffff), color-stop(35%,#ddf1f9), color-stop(100%,#a0d8ef))",
				background: "-webkit-linear-gradient(top,  #feffff 0%,#ddf1f9 35%,#a0d8ef 100%)",
				background: "-o-linear-gradient(top,  #feffff 0%,#ddf1f9 35%,#a0d8ef 100%)",
				background: "-ms-linear-gradient(top,  #feffff 0%,#ddf1f9 35%,#a0d8ef 100%)",
				background: "linear-gradient(to bottom,  #feffff 0%,#ddf1f9 35%,#a0d8ef 100%)",
				filter: "progid:DXImageTransform.Microsoft.gradient( startColorstr='#feffff', endColorstr='#a0d8ef',GradientType=0 )",
			   border:"5px solid rgb(255,255,255)",
			   boxShadow:"0 15px 15px rgba(0,0,0,.3)"}).toggle("fadeIn");
				$.get("users/add").done(function(resp) {
					pr(resp);
					$('#register-now').html(resp);
				});
			$("#hide-pane").on("click", function() {
				$("#register-now").fadeOut();
				$(this).fadeOut().remove();
			});



		});

		return true;
	},
	foldSplash: function() {
		var barslide = function() {};
	}

}

