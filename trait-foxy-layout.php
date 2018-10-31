<?php
/**
 * Foxy Layout features
 * This file define all layout methods
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */

/**
 * Foxy_Layout trait
 */
trait Foxy_Layout {
	/**
	 * Theme or page layout
	 *
	 * @var string
	 */
	protected static $layout = '';

	protected static $custom_404_error_template = true;

	/**
	 * Get number of footer widgets
	 *
	 * @return int
	 */
	public static function get_num_footer_widgets() {
		return (int) apply_filters(
			'foxy_num_footer_widgets',
			3
		);
	}

	/**
	 * Check theme has footer widgets
	 *
	 * @return boolean
	 */
	public static function has_footer_widget() {
		return self::get_num_footer_widgets() > 0;
	}

	/**
	 * Get status of second sidebar
	 *
	 * @return bool
	 */
	public static function has_second_sidebar() {
		return apply_filters(
			'foxy_has_second_sidebar',
			true
		);
	}

	/**
	 * Get default layout for theme
	 *
	 * @return string
	 */
	public static function get_default_layout() {
		return apply_filters(
			'foxy_default_layout',
			Foxy_Common::LAYOUT_SIDEBAR_CONTENT
		);
	}

	/**
	 * Set theme layout
	 *
	 * @param string $layout Layout will be apply to set layout for WordPress page, post, category,etc.
	 * @return void
	 */
	public function set_layout( $layout ) {
		$supported_layouts = array(
			Foxy_Common::LAYOUT_CONTENT_SIDEBAR,
			Foxy_Common::LAYOUT_SIDEBAR_CONTENT,
			Foxy_Common::LAYOUT_FULL_WIDTH,
		);

		if ( Foxy::has_second_sidebar() ) {
			$supported_layouts = array_merge($supported_layouts, array(
				Foxy_Common::LAYOUT_CONTENT_SIDEBAR_SIDEBAR,
				Foxy_Common::LAYOUT_SIDEBAR_CONTENT_SIDEBAR,
				Foxy_Common::LAYOUT_SIDEBAR_SIDEBAR_CONTENT,
			));
		}
		$supported_layouts = apply_filters(
			'foxy_supported_layouts',
			$supported_layouts
		);

		if ( in_array( $layout, $supported_layouts, true ) ) {
			self::$layout = $layout;
		} else {
			self::$layout = '';
		}
	}


	/**
	 * Get layout of current page
	 *
	 * @return string
	 */
	public static function get_layout() {
		$layout = self::$layout;
		if ( '' === $layout ) {
			$layout = self::get_default_layout();
		}
		return apply_filters( 'foxy_layout', $layout );
	}


	/**
	 * Check content has container to multi purpose
	 *
	 * @return bool
	 */
	public static function content_has_container() {
		return apply_filters(
			'foxy_content_container',
			true
		);
	}

	public static function post_layout( $args = array(), $posts = null ) {
		$method = empty( $posts ) ? 'default_loop_layout' : __FUNCTION__;
		return forward_static_call(
			array(
				Foxy_Post_Layout::class,
				$method,
			),
			$args, $posts
		);
	}

	public static function custom_404_error_template( $custom = null ) {
		if ( is_null( $custom ) ) {
			return self::$custom_404_error_template;
		}
		self::$custom_404_error_template = (bool) $custom;
	}
}
