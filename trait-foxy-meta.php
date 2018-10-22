<?php
/**
 * WordPress has define meta to extends Post infos and User info
 * Foxy will use meta info in a different way
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_Meta trait
 */
trait Foxy_Meta {
	/**
	 * WordPress Meta Framework integrate with Foxy theme framework
	 *
	 * @var instaceof Foxy_Meta_Base
	 */
	protected $meta_framework;

	/**
	 * Get meta value via meta framework
	 * Every meta framework has different method to get meta value
	 * So Foxy framework must integrate with meta framework is used
	 *
	 * @param string $meta_key   Meta key need to get value.
	 * @param int    $id         Post or User ID need to get meta value.
	 * @param string $meta_type  Meta type may be is post or user.
	 *
	 * @return string
	 */
	public function get_meta( $meta_key, $id = null, $meta_type = 'post' ) {
		if ( is_null( $id ) ) {
			$id = get_the_ID();
		}
		return get_metadata( $meta_type, $id, $meta_key );
	}

	/**
	 * Get user meta from WordPress user meta
	 *
	 * @param string $meta_key  Meta key need to get meta value.
	 * @param int    $user_id   User ID need to get meta value.
	 *
	 * @return string
	 */
	public function user_meta( $meta_key, $user_id = null ) {
		if ( is_null( $user_id ) ) {
			$user    = wp_get_current_user();
			$user_id = $user->ID();
		}
		return get_metadata( 'user', $user_id, $meta_key );
	}
}
