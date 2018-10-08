<?php

trait Foxy_Addon {
	protected static $addon_headers = array(
		'Name'        => 'Addon Name',
		'PluginURI'   => 'Addon URI',
		'Version'     => 'Version',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
	);
	protected static $addons = array();
	protected static $addon_cached_flag = false;

	public static function get_addon_headers() {
		return self::$addon_headers;
	}

	public static function get_addons( $load_cached = true ) {
		if ( ! self::$addon_cached_flag || ( self::$addon_cached_flag && ! $load_cached ) ) {
			$searched_addons = glob( FOXY_ACTIVE_THEME_DIR . 'addons/{*,*/*}.php', GLOB_BRACE);
			$addons = array();
			foreach ( $searched_addons as $searched_addon ) {
				$data = get_file_data( $searched_addon, self::get_addon_headers() );
				if ( empty( $data['Name'] ) ) {
					continue;
				}
				$addons[ $searched_addon ] = $data;
			}
			self::$addons = $addons;
		}
		return self::$addons;
	}

	public static function get_active_addons() {
		return array_keys( self::get_addons() );
	}
}
