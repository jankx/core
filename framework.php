<?php
/**
 * Foxy Framework Core
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL
 * @link https://wpclouds.com
 */

/**
 * Check Foxy framework don't have registered
 */
if ( ! class_exists( 'Foxy' ) && ! defined( 'FOXY_FRAMEWORK_FILE' ) ) {
	define( 'FOXY_FRAMEWORK_FILE', __FILE__ );

	require_once dirname( FOXY_FRAMEWORK_FILE ) . '/class-foxy-setup.php';

	Foxy_Setup::initialize();
}

if ( ! function_exists( 'foxy' ) ) {
	/**
	 * Helper get Foxy instance from $GLOBALS variable
	 *
	 * @return Foxy
	 */
	function foxy() {
		return $GLOBALS['foxy'];
	}
}
