/* JavaScript file loaded only on admin pages */

$(function() {

	/* setup-sync page */
	if( $( '#force_full_sync' ).length ) {
		var timeout = null;
		function get_last_cron_execution() {
			$.ajax({
					dataType: "json",
					url: '/wp-admin/admin-ajax.php?action=kigo_get_last_cron_execution'
				})
				.always(function( data ) {
					if(
						!$.isPlainObject( data ) ||
						!data.success ||
						'boolean' !== $.type( data.too_much )
					) {
						$( '#last-exec' ).html( 'n/a' );
					}
					else if( data.too_much ) {
						$( '#last-exec' ).html( '<span class="red">> 1h</span>' );
					}
					else {
						$( '#last-exec' ).html( data.formated );
					}
	
					if(data.success) {
						timeout = setTimeout(function(){ get_last_cron_execution(); }, 60000);
					}
				});
		}
	
		get_last_cron_execution();
	
		$( '#force_full_sync' ).click(
			function()
			{
				$(this).button().button("option", "disabled", true) ;
				var prev_text = $(this).text();
				$(this).text( 'Loading...' );
				$('#force_full_sync_spinner').show();
	
				$.ajax({
						url:  '/wp-admin/admin-ajax.php?action=kigo_site_cron&force_full_sync=1', // the cron runs synchronously
						timeout: 10 * 60 * 1000 // 10 minutes expressed in milliseconds
					})
					.always(function() {
						$( '#force_full_sync' ).text( prev_text );
						$('#force_full_sync_spinner').hide();
						$( '#force_full_sync' ).button("option", "disabled", false);
						if( timeout !== null )
							clearTimeout(timeout);
						get_last_cron_execution();
					});
			}
		);
	}



	/* Translation page */
	if( $( '#translations' ).length ) {

		/* Disable every button until a change is triggered */
		$( 'button.i18n-cancel, a.i18n-save' ).button().button( 'option', 'disabled', true );
		
		/* Cancel buttons listener */
		var cancel_button_enbaled = [];
		$( 'button.i18n-cancel' ).click(
			function() {
				var corresponding_input = null;
				if(
					'string' !== $.type( $(this).attr( 'data-key') ) ||
					'object' !== $.type( corresponding_input = $( "input.overwritten-translation[data-key='" + $(this).attr( 'data-key' ) + "']" ) ) ||
					'string' !== $.type( corresponding_input.attr( 'data-value' ) )
				) {
					// FIXME: Once we use loggly in JS please log this! (https://www.loggly.com/docs/javascript/)
					alert( "Unexpected error occured while retrieving cancel attributes.\nPlease contact support." );
					return false;
				}
				corresponding_input.val( corresponding_input.attr( 'data-value' ) );
				disable_cancel_button( $(this) )
			}
		);
		
		/* Input change listener */
		$( 'input.overwritten-translation' ).bind(
			'input change',
			function() {
				var corresponding_cancel = null;
				if(
					'string' !== $.type( $(this).attr( 'data-key' ) ) ||
					'string' !== $.type( $(this).attr( 'data-value' ) ) ||
					'string' !== $.type( $(this).val() ) ||
					'object' !== $.type( corresponding_cancel = $( "button.i18n-cancel[data-key='" + $(this).attr( 'data-key' ) + "']" ) )
				) {
					// FIXME: Once we use loggly in JS please log this! (https://www.loggly.com/docs/javascript/)
					alert( "Unexpected error occured while retrieving input attributes.\nPlease contact support." );
					return false;
				}
				
				if( $(this).attr( 'data-value' ) !== $(this).val() ) {
					
					// Enable save and the corresponding cancel button
					$( 'a.i18n-save' ).button().button( 'option', 'disabled', false );
					corresponding_cancel.button().button( 'option', 'disabled', false );
					
					// Keep track of the cancel button that are enabled 
					var pos = null;
					if( -1 === ( pos = cancel_button_enbaled.indexOf( $(this).attr( 'data-key' ) ) ) ) {
						cancel_button_enbaled.push( $(this).attr( 'data-key' ) );
					}
					
					// Message preventing to quite without saving
					$( window ).bind( 'beforeunload', function() {
						return 'You have unsaved changes, are you sure you want to leave?';
					});
				}
				else {
					disable_cancel_button( corresponding_cancel );
				}
			}
		);
		
		/* Save listener */
		$( 'a.i18n-save' ).click(
			function()
			{
				var lang_code = null;
				var translations = null;
				if(
					'string' !== $.type( ( lang_code = $(this).attr( 'data-lang' ) ) ) ||
					'object' !== $.type( translations = get_modified_translations() )
				) {
					// FIXME: Once we use loggly in JS please log this! (https://www.loggly.com/docs/javascript/)
					alert( "Unexpected error occured, unable to retrieve language code.\nPlease contact support." );
					return false;
				}
				
				// Disable all editing/saving
				$( 'button.i18n-cancel, a.i18n-save' ).button().button( 'option', 'disabled', true );
				$( 'input.overwritten-translation' ).attr( 'readonly', true );
				$( '#save-translations-spinner' ).show();
				
				$.ajax({
						method:	'POST',
						url:	'/wp-admin/admin-ajax.php?action=kigo_save_translations',
						data:	{
							'lang_code'	: lang_code,
							'data'		: JSON.stringify( translations )
						}
					})
					.always(function( data ) {
						if(
							'object' !== $.type( data ) ||
							'boolean' !== $.type( data.success ) ||
							'string' !== $.type( data.msg ) ||
							'object' !== $.type( data.data )
						) {
							// FIXME: Once we use loggly in JS please log this! (https://www.loggly.com/docs/javascript/)
							alert( "Unexpected error occured while saving the translations.\nPlease contact support." );
						}
						else if( !data.success ) {
							// If success false is returned, PHP has already logged into Loggly the error
							alert( "We couldn't save your translation.\nPlease contact support with the following message:\n" . data.msg );
						}
						else {
							// Let's update the data-value attribute with the value saved (returned by the server)
							$( 'input.overwritten-translation').each(
								function() {
									if( 'string' === $.type( data.data[ $(this).attr( 'data-key') ] ) ) {
										$(this).attr( 'data-value', data.data[ $(this).attr( 'data-key') ] );
									}
									else {
										$(this).attr( 'data-value', '' );
										$(this).val( '' );
									}
								}
							);
							alert( "Your translations have been correctly saved!" );
						}
						
						$( window ).unbind( 'beforeunload' );
						$( 'input.overwritten-translation' ).attr( 'readonly', false );
						$( '#save-translations-spinner' ).hide();
					});
			}
		);
		
		// HELPERS
		function disable_cancel_button( cancel_button ) {
			cancel_button.button().button( 'option', 'disabled', true );
			if( -1 !== ( pos = cancel_button_enbaled.indexOf( $(cancel_button).attr( 'data-key' ) ) ) ) {
				cancel_button_enbaled.splice( pos, 1 );
			}
			if( !cancel_button_enbaled.length ) {
				$( 'a.i18n-save' ).button().button( 'option', 'disabled', true );
				$( window ).unbind( 'beforeunload' );
			}
		}
		
		function get_modified_translations() {
			var translations = {};
			$( 'input.overwritten-translation' ).each(
				function() {
					if(
						'string' !== $.type( $(this).attr( 'data-value' ) ) ||
						'string' !== $.type( $(this).attr( 'data-key' ) ) ||
						'string' !== $.type( $(this).val() )
					) {
						translations = null;
						return false;
					}
					
					if( $(this).attr( 'data-value' ) !== $(this).val() ) {
						translations[ $(this).attr( 'data-key') ] = $(this).val();
					}
				}
			);
			return translations;
		}
		
		function unsaved_modification() {
			$( window ).bind( 'beforeunload', function() {
				return 'You have unsaved changes!';
			});
		}
	}
});
