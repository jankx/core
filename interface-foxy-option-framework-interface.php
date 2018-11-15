<?php
interface Foxy_Option_Framework_Interface {
	public function id( $id );

	public function load_options( $id, $refresh = false );

	/**
	 * Get option value by option name
	 *
	 * @param string  $option_name Option name.
	 * @param boolean $default_value Default value when option name don't have value.
	 * @return mixed
	 */
	public function get_option( $option_name, $default_value = false );

	public function add_sections( $sections );
}
