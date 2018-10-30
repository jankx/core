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
}
