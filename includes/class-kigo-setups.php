<?php

class Kigo_Setups {

	const ADMIN_VIEW_PATH	= 'admin-view';
	const INCLUDE_PATH		= 'includes';


	// Translations managment Section
	public static function translation_gui() {
		require_once( get_kigo_plugin_path( self::INCLUDE_PATH . DIRECTORY_SEPARATOR . 'class-kigo-translations-list-table.php' ) );
		
		$my_table = new Kigo_Translations_List_Table();
		if( !$my_table->prepare_items() ) {
			wp_die( 'Sorry, we were unable to retrieve the translations' );
		}
		
		include( get_kigo_plugin_path( self::ADMIN_VIEW_PATH . DIRECTORY_SEPARATOR . 'setup-translations.php' ) );
	}

}
