<?php
/**
 * This file define all base method of option framework can be use in child class
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com
 * @license GPLv3
 * @link https://wpclouds.com
 */

/**
 * Foxy_Option_Framework_Base class
 */
abstract class Foxy_Option_Framework_Base implements Foxy_Option_Framework_Interface {
	/**
	 * Set current option key for add new fields
	 *
	 * @var string
	 */
	protected $id;

	protected $loaded_options;

	/**
	 * Foxy_Option_Framework_Base constructor
	 */
	public function __construct() {
		$this->id = apply_filters( 'foxy_default_option_key_name', foxy_get_theme_name() );
	}

	public function id( $id ) {
		$this->id = $id;
		return $this;
	}
}
