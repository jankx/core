<?php
interface Foxy_Option_Interface {
	public function get_option( $option_name, $default_value = false );

	public function update_option( $option_name, $option_value );
}
