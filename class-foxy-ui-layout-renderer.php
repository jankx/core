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
		$this->render_something();

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
		if ( is_404() ) {
			add_action( 'foxy_header', array( $this, 'disable_404_custom_template' ) );
		} else {
			add_action( 'foxy_header', array( $this, 'site_header' ) );
		}
	}

	public function disable_404_custom_template() {
		Foxy::custom_404_error_template( false );
	}

	public function site_header() {
		if ( Foxy::has_menu( 'top-menu' ) ) {
			Foxy::ui()->wrap( 'top-menu', array( 'has_container' => true ) );
			Foxy::menu( 'top-menu', array(
				'ui_framework' => false,
			) );
			do_action( 'foxy_after_top_menu' );
			Foxy::ui()->close_wrap( array( 'has_container' => true ) );
		}

		if ( Foxy::has_menu( 'primary' ) ) {
			Foxy::ui()->wrap( 'site-header' );
			Foxy::menu( 'primary', array(
				'depth' => 3,
			) );
			Foxy::ui()->close_wrap();
		}
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

		add_action( 'foxy_before_main_content', array( $this, 'main_content_open' ), 5 );
		add_action( 'foxy_after_main_content', array( $this, 'main_content_close' ), 3 );

		if ( is_home() ) {
			add_action( 'foxy_index_content', 'foxy_index_content' );
		}
		if ( is_404() ) {
			add_action( 'foxy_error_404_content', 'foxy_error_404_content' );
		}
		if ( is_archive() ) {
			add_action( 'foxy_archive_content', 'foxy_archive_content' );
		}
		if ( is_search() ) {
			add_action( 'foxy_search_content', 'foxy_search_content' );
		}
		if ( is_page() ) {
			add_action( 'foxy_page_content', 'foxy_page_content' );
		}
		if ( is_single() ) {
			add_action( 'foxy_single_content', 'foxy_single_content' );
		}
	}

	public function content_wrap_open() {
		$args = array(
			'id' => 'content-sidebar-wrap',
		);
		Foxy::ui()->tag( $args );
		Foxy::ui()->container();
		Foxy::ui()->tag(
			array(
				'id'    => 'main-content-sidebar-row',
				'class' => 'row',
			)
		);
	}

	public function content_wrap_close() {
		Foxy::ui()->tag(
			array(
				'id'    => 'main-content-sidebar-row',
				'close' => true,
			)
		);
		Foxy::ui()->container( true );
		Foxy::ui()->tag(
			array(
				'close' => true,
			)
		);
	}

	/**
	 * Render footer layout
	 *
	 * @return void
	 */
	public function render_footer() {
		add_action( 'foxy_footer', array( $this, 'footer_widgets' ), 5 );
		add_action( 'foxy_footer', array( $this, 'footer_menu' ) );
		add_action( 'foxy_footer', array( $this, 'footer_copyright' ), 15 );

		add_action( 'foxy_before_footer_widget_loop', array( $this, 'open_widget_row_tag' ) );
		add_action( 'foxy_after_footer_widget_loop', array( $this, 'close_widget_row_tag' ) );
		add_action( 'foxy_before_footer_widget', array( $this, 'open_widget_area' ), 10, 2 );
		add_action( 'foxy_after_footer_widget', array( $this, 'close_widget_area' ), 10, 2 );
	}

	public function footer_widgets() {
		if ( Foxy::has_footer_widget() ) {
			Foxy::ui()->wrap( 'footer-widgets', array( 'has_container' => true ) );
			Foxy::footer_widgets();
			Foxy::ui()->close_wrap( array( 'has_container' => true ) );
		}
	}

	public function footer_menu() {
		if ( Foxy::has_menu( 'bottom-menu' ) ) {
			Foxy::ui()->wrap( 'footer-menu', array( 'has_container' => true ) );
			Foxy::menu( 'bottom-menu', array(
				'ui_framework' => false
			) );
			do_action( 'foxy_after_footer_menu' );
			Foxu::ui()->close_wrap( array( 'has_container' => true ) );
		}
	}

	public function footer_copyright() {
		$template = Foxy::search_template(array(
			'footer-text.php',
			'footer/text.php',
		));
		if ( ! empty( $template ) ) {
			require $template;
		} else {
			Foxy::ui()->wrap( 'copyright-text', array( 'has_container' => true ) );
			printf( __( 'Copyright &copy; %1$d <a href="%2$s" title="%3$s">%3$s</a>. All rights reserved.', 'foxy' ), esc_html( date( 'Y' ) ), esc_attr( home_url() ), esc_html(get_bloginfo( 'name' ) ) );
			Foxy::ui()->close_wrap( array( 'has_container' => true ) );
		}
	}

	public function open_widget_area( $sidebar_id, $num_footer_widgets ) {
		$args = array( 'class' => 'widget-area' );
		if ( $num_footer_widgets % 2 === 0 ) {
			$args['tablet_columns'] = 6;
			if ( $num_footer_widgets > 3 ) {
				$args['desktop_columns'] = 3;
			}
		} else {
			$args['desktop_columns'] = 12 / $num_footer_widgets;
		}

		Foxy::ui()->tag( $args );
	}

	public function close_widget_area() {
		echo '</div>';
	}

	public function open_widget_row_tag() {
		Foxy::ui()->tag( array(
			'class' => 'row'
		) );
	}

	public function close_widget_row_tag() {
		echo '</div>';
	}

	public function render_sidebar() {
		$sidebar = Foxy::get_layout();
		if ( $sidebar % 3 > 0 ) {
			add_action( 'foxy_after_main_content', array( $this, 'get_sidebar' ), 5 );

			add_action( 'foxy_before_sidebar_content', array( $this, 'sidebar_wrap_open' ) );
			add_action( 'foxy_after_sidebar_content', array( $this, 'sidebar_wrap_close' ) );
		}

		if ( $sidebar % 3 > 1 ) {
			add_action( 'foxy_after_main_content', array( $this, 'get_second_sidebar' ), 11 );

			add_action( 'foxy_before_second_sidebar_content', array( $this, 'second_sidebar_wrap_open' ) );
			add_action( 'foxy_after_sidebar_content', array( $this, 'second_sidebar_wrap_close' ) );
		}
	}

	public function render_something() {
		add_filter( 'get_search_form', 'foxy_get_search_form' );
	}

	/**
	 * Get WordPress primary sidebar
	 *
	 * @return void
	 */
	public function get_sidebar() {
		do_action( 'foxy_before_get_sidebar' );
		get_sidebar();
		do_action( 'foxy_after_get_sidebar' );
	}

	/**
	 * Get WordPress second sidebar
	 *
	 * @return void
	 */
	public function get_second_sidebar() {
		do_action( 'foxy_before_get_sidebar' );
		get_sidebar( 'alt' );
		do_action( 'foxy_after_get_sidebar' );
	}

	/**
	 * Main content HTML tag open
	 *
	 * @return void
	 */
	public function main_content_open() {
		$args = array(
			'name' => 'main',
			'id'   => 'main-content',
		);

		if ( 1 === $this->num_sidebar ) {
			$args = wp_parse_args(
				$args, array(
					'mobile_columns' => 12,
					'tablet_columns' => 8,
				)
			);
			if ( Foxy::get_layout() == Foxy_Common::LAYOUT_SIDEBAR_CONTENT ) {
				$args['push'] = 4;
			}
		} elseif ( 0 === $this->num_sidebar ) {
			$args = wp_parse_args(
				$args, array(
					'mobile_columns' => 12,
				)
			);
		} else {
			$args = wp_parse_args(
				$args, array(
					'mobile_columns'  => 12,
					'tablet_columns'  => 12,
					'desktop_columns' => 6,
				)
			);
		}

		Foxy::ui()->tag( $args );
	}

	/**
	 * Main content HTML tag close
	 *
	 * @return void
	 */
	public function main_content_close() {
		$args = array(
			'name'  => 'main',
			'close' => true,
		);
		Foxy::ui()->tag( $args );
	}

	/**
	 * Sidebar HTML wrap tag open
	 *
	 * @return void
	 */
	public function sidebar_wrap_open() {
		$args = array(
			'name'  => 'aside',
			'id'    => 'primary-sidebar',
			'class' => 'sidebar',
		);

		if ( 1 === $this->num_sidebar ) {
			$args = wp_parse_args(
				$args, array(
					'mobile_columns' => 12,
					'tablet_columns' => 4,
				)
			);
			if ( Foxy::get_layout() == Foxy_Common::LAYOUT_SIDEBAR_CONTENT ) {
				$args['pull'] = 8;
			}
		} else {
			$args = wp_parse_args(
				$args, array(
					'mobile_columns'  => 12,
					'tablet_columns'  => 6,
					'desktop_columns' => 3,
				)
			);
		}

		Foxy::ui()->tag( $args );
	}

	/**
	 * Sidebar HTML wrap tag open
	 *
	 * @return void
	 */
	public function sidebar_wrap_close() {
		Foxy::ui()->tag(
			array(
				'name'  => 'aside',
				'close' => true,
			)
		);
	}

	/**
	 * Second sidebar HTML wrap open
	 *
	 * @return void
	 */
	public function second_sidebar_wrap_open() {
		$args = array(
			'name'            => 'aside',
			'id'              => 'second-sidebar',
			'class'           => 'sidebar',
			'mobile_columns'  => 12,
			'tablet_columns'  => 6,
			'desktop_columns' => 3,
		);

		Foxy::ui()->tag( $args );
	}

	/**
	 * Second sidebar HTML wrap tag open
	 *
	 * @return void
	 */
	public function second_sidebar_wrap_close() {
		Foxy::ui()->tag(
			array(
				'name'  => 'aside',
				'close' => true,
			)
		);
	}
}
