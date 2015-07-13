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