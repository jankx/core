<?php
/**
 * Foxy UI Framework interface
 * This file define all function must be implements in UI framework class
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI_Framework_Interface interface
 */
interface Foxy_UI_Framework_Interface {
	/**
	 * This method use to get UI framework name
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Method register_scripts must be defined in extended class
	 * This method use to register all scripts and styles use for theme
	 *
	 * @return void
	 */
	public function register_scripts();

	/**
	 * Create container block
	 *
	 * @param boolean $close_tag Output close tag for container.
	 * @return string
	 */
	public function container( $close_tag = false );
}
