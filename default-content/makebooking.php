<div id="bookingform"></div>
<script type="text/javascript">    
    $(document).ready(function () {                        
        var options = {
            "mastertemplate": BAPI.templates.get('tmpl-booking-makebooking-masterlayout'),
            "targetids": {                
                "stayinfo": "#stayinfo",
                "statement": "#statement",
                "renter": "#renter",
                "creditcard": "#creditcard"
            },
            "templates": {                
                "stayinfo": BAPI.templates.get('tmpl-booking-makebooking-stayinfo'),
                "statement": BAPI.templates.get('tmpl-booking-makebooking-statement'),
                "renter": BAPI.templates.get('tmpl-booking-makebooking-renter'),
                "creditcard": BAPI.templates.get('tmpl-booking-makebooking-creditcard')
            }
        }
        BAPI.UI.createMakeBookingWidget('#bookingform', options);
    });    
</script>