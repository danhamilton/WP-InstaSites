<div id="results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('#results',
        {
            "entity": BAPI.entities.poi,
            "template": BAPI.templates.get('tmpl-attractions-summary-list')
        });
    });    
</script>