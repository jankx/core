<?php
/**
 * Foxy Template Helpers
 * This file define template methods use to load template files
 * from `templates` directory in theme folder
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_Template trait
 */
trait Foxy_Template {
	protected static $page_template = null;

	public static function set_page_template( $page_template ) {
		/**
		 * Only accept $template_name variable has value.
		 */
		if ( empty( $page_template ) ) {
			return;
		}
		self::$page_template = $page_template;
	}

	public static function get_page_template() {
		if ( is_null( self::$page_template ) ) {
			throw new Exception( 'Please use action hook after `template_direct` has been run' );
		}
		return self::$page_template;
	}
	/**
	 * Search template path
	 *
	 * @param array|string $template_names Template name(s) need to search location.
	 * @return string
	 */
	public static function search_template( $template_names ) {
		$located = '';
		foreach ( (array) $template_names as $template_name ) {
			if ( ! $template_name ) {
				continue;
			}
			if ( file_exists( FOXY_ACTIVE_THEME_DIR . 'templates/' . $template_name ) ) {
				$located = FOXY_ACTIVE_THEME_DIR . 'templates/' . $template_name;
				break;
			} elseif ( file_exists( FOXY_TEMPLATE_DIR . 'templates/' . $template_name ) ) {
				$located = FOXY_TEMPLATE_DIR . 'templates/' . $template_name;
				break;
			}
		}
		return $located;
	}

	/**
	 * Check template is exists
	 *
	 * @param array|string $template_names Template file(s) need to check is exists.
	 * @return bool
	 */
	public static function check_template( $template_names ) {
		return '' !== self::search_template( $template_names );
	}

	/**
	 * Load theme template
	 *
	 * @param array|string $template_names Template file name need to load.
	 * @param boolean      $require_once  Whether to require_once or require.
	 *
	 * @return void
	 */
	public static function template( $template_names, $require_once = false ) {
		$located = self::search_template( $template_names );
		if ( '' !== $located ) {
			load_template( $located, $require_once );
		}
	}
}
