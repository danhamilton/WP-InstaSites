<div id="results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('#results',
            {
                "entity": BAPI.entities.searches,
                "template": BAPI.templates.get('tmpl-searches-summary')
            });
    });    
</script>