<?php
/**
 * Foxy UI Framework interface
 * This file define all function must be implements in UI framework class
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI_Framework_Interface interface
 */
interface Foxy_UI_Framework_Interface {
	/**
	 * This method use to get UI framework name
	 *
	 * @return string
	 */
	public function get_name();

	/**
	 * Create container block
	 *
	 * @param boolean $close_tag Output close tag for container.
	 * @return string
	 */
	public function container( $close_tag = false );

	/**
	 * Open or close HTML tag with many option
	 *
	 * @param array $args Setting for tag.
	 * @return string
	 */
	public function tag( $args = array() );

	/**
	 * Site breadcrumb
	 *
	 * @return string
	 */
	public function breadcrumb();

	/**
	 * Create paginate for WordPress
	 *
	 * @return string
	 */
	public function paginate();

	/**
	 * Create button HTML for framework
	 *
	 * @return string
	 */
	public function button();

	/**
	 * Make collapse via UI framework must be require javascript
	 *
	 * @return void
	 */
	public function collapse();

	/**
	 * Make dropdown for user choosen
	 * This not be select HTML tag
	 *
	 * @return string
	 */
	public function dropdown();

	/**
	 * Create scollspy function for site
	 * Require javscript
	 *
	 * @return string
	 */
	public function scrollspy();

	/**
	 * Make form for framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function form();

	/**
	 * Make text input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function text();

	/**
	 * Make number input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function number();

	/**
	 * Make radio input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function radio();

	/**
	 * Make checkbox input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function checkbox();

	/**
	 * Make textarea input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function textarea();

	/**
	 * Make select choosen for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function select();

	/**
	 * Make alert message
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function alert();

	/**
	 * Make navigation
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function nav();
}
