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


/**
 * Check action & filter hooks is empty callback
 *
 * @param string $hook_name Hook name need to check is empty.
 * @return bool
 */
function foxy_check_empty_hook( $hook_name ) {
	global $wp_filter;

	/**
	 * If object doesn't exists this mean hook is empty
	 */
	if ( empty( $wp_filter[ $hook_name ] ) ) {
		return true;
	}

	return ! isset( $wp_filter[ $hook_name ]->callbacks ) && count( $wp_filter[ $hook_name ]->callbacks ) > 0;
}


function foxy_get_theme_name() {
	return basename( FOXY_ACTIVE_THEME_DIR );
}

function foxy_get_template_name() {
	return basename( FOXY_TEMPLATE_DIR );
}

/**
 * Create slug for post type, taxonomy or others
 *
 * @param string $source Source need to make slug.
 * @return string
 */
function foxy_make_slug( $source ) {
	return preg_replace(
		'/_/',
		'-',
		sanitize_title( $source )
	);
}
