<?php
/**
 * Foxy ultilities
 *
 * @package Foxy/Core
 * @subpackage Helper
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL
 * @link https://wpclouds.com
 */

/**
 * Foxy_Ultility trait
 */
trait Foxy_Ultility {
	/**
	 * Create slug for post type, taxonomy or others
	 *
	 * @param string $source Source need to make slug.
	 * @return string
	 */
	public static function make_slug( $source ) {
		return sanitize_title( $source );
	}

	/**
	 * Check action & filter hooks is empty callback
	 *
	 * @param string $hook_name Hook name need to check is empty.
	 * @return bool
	 */
	public static function hook_is_empty( $hook_name ) {
		global $wp_filter;

		/**
		 * If object doesn't exists this mean hook is empty
		 */
		if ( empty( $wp_filter[ $hook_name ] ) ) {
			return true;
		}

		/**
		 * Search hook has callbacks.
		 */
		foreach ( $wp_filter[ $hook_name ] as $hook ) {
			if ( ! isset( $hook['callbacks'] ) || count( $hook['callbacks'] ) < 1 ) {
				continue;
			}
			return false;
		}

		return true;
	}
}
