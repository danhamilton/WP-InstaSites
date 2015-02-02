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
});
