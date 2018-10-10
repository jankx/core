<?php
/**
 * This file will interact with WordPress core to create WordPress data
 * such as: post, page, taxonomy, post meta, user meta
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy Data class
 */
class Foxy_Data {
	protected static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->register_posts_types();
		$this->register_taxonomies();
		$this->add_post_metas();
		$this->add_user_metas();
	}

	public function register_posts_types() {
		$post_types = apply_filters( 'foxy_post_types', array() );
		if ( ! empty( $post_types ) && is_array( $post_types ) ) {
			foreach ( $post_types as $post_type => $args ) {
				register_post_type( $post_type, $args );
			}
		}
	}

	public function register_taxonomies() {
		$taxonomies = apply_filters( 'foxy_taxonomies', array() );
		if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {

		}
	}

	public function add_post_metas() {
		$meta_boxes = apply_filters( 'foxy_meta_boxes', array() );
		if ( ! empty( $meta_boxes ) && is_array( $meta_boxes ) ) {

		}
	}

	public function add_user_metas() {
		$user_metas = apply_filters( 'foxy_user_metas', array() );
		if ( ! empty( $user_metas ) && is_array( $user_metas ) ) {

		}
	}
}
