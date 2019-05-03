<?php
/**
 * Jankx UI
 *
 * @package Jankx/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://puleeno.com
 */

namespace Jankx\Core\Traits;
/**
 * UI trait
 */
trait UI {
	/**
	 * UI Framework use in Jankx framework
	 * Current supportes:
	 *  - Bootstrap
	 *  - Gris
	 *
	 * @var UI_Framework
	 */
	protected $ui_framework;

	/**
	 * Set UI Framework for Jankx Framework
	 *
	 * @param UI_Framework_Base $framework Jankx UI framework.
	 * @throws \Exception Throw exception if $framework is not be instance of UI_Framework_Base.
	 * @return void
	 */
	public function set_ui_framework( $framework ) {
		if ( ! ( $framework instanceof UI_Framework_Base ) ) {
			throw new \Exception(
				sprintf( 'UI Framework must be instance of %s class', 'UI_Framework_Base' ),
				333
			);
		}
		$this->ui_framework = $framework;

		/**
		 * Create UI Closure for Jankx
		 */
		$this->ui = function() {
			return $this->ui_framework;
		};
	}

	public static function get_ui_framework() {
		return apply_filters( 'default_ui_framework', 'bootstrap' );
	}

	/**
	 * Jankx logo render HTML
	 *
	 * @param boolean $alternate_logo Load second logo from theme settings.
	 * @return void
	 */
	public static function logo( $alternate_logo = false ) {
		$wrap_tag = 2;
		if ( is_home() ) {
			$wrap_tag = 1;
		}
		$template     = '<h%1$d id="site-brand-logo" class="%2$s">
			<a href="%3$s" title="%4$s">%4$s</a>
		</h%1$d>';
		$logo_classes = apply_filters( 'logo_class_name', 'site-logo' );

		// phpcs:ignore
		printf( $template, $wrap_tag, $logo_classes, home_url(), get_bloginfo( 'name' ) );
	}

	/**
	 * Jankx UI show menu
	 *
	 * @param string $location Menu location need to render.
	 * @param array  $original_args Menu args use to render menu.
	 * @return void
	 */
	public static function menu( $location, $original_args = array() ) {
		/**
		 * Parse menu args with Jankx menu args default value
		 */
		$args = wp_parse_args(
			$original_args,
			apply_filters(
				'default_ui_menu_args', array(
					'theme_location'   => $location,
					'show_logo'        => false,
					'alternative_logo' => false,
					'ui_framework'     => true,
				)
			),
			$location
		);

		/**
		 * Filter menu args by theme location
		 */
		$args = apply_filters( "ui_menu_{$location}_args", $args, $location );

		/**
		 * Reset args to don't use UI Framework
		 */
		if ( ! $args['ui_framework'] ) {
			self::reset_menu_args( $args, $original_args );
		}

		/**
		 * Rendering menu template
		 */
		do_action( 'before_render_menu', $args, $location );
		do_action( "before_render_{$location}_menu", $args, $location );
		wp_nav_menu( $args );
		if ( array_get( $args, 'search_form', false ) ) {
			Jankx::template( 'searchform.php' );
		}
		do_action( "after_render_{$location}_menu", $args, $location );
		do_action( 'after_render_menu', $args, $location );
	}

	/**
	 * Reset menu args when $args['ui_framework'] is set value is `false`
	 *
	 * @param array $args          Current menu args.
	 * @param array $original_args Original menu args are setted by you.
	 * @return void
	 */
	protected static function reset_menu_args( &$args, $original_args ) {
		$menu_reset_args = apply_filters(
			'reset_menu_args', array(
				'container_class' => 'navigation raw-menu',
				'container_id'    => 'jankx-menu-' . $args['theme_location'],
				'fallback_cb'     => '',
				'walker'          => '',
			)
		);

		foreach ( $menu_reset_args as $reset_key => $reset_value ) {
			if ( isset( $original_args[ $reset_key ] ) ) {
				$args[ $reset_key ] = $original_args[ $reset_key ];
			} else {
				$args[ $reset_key ] = $reset_value;
			}
		}
	}

	/**
	 * Get main menu location
	 *
	 * @return string
	 */
	public static function get_main_menu() {
		return apply_filters( 'default_main_menu_location', 'primary' );
	}

	/**
	 * Check menu has been registered
	 *
	 * @param string $location Menu theme location need to check has registered.
	 * @return boolean
	 */
	public static function has_menu( $location ) {
		return has_nav_menu( $location );
	}

	/**
	 * Render footer widgets
	 *
	 * @return void
	 */
	public static function footer_widgets() {
		$num_footer_widgets = self::get_num_footer_widgets();
		do_action( 'before_footer_widget_loop', $num_footer_widgets );
		for ( $index = 1; $index <= $num_footer_widgets; $index++ ) {
			$sidebar_id = 'footer-' . $index;
			do_action( 'before_footer_widget', $sidebar_id, $num_footer_widgets );
				dynamic_sidebar( $sidebar_id );
			do_action( 'after_footer_widget', $sidebar_id, $num_footer_widgets );
		}
		do_action( 'after_footer_widget_loop', $num_footer_widgets );
	}

	/**
	 * Check the post has show title
	 *
	 * @param int $post_id Post ID want to check the post has show title.
	 * @return boolean
	 */
	public static function has_title( $post_id = null ) {
		return 'yes' !== Jankx::get_meta( 'hide_post_title', $post_id, true );
	}

	private static function carousel_responsive_options( $args, $instance ) {
		$responsive = apply_filters(
			'widget_default_carousel_responsive',
			array(
				0    => (object) array(
					'items' => 1,
				),
				767  => (object) array(
					'items' => 2,
				),
				992  => (object) array(
					'items' => 3,
				),
				1200 => (object) array(
					'items' => 4,
				),
			)
		);
		return apply_filters( "widget_{$args['widget_id']}_carousel_responsive", $responsive );
	}

	public static function carousel_options( $use_carousel, $args, $instance ) {
		if ( $use_carousel ) {
			$carousel_options = apply_filters(
				'default_carousel_options',
				array(
					'loop'   => true,
					'nav'    => true,
					'margin' => 20,
				)
			);
			if ( in_array( $args['id'], array( 'primary', 'second', 'home-sidebar', 'realty-single', 'realty-archive' ), true ) ) {
				$carousel_options['items'] = 1;
			} else {
				$carousel_options['responsive'] = self::carousel_responsive_options( $args, $instance );
			}
			Jankx::asset()->lib( 'carousel' )->script(
				sprintf(
					'$(\'#%s .owl-carousel\').owlCarousel(%s);',
					$args['widget_id'],
					json_encode( (object) $carousel_options )
				)
			);
		}
	}

	/**
	 * Show WordPress pagination integrate with UI CSS Framework
	 *
	 * @return void
	 */
	public static function paginate() {

	}

	/**
	 * Show site breadcrumb integrate with UI CSS Framework
	 * Support SEO optimizaion, SEO plugins.
	 *
	 * @return void
	 */
	public static function breadcrumb() {}

	/**
	 * Render post meta at frontend
	 *
	 * @return void
	 */
	public static function post_meta() {

	}

	public static function thumbnail() {

	}

	public static function loading() {
		Jankx::ui()->wrap( 'loading-wrap' );
		Jankx::ui()->tag( array( 'class' => 'loading' ) );
		do_action( 'ui_loading' );
		Jankx::ui()->tag( array( 'close' => true ) );
		Jankx::ui()->close_wrap();
	}
}
