<?php
/**
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL
 * @link https://wpclouds.com
 */

function foxy_get_domain_name( $host ) {
	/**
	 * Last dot in host name
	 */
	$last_dot = strrpos( $host, '.' );
	if ( false === $last_dot ) {
		return false;
	}

	/**
	 * The dot separates the subdomain and the domain name
	 */
	$offset = strlen( $host ) - $last_dot + 1;
	$subdomain_dot = strrpos( $host, '.', -$offset);

	if ( false === $subdomain_dot ) {
		$domain_name = substr( $host, 0, $last_dot );
	} else {
		$subdomain_dot++;
		$domain_name = substr( $host, $subdomain_dot, $last_dot - $subdomain_dot );
	}
	return $domain_name;
}
