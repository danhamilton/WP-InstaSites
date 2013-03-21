<div id="results"></div>

<script type="text/javascript">
$(document).ready(function() {
  	var template = $('#tpl-allproperties').html();	
	BAPI.UI.createSummaryWidget('#results',
		{
			"entity": BAPI.entities.property,
			"template": BAPI.templates.get('tmpl-allproperties'),
		});
  
});
</script>