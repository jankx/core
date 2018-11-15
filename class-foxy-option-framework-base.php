<?php
/**
 * This file define all base method of option framework can be use in child class
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com
 * @license GPLv3
 * @link https://wpclouds.com
 */

/**
 * Foxy_Option_Framework_Base class
 */
abstract class Foxy_Option_Framework_Base implements Foxy_Option_Framework_Interface {
	/**
	 * Set current option key for add new fields
	 *
	 * @var string
	 */
	protected $id;

	protected $loaded_options;

	/**
	 * Foxy_Option_Framework_Base constructor
	 */
	public function __construct() {
		$this->id = apply_filters( 'foxy_default_option_key_name', foxy_get_theme_name() );
		$this->load_options( $this->id );
	}

	public function id( $id = null ) {
		if ( is_null( $id ) ) {
			return $this->id;
		}
		$this->id = $id;
		return $this;
	}

	public function admin_page() {
		$option_files = $this->search_option_files();
		foreach ( $option_files as $option_file ) {
			$fields = require $option_file;
			if ( ! is_array( $fields ) ) {
				continue;
			}
			$this->add_sections( $fields );
		}
	}

	private function search_option_files() {
		$search_directories = apply_filters(
			'foxy_options_directories',
			array(
				sprintf( '%soptions', FOXY_TEMPLATE_DIR ),
				sprintf( '%sincludes/options', FOXY_TEMPLATE_DIR ),
				sprintf( '%soptions', FOXY_ACTIVE_THEME_DIR ),
				sprintf( '%sincludes/options', FOXY_ACTIVE_THEME_DIR ),
			)
		);
		$option_files       = array();
		foreach ( (array) $search_directories as $directory ) {
			if ( ! file_exists( $directory ) ) {
				continue;
			}
			$searched_files = glob( $directory . '/{*.php,*/*.php}', GLOB_BRACE );
			$option_files   = array_merge_recursive( $option_files, $searched_files );
		}
		return $option_files;
	}


}
