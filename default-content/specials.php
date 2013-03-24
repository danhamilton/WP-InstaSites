<div id="results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('#results',
            {
                "entity": BAPI.entities.specials,
                "template": BAPI.templates.get('tmpl-specials-summary')
            });
    });    
</script>