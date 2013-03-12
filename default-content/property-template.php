<input type="hidden" id="pkid" value="{{ID}}" />
<h1 class="property-headline">{{Headline}}</h1>
<div id="prop-detail-tabs">
<ul class="mainTabs alwaysvisible">
        <li><a href="#tabs-1">General</a></li>
	<li><a href="#tabs-2">Rates & Availability</a></li>
	<li><a href="#tabs-3">Amenities</a></li>
	<li><a href="#tabs-4">Reviews</a></li>
	<li><a href="#tabs-5">Map & Attractions</a></li>
</ul>

<div id="tabs-1" class="shadow-box">	
	<div id="ad-gallery" class="ad-gallery">
		<div class="ad-image-wrapper"></div>
		<div class="ad-controls"></div>
		<div class="ad-nav">
			<div class="ad-thumbs">
				<ul class="ad-thumb-list">
                {{#Images}}
                	<li><a href="{{MediumURL}}"><img src="{{ThumbnailURL}}" alt="{{Caption}}" caption="{{Caption}}" height="80" width="120"/></a></li>
                {{/Images}}
            	</ul>
			</div>
		</div>
	</div>
<div class="clear2"></div>	
<div class="shadow-box general-lower">                              
	<div class="description">
		<div class="desc-text"><h1 class="desc-title">Description</h1></div>
		<div class="clear"></div>
		{{{Description}}}
	</div>		
</div>

<div id="tabs-2" class="shadow-box">
	<h1>Rates</h1>
	<div id="rates" class="rate-grid"></div>               	
	<div class="clear2"></div>
	<h1 class="availabilityprompt">Availability</h1>
	<div id="availability" class="availability"></div>

	<div class="clear2"></div>
	<div class="clear2"></div>              	
</div>

<div id="tabs-3" class="shadow-box">	
	<div class="amenities">
		<h1 class="ameni-title">Amenities</h1>
		<div class="ameni-list">
			<ul>
			{{#Amenities}}
				<li>{{.}}</li>
			{{/Amenities}}
			</ul>		
		</div>
		<div class="clear"></div>    
	</div>
</div>                    

<div id="tabs-4" class="shadow-box">
	<p></p>		
	<div class="clear"></div>
</div>
<div class="clear"></div>           

<div id="tabs-5" class="shadow-box">
	<div id="poimap" style="width:100%; height:500px; border:1px solid black"></div>      
	<div class="clear"></div>
	<div id="poigrid"></div>
</div>
</div>
<script>
$(document).ready(function () {
     $("#prop-detail-tabs").tabs();
});
</script>