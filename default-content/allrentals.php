<div id="results"></div>
<script type="text/javascript">
	$(document).ready(function () {		
		BAPI.UI.createSummaryWidget('#results', {
			"entity": BAPI.entities.property,
			"template": BAPI.templates.get('tmpl-allproperties')
		});
	});	
</script>
