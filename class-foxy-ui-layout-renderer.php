<?php
/**
 * Foxy layout renderer
 * This file will render site layout and content
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI_Layout_Renderer class
 */
class Foxy_UI_Layout_Renderer {
	/**
	 * Main render instance
	 *
	 * @var Foxy_UI_Layout_Renderer
	 */
	public static $instance;

	/**
	 * Render main instance
	 *
	 * @return Foxy_UI_Layout_Renderer
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * This method is main function use to render layout for Foxy Framework
	 *
	 * @return void
	 */
	public static function render() {
		/**
		 * Get layout renderer instance
		 */
		$layout = self::instance();

		/**
		 * Start render layout
		 */
		$layout->render_header();
		$layout->render_content();
		$layout->render_footer();

		add_filter( 'body_class', array( $layout, 'body_classes' ) );
	}

	/**
	 * Render foxy header layout
	 *
	 * @return void
	 */
	public function render_header() {

	}

	public function body_classes( $classes ) {
		$classes[] = sprintf( 'layout-%s', Foxy::get_layout() );
		return $classes;
	}

	/**
	 * Render site content layout
	 *
	 * @return void
	 */
	public function render_content() {
		add_action( 'foxy_index_content', 'foxy_index_content' );
		add_action( 'foxy_error_404_content', 'foxy_error_404_content' );
		add_action( 'foxy_archive_content', 'foxy_archive_content' );
		add_action( 'foxy_search_content', 'foxy_search_content' );
		add_action( 'foxy_page_content', 'foxy_page_content' );
		add_action( 'foxy_single_content', 'foxy_single_content' );
	}

	/**
	 * Render footer layout
	 *
	 * @return void
	 */
	public function render_footer() {

	}
}
