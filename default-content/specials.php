<div class="specials-results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('.specials-results',
            {
                "entity": BAPI.entities.specials,
                "template": BAPI.templates.get('tmpl-specials-summary')
            });
    });    
</script>