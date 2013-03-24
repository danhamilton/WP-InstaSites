<div id="results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('#results',
            {
                "entity": BAPI.entities.development,
                "template": BAPI.templates.get('tmpl-developments-summary-list')
            });
    });    
</script>