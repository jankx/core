<?php
/**
 * Foxy layout renderer
 * This file will render site layout and content
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI_Layout_Renderer class
 */
class Foxy_UI_Layout_Renderer {
	/**
	 * Main render instance
	 *
	 * @var Foxy_UI_Layout_Renderer
	 */
	public static $instance;

	/**
	 * Number of sidebar
	 *
	 * @var int
	 */
	protected $num_sidebar;

	public function __construct() {
		$this->num_sidebar = Foxy::get_layout() % 3;

		$this->render_header();
		$this->render_sidebar();
		$this->render_content();
		$this->render_footer();

		add_filter( 'body_class', array( $this, 'body_classes' ) );
	}

	/**
	 * This method is main function use to render layout for Foxy Framework
	 *
	 * @return void
	 */
	public static function render() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Render foxy header layout
	 *
	 * @return void
	 */
	public function render_header() {

	}

	public function body_classes( $classes ) {
		$classes[] = sprintf( 'layout-%s', Foxy::get_layout() );
		return $classes;
	}

	/**
	 * Render site content layout
	 *
	 * @return void
	 */
	public function render_content() {
		add_action( 'foxy_before_main_content', array( $this, 'content_wrap_open' ), 3 );
		add_action( 'foxy_after_main_content', array( $this, 'content_wrap_close' ), 33 );

		add_action( 'foxy_index_content', 'foxy_index_content' );
		add_action( 'foxy_error_404_content', 'foxy_error_404_content' );
		add_action( 'foxy_archive_content', 'foxy_archive_content' );
		add_action( 'foxy_search_content', 'foxy_search_content' );
		add_action( 'foxy_page_content', 'foxy_page_content' );
		add_action( 'foxy_single_content', 'foxy_single_content' );
	}

	public function content_wrap_open() {
		Foxy::ui()->container();
	}

	public function content_wrap_close() {
		Foxy::ui()->container( true );
	}

	/**
	 * Render footer layout
	 *
	 * @return void
	 */
	public function render_footer() {

	}

	public function render_sidebar() {
		$sidebar = Foxy::get_layout();
		if ( $sidebar % 3 > 0 ) {
			add_action( 'foxy_after_main_content', array( $this, 'get_sidebar' ), 3 );

			add_action( 'foxy_before_sidebar_content', array( $this, 'sidebar_wrap_open' ) );
			add_action( 'foxy_after_sidebar_content', array( $this, 'sidebar_wrap_close' ) );
		}

		if ( $sidebar % 3 > 1 ) {
			add_action( 'foxy_after_main_content', array( $this, 'get_second_sidebar' ), 11 );

			add_action( 'foxy_before_second_sidebar_content', array( $this, 'second_sidebar_wrap_open' ) );
			add_action( 'foxy_after_sidebar_content', array( $this, 'second_sidebar_wrap_close' ) );
		}
	}

	public function get_sidebar() {
		do_action( 'foxy_before_get_sidebar' );
		get_sidebar();
		do_action( 'foxy_after_get_sidebar' );
	}

	public function get_second_sidebar() {
		do_action( 'foxy_before_get_sidebar' );
		get_sidebar( 'alt' );
		do_action( 'foxy_after_get_sidebar' );
	}

	public function sidebar_wrap_open() {
		$args = array(
			'name' => 'aside',
			'id'   => 'primary-sidebar',
		);
		if ( $this->num_sidebar < 2 ) {
		} else {
		}
		Foxy::ui()->tag( $args );
	}

	public function sidebar_wrap_close() {
		Foxy::ui()->tag( array(
			'name'  => 'aside',
			'close' => true,
		) );
	}

	public function second_sidebar_wrap_open() {
		$args = array(
			'name' => 'aside',
			'id'   => 'second-sidebar',
		);
		if ( $this->num_sidebar >= 2 ) {
		} else {
		}
		Foxy::ui()->tag( $args );
	}

	public function second_sidebar_wrap_close() {
		Foxy::ui()->tag( array(
			'name'  => 'aside',
			'close' => true,
		) );
	}
}
