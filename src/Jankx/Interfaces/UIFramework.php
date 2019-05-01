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

namespace Jankx\Core;

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
	 * Create container block
	 *
	 * @param boolean $close_tag Output close tag for container.
	 * @return string
	 */
	public function container( $close_tag = false );
}
