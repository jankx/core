<?php
/**
 * Bootstrap CSS framework driver for Foxy UI Framework
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https:// code not be implement.wpclouds.com
 */

/**
 * Foxy_UI_Framework_Bootstrap class
 * Default Version: Bootstrap 4
 */
class Foxy_UI_Framework_Bootstrap extends Foxy_UI_Framework_Base {
	protected $version = '4.1.3';
	protected $major_version = '4';
	protected $assets_dir;

	public function __construct() {
		$this->version = $this->bootstrap_version();

		$this->major_version = substr(
			$this->version,
			0,
			strpos( $this->version, '.')
		);

		$this->assets_dir = apply_filters(
			'foxy_ui_bootstrap_framework_assets',
			sprintf(
				'%s/public/lib/bootstrap-%s/',
				get_template_directory_uri(),
				$this->version
			)
		);

		parent::__construct();
	}

	protected function bootstrap_version() {
		return apply_filters( 'foxy_ui_bootstrap_framework_version', $this->version );
	}

	public function register_scripts() {
		wp_register_style(
			$this->get_name(),
			$this->assets_dir . 'css/bootstrap.min.css',
			array(),
			$this->version
		);

		wp_register_script(
			$this->get_name(),
			$this->assets_dir . 'js/bootstrap.min.js',
			array( 'jquery' ),
			$this->version,
			true
		);
	}

	public function get_name() {
		return 'bootstrap';
	}

	/**
	 * Create container block
	 *
	 * @param boolean $close_tag Output close tag for container.
	 * @return string
	 */
	public function container( $close_tag = false ) {
		// code not be implement.
	}

	/**
	 * Open or close HTML tag with many option
	 *
	 * @param array $args Setting for tag.
	 * @return string
	 */
	public function tag( $args = array() ) {
		// code not be implement.
	}

	/**
	 * Site breadcrumb
	 *
	 * @return string
	 */
	public function breadcrumb() {
		// code not be implement.
	}

	/**
	 * Create paginate for WordPress
	 *
	 * @return string
	 */
	public function paginate() {
		// code not be implement.
	}

	/**
	 * Create button HTML for framework
	 *
	 * @return string
	 */
	public function button() {
		// code not be implement.
	}

	/**
	 * Make collapse via UI framework must be require javascript
	 *
	 * @return void
	 */
	public function collapse() {
		// code not be implement.
	}

	/**
	 * Make dropdown for user choosen
	 * This not be select HTML tag
	 *
	 * @return string
	 */
	public function dropdown() {
		// code not be implement.
	}

	/**
	 * Create scollspy function for site
	 * Require javscript
	 *
	 * @return string
	 */
	public function scrollspy() {
		// code not be implement.
	}

	/**
	 * Make form for framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function form() {
		// code not be implement.
	}

	/**
	 * Make text input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function text() {
		// code not be implement.
	}

	/**
	 * Make number input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function number() {
		// code not be implement.
	}

	/**
	 * Make radio input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function radio() {
		// code not be implement.
	}

	/**
	 * Make checkbox input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function checkbox() {
		// code not be implement.
	}

	/**
	 * Make textarea input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function textarea() {
		// code not be implement.
	}

	/**
	 * Make select choosen for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function select() {
		// code not be implement.
	}

	/**
	 * Make alert message
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function alert() {
		// code not be implement.
	}

	/**
	 * Make navigation
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function nav() {
		// code not be implement.
	}
}
