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
	 * @param bool  $print_r Use print_r function alternate var_dump function.
	 * @return void
	 */
	function dd( $var, $print_r = false ) {
		$method = $print_r ? 'print_r' : 'var_dump';
		echo '<pre>';
			$method( $var );
		echo '</pre>';
		exit();
	}
}
