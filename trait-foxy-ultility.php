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
		return str_replace( '_', '-', sanitize_title( $source ) );
	}

	public static function get_object_id( $object_or_id, $class_name ) {
		if ( is_numeric( $object_or_id ) ) {
			return $object_or_id;
		} elseif( is_null( $object_or_id ) ) {
			return self::get_current_object_id( $class_name );
		} else {
			if (
				in_array(
					$class_name,
					array( 'WP_User', 'WP_Post', 'WP_Term' ),
					true
				)
				&& $object_or_id instanceof $class_name
			) {
				return $object_or_id->ID;
			}
		}
		return 0;
	}

	private static function get_current_object_id( $class_name ) {
		$current_id = 0;
		switch ( $class_name ) {
			case 'WP_Post':
				$current_id = get_the_ID();
			break;
			case 'WP_User';
				$current_id = get_current_user_id();
			break;
		}
		return apply_filters( 'foxy_get_current_object_id', $current_id, $class_name );
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
