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
	 * UI Framework version
	 *
	 * @var string
	 */
	protected $version;

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

	/**
	 * Open or close HTML tag with many option
	 *
	 * @param array $args Setting for tag.
	 * @return string
	 */
	public function tag( $args = array(), $attributes = null ) {
		$context = '';
		$args = wp_parse_args(
			$args, array(
				'name'            => 'div',
				'id'              => '',
				'class'           => '',
				'responsive'      => true,
				'mobile_columns'  => '',
				'tablet_columns'  => '',
				'desktop_columns' => '',
				'xtra_columns'    => '',
				'close'           => false,
				'echo'            => true,
			)
		);
		$class_names = $args['class'];
		$id = '';
		if ( ! empty( $args['id'] ) ) {
			$id = sprintf( ' id="%s"', esc_attr( $args['id'] ) );
			$context = 'tag_' . $args['id'];
			$args = apply_filters( "foxy_ui_tag_{$context}", $args, $context );
			$attributes = apply_filters( "foxy_ui_tag_{$context}_attr", $attributes, $args, $context );
			$class_names = apply_filters( "foxy_ui_{$context}_class_name", $class_names );
		}
		if ( ! empty( $args['close'] ) ) {
			$tag = sprintf( '</%s>', $args['name'] );
		} else {
			$tag = sprintf(
				'<%1$s%2$s%3$s%4$s>',
				$args['name'],
				$id,
				$class_names ? ' ' . $class_names : '',
				$this->generate_attributes( $attributes )
			);
		}
		if ( empty( $args['echo'] ) ) {
			return $tag;
		}
		echo $tag; // WPCS: XSS ok.
	}

	public function generate_attributes( $attributes = null ) {
		return '';
	}
}
