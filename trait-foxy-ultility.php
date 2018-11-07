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
}
