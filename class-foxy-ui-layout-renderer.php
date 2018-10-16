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
	}

	/**
	 * Render foxy header layout
	 *
	 * @return void
	 */
	public function render_header() {

	}

	/**
	 * Render site content layout
	 *
	 * @return void
	 */
	public function render_content() {

	}

	/**
	 * Render footer layout
	 *
	 * @return void
	 */
	public function render_footer() {

	}
}
