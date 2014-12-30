/**
 * Created by jono on 12/29/14.
 */
function StopWatch() {
	this.sw = new Date();
	this.start_time = 0;
	this.now = function () { return this.sw.getTime(); };
	this.reset = function () { this.start_time = 0},
	this.start = function () { this.start_time = this.now(); };
	this.read = function () { return this.now() - this.start_time;},
	this.elapsed = function (period) {
		var diff = this.now() - this.start_time;
		return (period !== "undefined") ? diff > period : diff;
	};

	return this;
}