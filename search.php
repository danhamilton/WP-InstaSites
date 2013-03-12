<?php
function property_permalink_list(){
	$propid_array = array();
	$args = array(
		'numberposts' => -1,
		'meta_key' => 'property_id');
	$posts_array = get_pages($args);
	if(count($posts_array)>0){
		foreach($posts_array as $p){
			//print_r($posts_array);exit();
			$propid = get_post_custom_values('property_id',$p->ID);
			$lat = get_post_custom_values('bapi_lat',$p->ID);
			$long = get_post_custom_values('bapi_long',$p->ID);
			if(!empty($propid)){
				$prop_array[$p->ID] = array("propid"=>$propid, "lat"=>$lat[0], "lon"=>$long[0], "headline"=>$p->post_title);
			}
		}
	}
	?>
	<script>
		var prop_post_array = new Array();
	<?php
	foreach($prop_array as $po => $pr){
		?>
		prop_post_array[<?= $pr['propid'][0] ?>] = {"url":"<?= get_permalink($po) ?>", "lat":"<?= $pr['lat'] ?>" ,"lon":"<?= $pr['lon'] ?>", "pageid":"<?= $po ?>", "headline":"<?= $pr['headline'] ?>"};
		<?php
	}
	?>
	</script>
    <?php
}

function property_page_list(){
	$propid_array = array();
	$args = array(
		'numberposts' => -1,
		'meta_key' => 'property_id');
	$posts_array = get_pages($args);
	if(count($posts_array)>0){
		foreach($posts_array as $p){
			//print_r($p);exit();
			$propid = get_post_custom_values('property_id',$p->ID);
			$lat = get_post_custom_values('bapi_lat',$p->ID);
			$long = get_post_custom_values('bapi_long',$p->ID);
			if(!empty($propid)){
				$prop_array[$p->ID] = $propid;
			}
		}
	}
	?>
	<script>
		var page_prop_array = new Array();
	<?php
	foreach($prop_array as $po => $pr){
		?>
		page_prop_array[<?= $po ?>] = {"url":"<?= get_permalink($po) ?>", "propid":"<?= $pr[0] ?>", "lat":"<?= $lat[0] ?>" ,"lon":"<?= $long[0] ?>"};
		<?php
	}
	?>
	</script>
    <?php
}

function bapi_search_page_head($content){
	if((get_post_meta(get_the_ID(),'bapi_search',true)!='')&&(get_post_meta(get_the_ID(),'property_id',true)!='true')){
		$apiKey = get_option('api_key');
		?>
        <?php //property_permalink_list(); ?>
        <?php //property_page_list() ?>
		<!--<script type="text/javascript">
		var ids = [], curpage=1, theEnd=0, loading=1;
		if(BAPI.session().searchmode<1){
			var searchmode = 1;
			BAPI.session().searchmode = 1;
		}
		var map, content, title, streetview, infowindow;
		$(document).ready(function () {
			if($('#qsearch').html()==''){
				BAPI.createSearchWidget('qsearch', null, 
					function () {
						// record the search paarams to our session
						var sparams = BAPI.session().searchparams;
						sparams.checkin = (typeof ($("#qsearch-checkin-val").val()) === "undefined" ? null : $("#qsearch-checkin-val").val());
						sparams.checkout = (typeof ($("#qsearch-checkout-val").val()) === "undefined" ? null : $("#qsearch-checkout-val").val());
						sparams.los = (typeof ($("#qsearch-los").val()) === "undefined" ? null : $("#qsearch-los").val());
						sparams.beds.min = $("#qsearch-beds").val();
						sparams.category = (typeof ($("#qsearch-category").val()) === "undefined" ? null : $("#qsearch-category").val());
						BAPI.savesession();
	
						curpage = 1; // go back to beginning of list
						doSearch(BAPI.session().searchmode); // perform the search
					},
					function () {
						if (confirm('Do you want to clear your search?')) {
							var m = BAPI.session().searchmode
							BAPI.clearsession();
							$("#qsearch-checkin-val").val('');
							$("#qsearch-checkout-val").val('');
							if(m==3){
								window.location.reload();
							}
							doSearch(m);
						}                
					},
					function () {
						alert('advanced clicked');
					}
				);
				BAPI.log(BAPI.session());
				doSearch(BAPI.session().searchmode);
			}    

			$(window).scroll(function() {
				if(loading==0){
					if(($(window).scrollTop() + $(window).height() == $(document).height())&&(theEnd==0)&&(BAPI.session().searchmode!=3)&&loading==0) {
						$('#more-results').html('<div class="search-loading"><p>Finding more rentals...</p><img src="http://phpdemo.bookt.net/img/ajax-loader-search.gif"></div>');
						$('html, a').css('cursor','wait');
						if ((curpage * 5) >= ids.length) {
							$('#more-results').html('<div class="search-loading"><h3>No more search results available</h3></div>');
							$('html, a').css('cursor','');
							return;
						}  
						loading=1;   
						curpage++;
						var wrapper = new Object();
						wrapper.result = ids;
						searchCallback(wrapper);    
					}
				}
				else{
					return(false);
				}
			});
			
			$('.datepicker').datepicker({
				numberOfMonths: 2,
				minDate: 0
			});


		});
		
		function doSearch(newSearchMode) {
			$('#more-results').html('<div class="search-loading"></div>');
			$('#init-loading').html('<div class="search-loading"><p>Finding rentals...</p><img src="http://phpdemo.bookt.net/img/ajax-loader-search.gif"></div>');
			$('#more-results-map').html('<div class="search-loading"><p>Finding rentals...</p><img src="http://phpdemo.bookt.net/img/ajax-loader-search.gif"></div>');
			$('html, a').css('cursor','wait');
			BAPI.session().searchmode = newSearchMode; // switch our session to the new mode
			BAPI.savesession();
			$('#search-results').empty(); // make sure prev search results are cleared
			BAPI.search(BAPI.entities.property, BAPI.session().searchparams, searchCallback);
		}

		
		function searchCallback(data) {
			ids = data.result;
			$('#numresults').text(ids.length);
			var pagesize=5;
			if(BAPI.session().searchmode == 3){
				pagesize=20;
			}
			if (BAPI.session().searchmode == 3) {
				$('#reg-view').hide();
				$('#map-view').show();
				$('#init-loading').html('');
				var options = {width:"400px", height:"200px"};
				var tmp = displayMapView(ids, 'map-view-map', options, mapInfoWindow);
				$('html, a').css('cursor','');
				loading=0;
				return;
			}
			BAPI.get(ids, BAPI.entities.property,
				{
				"seo": true,
				"favorites": true, 
				"page": curpage,
				"checkin": BAPI.session().searchparams.checkin,
				"checkout": BAPI.session().searchparams.checkout,
				"los": BAPI.session().searchparams.los,
				"pagesize": pagesize
				},       
				function (pdata) {
					if (pdata.result.length == 0) { return; }
					var searchmode = BAPI.session().searchmode;
					if (searchmode == 1) {
						$('#reg-view').show();
						$('#map-view').hide();
						var tmp = displayListView(pdata);
						$("#search-results").append(tmp);
						$('html, a').css('cursor','');
						$('#init-loading').html('');
						loading=0;
					}
					else if (searchmode == 2) {
						$('#reg-view').show();
						$('#map-view').hide();
						var tmp = displayGalleryView(pdata);
						$("#search-results").append(tmp);
						$('html, a').css('cursor','');
						$('#init-loading').html('');
						loading=0;
					}
		
					$('.favorite').click(function (event) {
						var pkid = $(this).attr('data-id');
						var isfavorited = $(this).attr('checked')
						if (isfavorited == 'checked') {
							BAPI.session().favorites.add(pkid, function (res) {
								BAPI.log(pkid + ' added to favorites.');
							});
						}
						else {
							BAPI.session().favorites.del(pkid, function (res) {
								BAPI.log(pkid + ' removed from favorites.');
							});
						}
					});
				});
		}

		function displayListView(pdata) {    
			$('#more-results').html('');
			$.each(pdata.result, function (i, item) {
				BAPI.log(item);
				$("#search-results").append($("<div>", { class: "portal-block", "data-lat": item.latitude, "data-lng": item.longitude, "id": "p" + item.ID })
					.append($("<div>", { class: "portal-inner-left" })
						.append($("<div>", { class: "portal-thumbnail" })
							.append($("<div>", { class: "rownumber", text: ((curpage-1)*5 + i + 1).toString() }))
							.append($("<a>", { href: prop_post_array[item.ID].url })
							.append($("<img>", { class: "portal-thumb-img", alt: item.Images[0].Caption, src: item.Images[0].ThumbnailURL, width: 340 })))
					.append($("<div>", { class: "btn-details-portal" })
						.append($("<a>", { href: prop_post_array[item.ID].url, text: "Details & Availability" }))))
					.append($("<div>", { class: "portal-info" })
						.append($("<h2>").append($("<a>", { href: prop_post_array[item.ID].url }).append($("<span>", { text: item.Headline }))))
						.append($("<small>", { text: "XXX No reviews yet for this Property XXX" }))
						.append($("<div>", { class: "clear" }))
						.append($("<p>", { class: "first" })
							.append($("<span>", { html: "<b>City:</b> " + item.City })))
						.append($("<p>", { html: "<b>Bedrooms:</b> " + item.Bedrooms + "</b> | <b>Bathrooms:</b> " + item.Bathrooms + "</b> | <b>Sleeps:</b> " + item.Sleeps }))
						.append($("<span>").append($("<p>", { html: item.Summary })))
					.append($("<div>", { class: "portal-rates", style: "top:0", text: item.ContextData.Quote.PublicNotes }))
					.append($("<div>", { style: "clear:both" })))).append($("<div>", { style: "clear:both" })));
			});
		}
		
		function displayGalleryView(pdata) {
			$('#more-results').html('');
			$.each(pdata.result, function (i, item) {
				//BAPI.log(item);
				$("#search-results").append($("<div>", { class: "gallery-block", "data-lat": item.latitude, "data-lng": item.longitude, "id": "p" + item.ID })
					.append($("<a>", { href: prop_post_array[item.ID].url })
						.append($("<h2>", { text: item.Headline })))
					.append($("<div>", { class: "" })
						.append($("<a>", { href: prop_post_array[item.ID].url })
							.append($("<img>", { alt: item.Images[0].Caption, src: item.Images[0].OriginalURL }))))
					.append($("<a>", { href: prop_post_array[item.ID].url })
						.append($("<span>", { class: "", text: item.Bedrooms+" Bedrooms | "+item.Bathrooms+" Bathrooms | Sleeps "+item.Sleeps }))
					.append($("<div>", { style: "clear:both" }))).append($("<div>", { style: "clear:both" })));
			});
		}
		
		function displayMapView(ids, mapid, options, infowindowCallback) {
			//alert('test');
			if (typeof(options) === "undefined" || options == null) {
				options = { width: "100%", height: "500px" };
			}
			var infoFunc;
			if (typeof(infowindowCallback) === "undefined" || infowindowCallback == null)
				infoFunc = openInfoWindow;
			else
				infoFunc = infowindowCallback;
				
			map = new google.maps.Map(document.getElementById(mapid),{ mapTypeId: google.maps.MapTypeId.ROADMAP, streetViewControl: false });
			content = document.createElement("DIV");    
			title = document.createElement("DIV");
			content.appendChild(title);
			streetview = document.createElement("DIV");
			streetview.style.width = options.width;
			streetview.style.height = options.height;
			content.appendChild(streetview);
			infowindow = new google.maps.InfoWindow({ content: content });		
			var bounds = new google.maps.LatLngBounds();
			var l = ids.length;
			var t = 0;
			$.each(ids, function (i, item) {
				//alert(page_prop_array[52883]);
				var pp;
				var pt = new google.maps.LatLng(prop_post_array[item].lat, prop_post_array[item].lon);
				var marker = new google.maps.Marker({
					position: pt,
					map: map,
					title: prop_post_array[item].headline
				});
				google.maps.event.addListener(marker, "click", function () { 
					infoFunc(title, marker, item, 'property'); 
					infowindow.open(map, marker); 
				});
				bounds.extend(pt);
				map.fitBounds(bounds); 
				t++;
				if(t==l){
					$('#more-results-map').html('');
				}
			});
		}
		
		function mapInfoWindow(title, marker, pid, type) {
			BAPI.get(pid, BAPI.entities.property, {}, function(data){
				BAPI.log(data.result[0]); 
				var p = data.result[0];
				BAPI.log(p);
				var outerdiv = $("<div>");
				var imgdiv = $("<div>", { class: "left", style: "width:175px; padding-left:10px; float:left;" });
				imgdiv.append($("<img>", { src: p.Images[0].ThumbnailURL, caption: p.Images[0].Caption, width: "175" }));
				imgdiv.append($("<a>", { href: prop_post_array[p.ID].url, text: "Details & Availability" }));
				outerdiv.append(imgdiv);
			
				var ddiv = $("<div>", { class: "right", style: "width:220px; padding:0 10px; float:right;" });
				outerdiv.append(ddiv);
				ddiv.append($("<div>").append($("<b>", { text: p.Headline })));
				//ddiv.append($("<div>", { text: p.Type }));
			
				var st = p.Summary
				var stl = st.length
				if(stl>85){
					st = st.substring(0,85)+'...';
				}
				var summary = $("<div>").st
				ddiv.append($("<div>", { text: summary, style:"display:block;border-bottom:1px solid #ccc;margin-top:8px;" }));
				ddiv.append($("<div>", { text: p.Amenities.slice(0,5).join(", "), style:"display:block;border-bottom:1px solid #ccc;margin-top:8px;" }));
				ddiv.append($("<div>", { text: p.ContextData.Quote.PublicNotes, style:"display:block;margin-top:8px;" }));
				ddiv.append($("<div>", { class: "clear" }));
				//BAPI.log(outerdiv.html());
				title.innerHTML = outerdiv.html(); //marker.getTitle();
				infowindow.open(map, marker);
			});
		}
		
		function getQuote(pid) {
			alert("Get Quote");
		}

		</script>-->
        <?php
	}
}

function bapi_search_page_body($content){
	if((get_post_meta(get_the_ID(),'bapi_search',true)!='')&&(get_post_meta(get_the_ID(),'property_id',true)!='true')){
		?> 
        <style type="text/css">
			<?php echo get_option('search_css'); ?>
        </style>
        <?php echo get_option('search_template'); ?>
		<?php
	}
}
?>