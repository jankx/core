<?php
/**
 * This file define all method use to compatibility with all WordPress versions.
 *
 * @package Foxy/Core
 * @subpackage Helper
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL
 * @link https://wpclouds.com
 */

if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
	/**
	 * Dump variable only useable in development
	 *
	 * @param mixed $var Variable need to dump.
	 * @return void
	 */
	function dd( $var ) {
		echo '<pre>';
			// phpcs:ignore
			print_r( $var );
		echo '</pre>';
		exit();
	}
}
