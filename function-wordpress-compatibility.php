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

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin huh?' );
}

if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
	/**
	 * Dump variable only useable in development
	 *
	 * @param mixed $var Variable need to dump.
	 * @param bool  $print_r Use print_r function alternate var_dump function.
	 * @return void
	 */
	function dd() {
		$args   = func_get_args();
		$method = array_pop( $args );
		if ( ! in_array( $method, array( 'print_r', 'var_dump' ), true ) ) {
			$args[] = $method;
			$method = 'var_dump';
		}
		echo '<pre>';
		call_user_func_array( $method, $args );
		echo '</pre>';
		exit();
	}
}


add_filter( 'kses_allowed_protocols', 'foxy_allowed_protocols' );
function foxy_allowed_protocols( $protocols ) {
	// Add skype protocol to WordPress allowed protocols.
	$protocols = array_merge( $protocols, array( 'skype' ) );

	return $protocols;
}

if ( ! function_exists( 'array_column' ) ) {
	function array_column( $input, $column_key, $index_key = null ) {
		$arr = array_map(
			function( $d ) use ( $column_key, $index_key ) {
				if ( ! isset( $d[ $column_key ] ) ) {
					return null;
				}
				if ( null !== $index_key ) {
					return array( $d[ $index_key ] => $d[ $column_key ] );
				}
					return $d[ $column_key ];
			}, $input
		);

		if ( null !== $index_key ) {
			$tmp = array();
			foreach ( $arr as $ar ) {
				$tmp[ key( $ar ) ] = current( $ar );
			}
			$arr = $tmp;
		}
		return $arr;
	}
}

function foxy_get_terms( $args ) {
	return get_terms( $args );
}
