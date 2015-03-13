<?php

class Kigo_Setups {

	// SSL Section
	public static function ssl_config() {
		$ssl_config = new Kigo_Ssl_Config();
		if(
			isset( $_POST[ 'submit' ] ) &&
			isset( $_POST[ 'ssl_select' ] )
		) {
			if( $ssl_config->save_custom( $_POST[ 'ssl_select' ], $_POST[ 'ssl_custom_script' ] ) ) {
				echo '<div id="message" class="updated"><p><strong>Settings saved.</strong></p></div>';
			}
			else {
				echo '<div id="message" class="error"><p><strong>Settings not saved.</strong></p></div>';
			}
		}
		
		self::ssl_config_html( $ssl_config->get_ssl_options_to_json() );
	}
	
	private static function ssl_config_html( $options ) {
		?>
		<script type="text/javascript">
			/*TODO: move the following code into admin.js (created in mantis 4203 task) */
			jQuery(document).ready(function($){
				var sslProviders = <?php echo $options; ?>;
		
				// Change image on the fly when script is being updated
				$('#ssl-input').bind('input', function() {
					$('#ssl-preview').attr( 'src', 'data:text/html;charset=utf-8,' + $(this).val() );
				});
		
				// Trigger change on select
				$('#ssl-select').change( function() {
					change_selected_trigger( $(this).children( 'option:selected' ) );
				});
		
				// Fill the select
				$.each(
					sslProviders,
					function ( key ) {
					var option  = $('<option />').attr( 'value', key ).text( this.label );
					$('#ssl-select').append( option );
					if( this.selected ) {
						change_selected_trigger( option );
					}
				});
		
		
				function change_selected_trigger( option ) {
					option.attr( 'selected', 'selected' );
					$('#ssl-input').prop('disabled', ( 'godaddy' === option.val() ) );
					$('#ssl-input').val( sslProviders[ option.val() ]['content'] ).trigger( 'input' );
				}
			});
		</script>
		
		<div class="wrap">
			<?php echo self::get_setup_header(); ?>
			<h2>SSL Configuration</h2>
			
			<form method="post">
				<table class="widefat" style="width:auto">
				<tr>
					<td><label for="ssl_select">SSL Support: </label></td>
					<td><select name="ssl_select" id="ssl-select" style="width:30em"></select></td>
				</tr>
				<tr>
					<td colspan="2"><textarea name="ssl_custom_script" id="ssl-input" rows="7" cols="70"></textarea></td>
				</tr>
				<tr>
					<td>Preview: </td>
					<td><iframe id="ssl-preview"></iframe></td>
				</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	// General setup page helpers
	private function get_setup_header() {
		if( is_newapp_website() ) {
			return '<h1><img src="' . get_kigo_plugin_url( '/img/logo_kigo.png' ) . '"/></h1>';
		}
		else{
			return '<h1><a href="http://www.bookt.com" target="_blank"><img src="' . get_kigo_plugin_url( '/img/logo-im.png' ) . '" /></a></h1>';
		}
	}
}
