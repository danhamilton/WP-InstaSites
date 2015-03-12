(function($){
	
	/* Action for the restore default page content button on edit page */
	$( '#restore-default-content-button' ).click( function() {
		var button = $(this);
		button.prop( 'disabled', true );
		$( '#restore-default-content-spiner' ).show();
		
		var post_name;
		if(
			'string' !== $.type( post_name = $( '#restore-default-content-button' ).attr( 'data-post-name' ) ) ||
			!post_name.length
		) {
			alert( "Sorry we couldn't restore the default content of this page.\n Please try again.\nError code: admin_1" );
		}
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,/* This variable is populated by wordpress http://codex.wordpress.org/AJAX_in_Plugins */
			data: {
				'action': 'restore_default_content',
				'post_name' : post_name
			},
			success: function( response ) {
				if(
					!$.isPlainObject( response ) ||
					'boolean' !== $.type( response.success ) ||
					'string' !== $.type( response.error_code )
				) {
					alert( "Sorry we couldn't restore the default content of this page.\n Please try again.\nError code: admin_2" );
				}
				else if( !response.success ) {
					alert( "Sorry we couldn't restore the default content of this page.\n Please try again.\nError code: " + response.error_code );
				}
				else{
					/* Add/Replace the message parameter to the value 4 => this display the "Page updated." message */
					var new_location = location.href;
					if( -1 !== location.href.indexOf( 'message=' ) ) {
						new_location = location.href.replace( /message=\d+/,'message=4')
					}
					else
					{
						new_location += '&message=4';
					}
					location.href = new_location;
				}
				
				button.prop( 'disabled', false );
				$( '#restore-default-content-spiner' ).hide();
			}
		}).fail( function() {
			alert( "Sorry we couldn't restore the default content of this page.\n Please try again.\nError code: admin_3" );
			button.prop( 'disabled', false );
			$( '#restore-default-content-spiner').hide();
		});
	});
})(jQuery);
