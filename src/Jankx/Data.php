<?php
/**
 * This file will interact with WordPress core to create WordPress data
 * such as: post, page, taxonomy, post meta, user meta
 *
 * @package Jankx/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://puleeno.com
 */

/**
 * Jankx Data class
 */
class Jankx_Data {
	/**
	 * Jankx_Data instance
	 *
	 * @var Jankx_Data
	 */
	protected static $instance;

	protected $post_meta_framework;

	/**
	 * Get Jankx_Data instance
	 *
	 * @return Jankx_Data
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Jankx_Data constructor
	 */
	public function __construct() {
		$this->register_posts_types();
		$this->register_taxonomies();
		$this->register_post_metas();

		// $this->add_terms_metas();
		// $this->add_user_metas();
	}

	/**
	 * Register jankx post types from addons or integrated plugins
	 *
	 * @return void
	 */
	public function register_posts_types() {
		$post_types = apply_filters( 'jankx_post_types', array() );
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
	 * Register all jankx taxonomies for post types
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		$taxonomies = apply_filters( 'jankx_taxonomies', array() );
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
						'show_in_rest'          => true,
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

	public function register_post_metas() {
		$post_meta = new Jankx_Meta();

		/**
		 * Setup meta framework for Jankx Core.
		 */
		$meta_framework = apply_filters( 'jankx_default_meta_framework', 'jankx' );

		$meta_framework_class = apply_filters(
			'jankx_meta_framework_class',
			sprintf( 'Jankx_Meta_Framework_' . ucfirst( $meta_framework ) )
		);
		if ( ! class_exists( $meta_framework_class ) ) {
			return;
		}

		Jankx::instance()->set_meta_framework(
			new $meta_framework_class( $post_meta )
		);


		/**
		 * Adding Jankx meta data into WordPress
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_post_metas' ) );
	}

	/**
	 * Add jankx metaboxes
	 *
	 * @return void
	 */
	public function add_post_metas() {
		$current_screen = get_current_screen();
		$meta_boxes     = apply_filters( 'jankx_post_metas', array() );
		if ( ! empty( $meta_boxes ) && is_array( $meta_boxes ) ) {
			foreach ( $meta_boxes as $id => $args ) {
				if ( ! in_array( $current_screen->id, (array) $args['post_type'], true ) ) {
					unset( $meta_boxes[ $id ] );
					continue;
				}
				$args = wp_parse_args(
					$args, array(
						'title'    => '',
						'icon'     => '',
						'context'  => 'normal',
						'priority' => 'default',
						'fields'   => array(),
					)
				);
				$args = apply_filters( "jankx_post_meta_{$id}_args", $args );

				add_meta_box(
					$id,
					Jankx::meta()->meta_title( $args ),
					array( Jankx::meta(), 'metabox_callback' ),
					$args['post_type'],
					$args['context'],
					$args['priority'],
					$args['fields']
				);
			}
		}
	}

	/**
	 * Add jankx term metas
	 *
	 * @return void
	 */
	public function add_terms_metas() {
		$term_metas = apply_filters( 'jankx_term_metas', array() );
		if ( ! empty( $term_metas ) && is_array( $term_metas ) ) {

		}
	}

	/**
	 * Add jankx user metas
	 *
	 * @return void
	 */
	public function add_user_metas() {
		$user_metas = apply_filters( 'jankx_user_metas', array() );
		if ( ! empty( $user_metas ) && is_array( $user_metas ) ) {

		}
	}
}
