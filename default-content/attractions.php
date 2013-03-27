<div class="poi-results"></div>
<script type="text/javascript">
    $(document).ready(function () {
        BAPI.UI.createSummaryWidget('.poi-results',
        {
            "entity": BAPI.entities.poi,
            "template": BAPI.templates.get('tmpl-attractions-summary-list')
        });
    });    
</script>