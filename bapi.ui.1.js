;
/* Bookt API */
var BAPI = BAPI || {};

(function(context) {

/* Search Widgeth */
context.searchWidgetOptions = function() {
	this.prompts = false;
	this.outercss = "property-search-field";
	this.outersearchcss = "btn property-search-button-block search-button-block";
	this.btnoutercss = "btn-group property-search-button-block search-button-block";
	this.btnsearchcss = "btn property-search-button";
	this.btnclearcss = "btn property-search-reset";
	this.btnadvancedcss = "";
	this.categorycss = "property-search-input";
	this.categoryctltype = 0;
	this.minbedroomscss = "property-search-input";
	this.minbedroomsctltype = 0;
	this.locationcss = "property-search-input";
	this.locationctltype = 0;
	this.checkincss = "property-search-input ps-dropdown";
	this.checkinctltype = 0;
	this.checkoutcss = "property-search-input";
	this.checkoutctltype = 0;
	this.loscss = "property-search-input";
	this.losctltype = 0;
}

context.createSearchWidget = function (id, options) {
    BAPI.loadsession();
    var sparams = BAPI.session().searchparams;
    if (typeof (options) === "undefined") {
		options = new context.searchWidgetOptions();        
    }
                    
    var q = $('#' + id);
    if (window.config.category.enabled) {
        var c = $("<select>", { id: "qsearch-category", class: options.categorycss })
        c.append($("<option>", { text: '- ' + config.category.prompt + ' -', value: '' }))
        $.each(config.category.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        c.val(sparams.category);
        q.append($("<div>", { class: options.outercss }).append(c));
    }
        
    if (window.config.dev.enabled) {
        var c = $("<select>", { id: "qsearch-dev", class: options.categorycss })
        c.append($("<option>", { text: '- ' + config.dev.prompt + ' -', value: '' }))
        $.each(config.dev.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        c.val(sparams.dev);
        q.append($("<div>", { class: options.outercss }).append(c));
    }
    if (window.config.beds.enabled) {
        var c = $("<select>", { id: "qsearch-beds", class: options.categorycss })
        c.append($("<option>", { text: '- ' + config.beds.prompt + ' -', value: '' }))
        $.each(config.beds.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        c.val(sparams.beds.min);
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.checkin.enabled) {
        var c = $("<input>", { id: "qsearch-checkin", type: "text", class: options.checkincss, value: sparams.checkin })            
        var hf = $("<input>", { id: "qsearch-checkin-val", type: "hidden", value: sparams.checkin })
        var div = $("<div>", { class: options.outercss })
                .append(c)
                .append(hf);
        q.append(div);
        c.datepicker({
            numberOfMonths: 2,
            minDate: config.minbookingdays,
            maxDate: "+" + config.maxbookingdays + "D",
            createButton: false,
            altField: "#qsearch-checkin-val",
            altFormat: "mm/dd/yy",
            beforeShow: function (input, inst) {
                inst.dpDiv.css({ marginTop: 0, marginLeft: -input.offsetWidth + 'px' });
            },
            onSelect: function (dateValue, inst) {
                $("#qsearch-checkout").datepicker("option", "minDate", dateValue);
            }
        });
    }

    if (window.config.checkout.enabled) {
        var c = $("<input>", { id: "qsearch-checkout", type: "text", class: options.checkoutcss, value: sparams.checkout })
        var hf = $("<input>", { id: "qsearch-checkout-val", type: "hidden", value: sparams.checkout })
        var div = $("<div>", { class: options.outercss })
                .append(c)
                .append(hf);
        q.append(div);

        c.datepicker({
            numberOfMonths: 2,
            minDate: config.minbookingdays,
            maxDate: "+" + config.maxbookingdays + "D",
            createButton: false,
            altField: "#qsearch-checkout-val",
            altFormat: "mm/dd/yy",
            beforeShow: function (input, inst) {
                inst.dpDiv.css({ marginTop: 0, marginLeft: -input.offsetWidth + 'px' });
            }
        })
    }

    if (window.config.los.enabled) {
        var c = $("<select>", { id: "qsearch-los", class: options.loscss })
        c.append($("<option>", { text: '- ' + config.los.prompt + ' -', value:'' }))
        $.each(config.los.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        c.val(sparams.los == null ? config.los.defaultval : sparams.los);
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.adults.enabled) {
        var c = $("<select>", { id: "qsearch-adults", class: options.loscss })
        c.append($("<option>", { text: '- ' + config.adults.prompt + ' -' }))
        $.each(config.adults.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.children.enabled) {
        var c = $("<select>", { id: "qsearch-children", class: options.loscss })
        c.append($("<option>", { text: '- ' + config.children.prompt + ' -', value: '' }))
        $.each(config.children.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.altid.enabled) {
        var c = $("<input>", { id: "qsearch-altid", type: "text", class: options.checkoutcss, value: sparams.altid })
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.headline.enabled) {
        var c = $("<input>", { id: "qsearch-headline", type: "text", class: options.checkoutcss, value: sparams.altid })
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.location.enabled) {
        var c = $("<input>", { id: "qsearch-location", type: "text", class: options.checkoutcss, value: sparams.altid })
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.rate.enabled) {
        var c = $("<select>", { id: "qsearch-rate", class: options.loscss })
        c.append($("<option>", { text: '- ' + config.rate.prompt + ' -', value: '' }))
        $.each(config.rate.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        q.append($("<div>", { class: options.outercss }).append(c));
    }

    if (window.config.rooms.enabled) {
        var c = $("<select>", { id: "qsearch-rooms", class: options.loscss })
        c.append($("<option>", { text: '- ' + config.rooms.prompt + ' -', value: '' }))
        $.each(config.rooms.values, function (i, item) {
            c.append($("<option>", { text: item.split('|')[0], value: item.split('|')[1] }))
        })
        q.append($("<div>", { class: options.outercss }).append(c));
    }
                
    // add the buttons
    var d = $("<div>", { class: options.btnoutercss });
    d.append($("<a>", { class: options.btnsearchcss, text: config.searchtext, onclick: 'onSearch(' + id + ');' }));        
    d.append($("<a>", { class: options.btnclearcss, text: "clear", onclick: 'onClear();' }));            
    q.append(d);
        
    q.append($("<div>", { class: "clear" }));    
}

/* Search Results */
context.searchResults = {
	listview: function(pdata) {
		var tmp = $("<p>");
		$.each(pdata.result, function (i, item) {
			tmp.append($("<div>", { class: "portal-block", "data-lat": item.latitude, "data-lng": item.longitude, "id": "p" + item.ID })
            .append($("<div>", { class: "portal-inner-left" })
                .append($("<div>", { class: "portal-thumbnail" })
                    .append($("<div>", { class: "rownumber", text: (item.ContextData.ItemIndex + 1).toString(), "data-id": item.ID }))
                    .append($("<a>", { hef: item.ContextData.SEO.DetailURL })
                    .append($("<img>", { class: "portal-thumb-img", alt: item.Images[0].Caption, src: item.Images[0].ThumbnailURL }))))
            .append($("<div>", { class: "portal-info" })
                .append($("<h2>").append($("<a>", { href: item.ContextData.SEO.DetailURL }).append($("<span>", { text: item.Headline }))))
                .append($("<small>", { text: item.NumReviews==0 ? "No reviews yet" : "Avg Review: " + item.AvgReview  }))                
                .append($("<div>", { class: "clear" }))
                .append($("<p>", { class: "first" })
                    .append($("<span>", { html: "<b>City:</b> " + item.City })))
                .append($("<p>", { html: "<b>" + config.textdata["Beds"] + ":</b> " + item.Bedrooms + "</b> | <b>" + config.textdata["Baths"] + ":</b> " + item.Bathrooms + "</b> | <b>" + config.textdata["Sleeps"] + ":</b> " + item.Sleeps }))
                .append($("<span>").append($("<p>", { html: $(item.Summary).text() })).jTruncate({ length: 75, moreText: "" }))
            .append($("<div>", { class: "portal-rates", style: "top:0", text: item.ContextData.Quote.PublicNotes }))
            .append($("<div>", { class: "btn-details-portal" })
                .append($("<a>", { href: item.ContextData.SEO.DetailURL, text: config.textdata["Details & Availability"] })))
            .append($("<div>", { class: "compare" })
                .append($("<a>", { href: '/rentalsearch/compareproperties.aspx', text: config.textdata["Compare"] }))
                .append($("<input>", { type: "checkbox", class: "favorite", text: "favorite", "data-id": item.ID }).attr('checked', item.ContextData.Favorited)))
            .append($("<div>", { style: "clear:both" })))));
		});
		return tmp;		
	},
	
	galleryview: function(pdata) {
		var tmp = $("<p>");
		$.each(pdata.result, function (i, item) {
			tmp.append($("<div>", { class: "gallery-block", "data-lat": item.latitude, "data-lng": item.longitude, "id": "p" + item.ID })
            .append($("<div>", { class: "gallery-block-inner" })
                .append($("<div>", { class: "gallery-block-left" })
                    .append($("<div>", { class: "gallery-thumbnail" })
                        .append($("<div>", { class: "rownumber", text: (item.ContextData.ItemIndex + 1).toString() }))
                        .append($("<a>", { hef: "#" })
                        .append($("<img>", { class: "gallery-imgpropthumb", alt: item.Images[0].Caption, src: item.Images[0].ThumbnailURL })))))
                    .append($("<div>", { class: "clear2" }))
                    .append($("<a>", { href: item.ContextData.SEO.DetailURL, class: "btn-Details", text: config.textdata["All Details & Photos"] }).append($("<span>", { class: "gallery-arrow" })))
                    .append($("<div>", { class: "clear_5px" }))
                    .append($("<a>", { href: item.ContextData.SEO.BookingURL, class: "btn-quote", text: (item.IsBookable ? config.textdata["Book Now"] : config.textdata["Get a Quote"]) }).append($("<span>", { class: "gallery-arrow" }))))
            .append($("<div>", { class: "gallery-info" })
                .append($("<h2>", { class: "property-headline" }).append($("<a>", { href: item.ContextData.SEO.DetailURL }).append($("<span>", { text: item.Headline }))))
                .append($("<div>", { class: "clear" }))
                .append($("<div>", { class: "gallery-room", html: "<b>" + config.textdata["Beds"] + ":</b> " + item.Bedrooms + "</b> | <b>" + config.textdata["Baths"] + ":</b> " + item.Bathrooms + "</b> | <b>" + config.textdata["Sleeps"] + ":</b> " + item.Sleeps }))
                .append($("<div>", { class: "top-amenities", text: item.Amenities.slice(0, 5).join(", ") }))
                .append($("<h2>", { class: "property-rate-value", text: item.ContextData.Quote.PublicNotes }))
                .append($("<div>", { class: "compare" })
                    .append($("<a>", { href: '/rentalsearch/compareproperties.aspx', text: config.textdata["Compare"] }))
                    .append($("<input>", { type: "checkbox", class: "favorite", text: "favorite", "data-id": item.ID }).attr('checked', item.ContextData.Favorited))))
            .append($("<div>", { style: "clear:both" })));
		});
		return tmp;		
	},
	
	mapview: function(pdata, mapid, options, infowindowCallback) {
		if (typeof(options) === "undefined" || options == null) {
			options = { width: "960px", height: "500px" };
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
		$.each(pdata.result, function (i, item) {
			var pt = new google.maps.LatLng(item.Latitude, item.Longitude);
			var marker = new google.maps.Marker({
				position: pt,
				map: map,
				title: item.Headline
			});
			google.maps.event.addListener(marker, "click", function () { 
				infoFunc(title, marker, item, 'property'); 
				infowindow.open(map, marker); 
			});
			bounds.extend(pt);
			map.fitBounds(bounds);
		});   

		function openInfoWindow(title, marker, p, type) {
			var outerdiv = $("<div>");
			var imgdiv = $("<div>", { class: "left" });
			imgdiv.append($("<img>", { src: p.Images[0].ThumbnailURL, caption: p.Images[0].Caption }));
			outerdiv.append(imgdiv);

			var ddiv = $("<div>", { class: "left", style: "width:400px; padding-left:10px" });
			outerdiv.append(ddiv);
			ddiv.append($("<div>").append($("<b>", { text: p.Headline })));
			ddiv.append($("<div>", { text: p.Type }));

			var summary = $("<span>").html(p.Summary).text();
			ddiv.append($("<div>", { text: summary }).jTruncate());
			//ddiv.append($("<div>", { text: p.Amenities.slice(5).join(", ") }));
			//ddiv.append($("<div>", { text: p.ContextData.Quote.PublicNotes }));
			ddiv.append($("<a>", { href: p.ContextData.SEO.DetailURL, text: config.textdata["Details & Availability"] }));
			title.innerHTML = outerdiv.html(); //marker.getTitle();
		}			
	}
}

/* Availability Widget */
context.availabilityWidgetOptions = function() {
	this.availcalendarmonths = 6;	
	this.minbookingdays = 0;
	this.maxbookingdays = 366;
}

context.createAvailabilityWidget = function (p, id, options) {
    if (typeof (options) === "undefined" || options == null) {
		options = new context.availabilityWidgetOptions();        
    }
	$(id).datepicker({
		numberOfMonths: options.availcalendarmonths,
		minDate: options.minbookingdays,
		maxDate: "+" + options.maxbookingdays + "D",
		createButton: false,
		beforeShowDay: function (date) {
			var taken = false;
			$.each(p.ContextData.Availability, function (index, item) {
				if (date >= BAPI.utils.jsondate(item.CheckIn) && date < BAPI.utils.jsondate(item.CheckOut) - 1)
					taken = true;
			})
			if (!taken) {
				return [true, "avail", ''];
			}
			else {
				return [false, 'unavail', ''];
			}
		}
	})
}

context.rateWidgetOptions = function() {
	this.tableclass = "rate-table";
	this.trheadclass = "rate-table-trhead";
	this.thclass = "rate-table-th";
	this.trclass = "rate-table-tr";
	this.traltclass = "rate-table-tralt";
	this.tdclass = "rate-table-td";
	this.tdaltclass = "rate-table-tdalt";
}
context.createRateWidget = function (p, id, options) {
	if (typeof (options) === "undefined" || options == null) {
		options = new context.rateWidgetOptions();        
    }
	var ctx = p.ContextData;
	if (typeof (ctx) === "undefined" || ctx == null) { ctx = new Object(); }
	if (typeof (ctx.Rates) == "undefined" || ctx.Rates == null) { ctx.Rates = new Object(); }
	if (typeof (ctx.Rates.Values) === "undefined" || ctx.Rates.Values == null) { ctx.Rates.Values = new Object(); }
	if (typeof (ctx.Rates.Values.length) === "undefined" || ctx.Rates.Values.length == null) { ctx.Rates.Values.length = 0; }
	
	if (ctx.Rates.Values.length <= 0) {
		$(id).append($('<div>', { text: 'No rates available' }));
		return;
	}
	
    var table = $('<table>', { class: options.tableclass });
    var th = $('<thead>');
    table.append(th);

    var tr = $('<tr>', { class: options.trheadclass })
    $.each(p.ContextData.Rates.Keys, function (index, item) {
        tr.append($('<th>', { scope: "col", class: options.thclass, text: item }));
    });
    th.append(tr);

    var tb = $('<tbody>');
    table.append(tb);
    $.each(p.ContextData.Rates.Values, function (index, rateitem) {
        var tr2 = $('<tr>', { class: index % 2 == 0 ? options.trclass : options.traltclass })
        var tdclass = index % 2 == 0 ? options.tdclass : options.tdaltclass;
        $.each(rateitem, function (index2, val) {
            tr2.append($('<td>', { text: val, class: tdclass }));
        });
        tb.append(tr2);
    });

    $(id).append(table);
	return table;
}

/* Mapping */
function staticMapOptions() {
	this.zoom = 14;
	this.width = 300;
	this.height = 450;
}
context.createStaticPropertyMap = function (id, prop, options) {
	if (options == null) {
		options = new staticMapOptions();
	}
	var url = 'https://maps.googleapis.com/maps/api/staticmap?center=' + prop.Latitude + ',' + prop.Longitude + 
				'&zoom=' + options.zoom + 
				'&size=' + options.width + 
				'x' + options.height + 
				'&maptype=roadmap&markers=color:blue%7Clabel:%20%7C' + prop.Latitude + ',' + prop.Longitude + '&sensor=false';
	$(id).append($("<img>", { src: url }));
}

context.createPropertyMap = function (id, prop, options, infowindowCallback) {
	var map = new google.maps.Map(document.getElementById(id), { mapTypeId: google.maps.MapTypeId.ROADMAP, streetViewControl: false });
	
	var infoFunc;
	if (typeof(infowindowCallback) === "undefined" || infowindowCallback == null)
		infoFunc = openInfoWindow;
	else
		infoFunc = infowindowCallback;
		
	var content = document.createElement("DIV");
	var title = document.createElement("DIV");
	content.appendChild(title);
	var streetview = document.createElement("DIV");
	streetview.style.width = options.width;
	streetview.style.height = options.height;
	content.appendChild(streetview);
	infowindow = new google.maps.InfoWindow({ content: content });
	var bounds = new google.maps.LatLngBounds();
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(prop.Latitude, prop.Longitude),
		map: map,
		title: prop.Headline
	});		
	google.maps.event.addListener(marker, "click", function () { 
		infoFunc(title, marker, prop, 'property') 
		infowindow.open(map, marker); 
	});	
	var bounds = new google.maps.LatLngBounds();
	bounds.extend(new google.maps.LatLng(prop.Latitude, prop.Longitude));            
	map.fitBounds(bounds);

	// add a marker for all the poi
	if (options.showpoi && prop.ContextData.Attraction != null) {
		$.each(prop.ContextData.Attractions, function (index, poi) {
			var mpoi = new google.maps.Marker({
				position: new google.maps.LatLng(poi.Latitude, poi.Longitude),
				map: map,
				title: poi.Name
			});

			google.maps.event.addListener(mpoi, "click", function () { 
				infoFunc(title, mpoi, poi, 'poi'); 
				infowindow.open(map, marker); 
			});
			bounds.extend(new google.maps.LatLng(poi.Latitude, poi.Longitude));
			map.fitBounds(bounds);
		});
	}
	
	function openInfoWindow(title, marker, data, type) {
		title.innerHTML = marker.getTitle();			
	}
	return map;
}

context.createPOIGrid = function (id, prop, options) {
	if (typeof (options) === "undefined" || options == null) {
		options = new context.rateWidgetOptions();
	}
	var table = $('<table>', { class: options.tableclass });
	var th = $('<thead>');
	table.append(th);

	var tr = $('<tr>', { class: options.trheadclass })
	tr.append($('<th>', { scope: "col", class: options.thclass }));
	tr.append($('<th>', { scope: "col", class: options.thclass, text: 'Attraction' }));
	tr.append($('<th>', { scope: "col", class: options.thclass, text: 'Category' }));
	tr.append($('<th>', { scope: "col", class: options.thclass, text: 'Distance' }));
	th.append(tr);

	var tb = $('<tbody>');
	table.append(tb);
	$.each(prop.ContextData.Attractions, function (index, poi) {
		var tr2 = $('<tr>', { class: index % 2 == 0 ? options.trclass : options.traltclass })
		var tdclass = index % 2 == 0 ? options.tdclass : options.tdaltclass;
		tr2.append($('<td>').append($('<a>', { onclick: 'alert("clicked->needs to open infowindow");', text: (index + 1), class: tdclass })));
		tr2.append($('<td>', { text: poi.Name, class: tdclass }));
		tr2.append($('<td>', { text: poi.Type, class: tdclass }));
		tr2.append($('<td>', { text: poi.ContextData.Distance, class: tdclass }));
		tb.append(tr2);
	});

	$(id).append(table);
	return table;
}

context.createCheckInDatePicker = function (id, prop, options, config) {
	if (typeof (options) === "undefined" || options == null) { options = new Object(); }
	if (typeof (config) === "undefined" || config == null) { config = new Object(); }
	if (typeof (config.minlos) === "undefined") { config.minlos = 1; }
	
	if (typeof (options.ShowOn) === "undefined") { options.ShowOn = 'both'; }
	if (typeof (options.buttonImage) === "undefined") { options.buttonImage = 'http://booktplatform.s3.amazonaws.com/App_SharedStyles/images/checkInBtn.png'; }
	if (typeof (options.buttonImageOnly) === "undefined") { options.buttonImageOnly = true; }
	if (typeof (options.dateFormat) === "undefined") { options.dateFormat = 'mm/dd/yy'; }
	if (typeof (options.firstDate) === "undefined") { options.firstDate = 1; }
	if (typeof (options.minDate) === "undefined") { options.minDate = config.minbookingdays; }
	if (typeof (options.maxDate) === "undefined") { options.maxDate = "+" + config.maxbookingdays + "D"; }            
	
	options.beforeShow = function (input, inst) {
		if (prop != null) {
				context.log(input);
		}
	}	
	
	if (!(typeof (options.checkoutID) === "undefined")) {
		options.onSelect = function(dateText, inst) {
			var cout = $(options.checkoutID);
			var theDate = new Date(Date.parse($(this).datepicker('getDate')));	
			var newMinDate = new Date();
			newMinDate.setDate(theDate.getDate() + 1);
			cout.datepicker('option', 'minDate', newMinDate); 
			var selcout = cout.datepicker('getDate');
			if (selcout == null) {
				var newCheckOut = new Date();
				newCheckOut.setDate(theDate.getDate() + config.minlos);
				cout.datepicker('setDate', newCheckOut);
			}
		}
	}
				
	$(id).datepicker(options);	
}

context.createCheckOutDatePicker = function (id, prop, options, config) {	
	if (typeof (options) === "undefined" || options == null) { options = new Object(); }
	if (typeof (config) === "undefined" || config == null) { config = new Object(); }
	
	if (typeof (options.ShowOn) === "undefined") { options.ShowOn = 'both'; }
	if (typeof (options.buttonImage) === "undefined") { options.buttonImage = 'http://booktplatform.s3.amazonaws.com/App_SharedStyles/images/checkInBtn.png'; }
	if (typeof (options.buttonImageOnly) === "undefined") { options.buttonImageOnly = true; }
	if (typeof (options.dateFormat) === "undefined") { options.dateFormat = 'mm/dd/yy'; }
	if (typeof (options.firstDate) === "undefined") { options.firstDate = 1; }
	if (typeof (options.minDate) === "undefined") { options.minDate = config.minbookingdays + 1; }
	if (typeof (options.maxDate) === "undefined") { options.maxDate = "+" + config.maxbookingdays + "D"; }            
	
	options.beforeShow = function (input, inst) {
		if (prop != null) {
				context.log(input);
		}
	}	
	
	if (!(typeof (options.checkinID) === "undefined")) {
		options.onSelect = function(dateText, inst) {
			var cout = $(options.checkoutID);
			context.log(cout.datepicker('getDate'));
			//var theDate = new Date(Date.parse($(this).datepicker('getDate')));	
				//context.log(theDate);
		}
	}
				
	$(id).datepicker(options);	

}

/* Lead Request */
context.createInquiryForm = function (id, prop, options) {	
	if (typeof (options) === "undefined" || options == null) { options = new Object(); }	
}

})(BAPI); 
