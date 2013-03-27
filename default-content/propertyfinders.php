<div class="propertyfinders-results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('.propertyfinders-results',
            {
                "entity": BAPI.entities.searches,
                "template": BAPI.templates.get('tmpl-searches-summary')
            });
    });    
</script>