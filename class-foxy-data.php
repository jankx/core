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
	/**
	 * Foxy_Data instance
	 *
	 * @var Foxy_Data
	 */
	protected static $instance;

	/**
	 * Get Foxy_Data instance
	 *
	 * @return Foxy_Data
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Foxy_Data constructor
	 */
	public function __construct() {
		$this->register_posts_types();
		$this->register_taxonomies();

		/**
		 * Setup meta framework for Foxy Core.
		 */
		$meta_framework = apply_filters( 'foxy_default_meta_framework', 'WordPress' );

		$meta_framework_class = apply_filters(
			'foxy_meta_framework_class',
			sprintf( 'Foxy_Meta_Framework_' . ucfirst( $meta_framework ) )
		);

		Foxy::instance()->set_meta_framework(
			new $meta_framework_class()
		);

		/**
		 * Adding Foxy meta data into WordPress
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_post_metas' ) );
		// $this->add_terms_metas();
		// $this->add_user_metas();
	}

	/**
	 * Register foxy post types from addons or integrated plugins
	 *
	 * @return void
	 */
	public function register_posts_types() {
		$post_types = apply_filters( 'foxy_post_types', array() );
		if ( ! empty( $post_types ) && is_array( $post_types ) ) {
			foreach ( $post_types as $post_type => $args ) {
				register_post_type( $post_type, $args );

				// Free up memory.
				unset( $post_types[ $post_type ] );
			}
		}
		// Free up memory.
		unset( $post_type, $args, $post_types );
	}

	/**
	 * Register all foxy taxonomies for post types
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		$taxonomies = apply_filters( 'foxy_taxonomies', array() );
		if ( ! empty( $taxonomies ) && is_array( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy => $args ) {
				if ( empty( $args['post_type'] ) ) {
					continue;
				}
				if ( empty( $args['args'] ) ) {
					$args['args'] = array();
				}
				$args['args'] = wp_parse_args(
					$args['args'], array(
						'hierarchical'          => true,
						'show_ui'               => true,
						'show_admin_column'     => true,
						'update_count_callback' => '_update_post_term_count',
						'query_var'             => true,
					)
				);
				register_taxonomy(
					$taxonomy,
					$args['post_type'],
					$args['args']
				);

				// Free up memory.
				unset( $taxonomies[ $taxonomy ] );
			}
		}
		// Free up memory.
		unset( $taxonomy, $args, $taxonomies );
	}

	/**
	 * Add foxy metaboxes
	 *
	 * @return void
	 */
	public function add_post_metas() {
		$current_screen = get_current_screen();
		$meta_boxes = apply_filters( 'foxy_post_metas', array() );
		if ( ! empty( $meta_boxes ) && is_array( $meta_boxes ) ) {
			foreach ( $meta_boxes as $id => $args ) {
				if ( ! in_array( $current_screen->id, (array) $args['post_type'] ) ) {
					unset( $meta_boxes[ $id ] );
					continue;
				}
				$args = wp_parse_args( $args, array(
					'title' => '',
					'icon' => '',
					'context' => 'normal',
					'priority' => 'default',
					'fields' => array()
				));
				$args = apply_filters( "foxy_post_meta_{$id}_args", $args );

				add_meta_box(
					$id,
					Foxy::meta()->meta_title( $args ),
					array( Foxy::meta(), 'factory' ),
					$args['post_type'],
					$args['context'],
					$args['priority'],
					$args['fields']
				);
			}
		}
	}

	/**
	 * Add foxy term metas
	 *
	 * @return void
	 */
	public function add_terms_metas() {
		$term_metas = apply_filters( 'foxy_term_metas', array() );
		if ( ! empty( $term_metas ) && is_array( $term_metas ) ) {

		}
	}

	/**
	 * Add foxy user metas
	 *
	 * @return void
	 */
	public function add_user_metas() {
		$user_metas = apply_filters( 'foxy_user_metas', array() );
		if ( ! empty( $user_metas ) && is_array( $user_metas ) ) {

		}
	}
}
