<?php
/**
 * Jankx Framework Core
 *
 * @package Jankx/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license @license GPL
 * @link https://puleeno.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin huh?' );
}

/**
 * Check Jankx framework don't have registered
 */
if ( ! class_exists( 'Jankx' ) && ! defined( 'JANKX_FRAMEWORK_FILE' ) ) {
	define( 'JANKX_FRAMEWORK_FILE', __FILE__ );

	require_once dirname( JANKX_FRAMEWORK_FILE ) . '/src/class-jankx-setup.php';
	Jankx_Setup::initialize();
}

if ( ! function_exists( 'jankx' ) ) {
	/**
	 * Helper get Jankx instance from $GLOBALS variable
	 *
	 * @return Jankx
	 */
	function jankx() {
		return $GLOBALS['jankx'];
	}
}
