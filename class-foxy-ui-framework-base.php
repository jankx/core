<?php
/**
 * Abstract class for UI framework use to extends
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI_Framework_Base class
 */
abstract class Foxy_UI_Framework_Base implements Foxy_UI_Framework_Interface {
	/**
	 * Foxy_UI_Framework_Base constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 33 );
	}

	/**
	 * This method will register assets automatic by UI framework name
	 * Other plugin or theme function can integrate with foxy ui framework via filter & action hook.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! apply_filters( 'foxy_ui_framework_enqueue_scripts', false, $this->get_name(), $this->version ) ) {
			wp_enqueue_style( $this->get_name() );
			wp_enqueue_script( $this->get_name() );
		}
		do_action( 'foxy_ui_framework_enqueue_scripts' );
	}
}
