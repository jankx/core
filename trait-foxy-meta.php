<?php

trait Foxy_Meta {
	protected $meta_framework;

x
	public function meta($meta_key, $id = null, $meta_type = 'post') {
		return get_metadata($meta_type, $id, $meta_key);
	}

	public function user_meta($meta_key, $user_id = null) {
		if ( is_null( $user_id ) ) {
			$user = wp_get_current_user();
			$user_id = $user->ID();
		}
		return get_metadata('user', $user_id, $meta_key);
	}
}
