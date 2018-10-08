<?php
/**
 * Foxy framework main class
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen
 * @license GPL-3
 */

/**
 * Foxy class
 */
class Foxy {
	use Foxy_Option, Foxy_Plugin, Foxy_Addon, Foxy_Meta, Foxy_Request, Foxy_Layout, Foxy_Template, Foxy_UI;

	protected static $instance;

	protected $initialized = false;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
