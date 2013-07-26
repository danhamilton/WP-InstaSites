<div id="rental-policy-content">
</div>
<script>
$(document).ready(function () {
	BAPI.search(BAPI.entities.doctemplate, { "docname": "Rental Policy" }, function (data) {
		BAPI.get(data.result[0], BAPI.entities.doctemplate,null, function (data) {			
			$('#rental-policy-content').html(data.result[0].EntityLinks.Items[0].Obj.TextData);
		});
	});
});
</script>