<?php
/**
 * Jankx meta framework interface
 *
 * @package Jankx/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://puleeno.com
 * @license @license GPL
 */

namespace Jankx\Core;

/**
 * MetaFrameworkInterface interface
 */
interface MetaFrameworkInterface {
	public function get( $meta_key, $post_id = null, $is_single = true );

	public function set( $post_id, $meta_key, $value );

	/**
	 * Meta data content factory
	 *
	 * @param WP_Post $post_type Default argument in action add_meta_box.
	 * @param array   $fields Jankx meta data setting fields.
	 * @return void
	 */
	public function metabox_callback( $post_type, $fields );
}
