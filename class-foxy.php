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
	use Foxy_Option, Foxy_Request, Foxy_Layout, Foxy_Template, Foxy_UI;

	protected static $instance;

	protected $initialized = false;
	protected $ui_framework;
	protected $option_framework;
	protected $metabox_framework;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct(
		Foxy_GUI_Framework_Interface $ui_framework,
		Foxy_Option_Interface $option_framework,
		Foxy_Metabox_Interface $metabox_framework
	) {
		$this->ui_framework = $ui_framework;
		$this->option_framework = $option_framework;
		$this->metabox_framework = $metabox_framework;
	}
}
