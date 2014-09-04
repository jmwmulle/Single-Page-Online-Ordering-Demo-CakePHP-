<?php
/**
 * J. Mulle, for app, 9/3/14 9:39 PM
 * www.introspectacle.net
 * Email: this.impetus@gmail.com
 * Twitter: @thisimpetus
 * About.me: about.me/thisimpetus
 */

	?>
<div class="row">
 	<!--<div {{bind-attr class=":large-4 :small-6 :columns :thumb hasThumbnail::noThumb "}}>-->
 	<div class="large-4 small-6 columns thumb">
 		<img src="img/menu/pepperoni_pizza.jpg" />
 		<!--<img {{bind-attr src="thumbSource"}} />-->
 	</div>
 	<h3 class="large-8 small-6 columns">
 		{{title}} {{#if subtitle}}<span class="subheader">{{subtitle}}</span>{{/if}}
 	</h3>
 </div>
 <div class="row">
 	<p class="large-12 small-12 columns">{{description}}</p>
 </div>
 <div class="row">
 	<dl class="large-12 small-12 columns price-table">
 	{{#each pricingOption}}
 				<dt>{{#if pricingFlag}}*{{/if}}{{pricingCondition}}</dt>
 				<dd>${{price}}</dd>

 	{{/each}}
 	</dl>
 	<ul class="icon-row">
 		{{#each}}
 			<li {{bind-attr class=":iconName"}} {{action iconAction}} {{bind-attr alt="iconAltText"}}></li>
 		{{/each}}
 	</ul>
 </div>