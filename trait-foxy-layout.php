<?php
/**
 * Foxy Layout features
 * This file define all layout methods
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */
trait Foxy_Layout {
	/**
	 * Theme or page layout
	 *
	 * @var string
	 */
	protected static $layout = '';

	/**
	 * Number of footer widgets
	 *
	 * @var integer
	 */
	protected static $num_footer_widgets = 3;

	/**
	 * Use second sidebar flag
	 *
	 * @var boolean
	 */
	protected static $use_second_sidebar = true;

	/**
	 * Set number of footer wigets use in theme
	 *
	 * @param int $num Number of footer widgets.
	 * @return void
	 */
	public static function set_num_footer_widgets( $num ) {
		self::$num_footer_widgets = (int) $num;
	}

	/**
	 * Get number of footer widgets
	 *
	 * @return int
	 */
	public static function get_num_footer_widgets() {
		return (int) self::$num_footer_widgets;
	}

	/**
	 * Check theme has footer widgets
	 *
	 * @return boolean
	 */
	public static function has_footer_widget() {
		return self::$num_footer_widgets > 0;
	}

	/**
	 * Enable or disable second sidebar in theme
	 *
	 * @param boolean $use Status want to set to second sidebar.
	 * @return void
	 */
	public static function use_second_sidebar( $use = true ) {
		self::$use_second_sidebar = (bool) $use;
	}

	/**
	 * Get status of second sidebar
	 *
	 * @return bool
	 */
	public static function get_second_sidebar() {
		return (bool) self::$use_second_sidebar;
	}

	/**
	 * Set theme or page layout
	 *
	 * @return void
	 */
	public function set_layout() {
	}


	/**
	 * Get layout of current page
	 *
	 * @return string
	 */
	public function get_layout() {
		return Foxy_Common::LAYOUT_CONTENT_SIDEBAR;
	}
}
