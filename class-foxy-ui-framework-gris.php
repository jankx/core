<?php
/**
 * Gris CSS framework driver for Foxy UI Framework
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI_Framework_Gris class
 */
class Foxy_UI_Framework_Gris extends Foxy_UI_Framework_Base {
	/**
	 * Gris CSS framework version
	 *
	 * @var string
	 */
	protected $version = '1.0.0';
	/**
	 * Gris UI Framework name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'gris';
	}

	/**
	 * Get bootstrap version
	 * Other plugin can change version via hook `foxy_ui_bootstrap_framework_version`
	 *
	 * @return string
	 */
	protected function gris_version() {
		return apply_filters( 'foxy_ui_bootstrap_framework_version', $this->version );
	}

	/**
	 * Register bootstrap assets for framework
	 *
	 * @return void
	 */
	public function register_scripts() {

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
