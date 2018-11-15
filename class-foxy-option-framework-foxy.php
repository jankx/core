<?php
/**
 * Undocumented class
 *
 * @package Foxy/Fields
 */

/**
 * Foxy_Option_Framework_Foxy class
 */
class Foxy_Option_Framework_Foxy extends Foxy_Option_Framework_Base {
	protected $factory;

	public function __construct() {
		parent::__construct();
		$this->factory = new Foxy_Fields_Factory_Option();
	}

	public function load_options( $id, $refresh = flase ) {
	}

	public function get_option( $option_name, $default_value = false ) {
		return $default_value;
	}

	public function add_sections( $sections ) {
	}
}
