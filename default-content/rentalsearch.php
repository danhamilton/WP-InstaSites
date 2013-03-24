<header class="row-fluid search-result-controls">
<div class="span7 form-horizontal">
<div class="control-group" style="display: none;"><label class="control-label" for="slcSort">Sort by</label>
<div class="controls"><select id="slcSort"><option>Relevance 1</option><option>Relevance 2</option><option>Relevance 3</option><option>Relevance 4</option><option>Relevance 5</option></select><span class="help-inline">250 Matches found</span>

</div>
</div>
</div>
<div class="span5 form-horizontal">
<div class="control-group pull-right"><label class="control-label">View as</label>
<div class="controls">
<div class="btn-group" data-toggle="buttons-radio"><button class="btn changeview" data-template="tmpl-propertysearch-listview"> List</button>
<button class="btn changeview active" data-template="tmpl-propertysearch-galleryview"> Photo</button>
<button class="btn changeview" style="display: none;"> Map</button></div>
</div>
</div>
</div>
</header>
<div id="results"></div>
<script type="text/javascript">// <![CDATA[
	$(document).ready(function () {		
		if($('.phone-filters').css('display') == 'block')
		{
			$('#qs2, #filter').removeClass('module shadow');	
			$('#qs2').appendTo('#filters');
			$('#filter').appendTo('#filters');	  
		}

		doSearch('tmpl-propertysearch-listview');
		function doSearch(templatename) {
			var searchoptions = { "seo": true, "pagesize": 10 };
			searchoptions = $.extend({}, searchoptions, BAPI.session().searchparams);
			BAPI.UI.createSummaryWidget('#results', 
				{
					"entity": BAPI.entities.property,
					"template": BAPI.templates.get(templatename),
					"searchoptions": searchoptions
				}, 
				function() { 
					$('.description').jTruncate({ length: 150, moreText: BAPI.textdata.more, lessText: BAPI.textdata.less }); 
				}
			);
		}

		$(".changeview").on("click", function () {
			$('.changeview').removeClass('active');
			$(this).addClass('active');
			var templatename = $(this).attr('data-template');
			doSearch(templatename);
		});

		var timer = setInterval(function() {
			if ($(".showmore").length > 0) {
				start_init();
			}
			if ($(".nomore").length > 0) {
				start_init();
				clearInterval(timer);
			}
		}, 200);
	});

	function start_init()
	{	 
		$('.flexslider').flexslider({ animation: "slide", slideshow: false });
	}
// ]]></script>