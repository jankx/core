<?php
/**
 * Foxy meta framework interface
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://puleeno.com
 * @license GPL
 */

/**
 * Foxy_Meta_Framework_Interface interface
 */
interface Foxy_Meta_Framework_Interface {
	/**
	 * Meta data content factory
	 *
	 * @param WP_Post $post_type Default argument in action add_meta_box.
	 * @param array   $fields Foxy meta data setting fields.
	 * @return void
	 */
	public function factory( $post_type, $fields );
}
