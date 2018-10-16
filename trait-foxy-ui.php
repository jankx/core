<?php
/**
 * Foxy UI
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://wpclouds.com
 */

/**
 * Foxy_UI trait
 */
trait Foxy_UI {
	/**
	 * UI Framework use in Foxy framework
	 * Current supportes:
	 *  - Bootstrap
	 *  - Gris
	 *
	 * @var UI_Framework
	 */
	protected $ui_framework;

	/**
	 * Set UI Framework for Foxy Framework
	 *
	 * @param Foxy_UI_Framework_Base $framework Foxy UI framework.
	 * @throws \Exception Throw exception if $framework is not be instance of Foxy_UI_Framework_Base.
	 * @return void
	 */
	public function set_ui_framework( $framework ) {
		if ( ! ( $framework instanceof Foxy_UI_Framework_Base ) ) {
			throw new \Exception(
				sprintf( 'UI Framework must be instance of %s class', 'Foxy_UI_Framework_Base' ),
				333
			);
		}
		$this->ui_framework = $framework;
	}

	/**
	 * Foxy logo render HTML
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
		$logo_classes = apply_filters( 'foxy_logo_class_name', 'site-logo' );

		// phpcs:ignore
		printf( $template, $wrap_tag, $logo_classes, home_url(), get_bloginfo( 'name' ) );
	}

	/**
	 * Foxy UI show menu
	 *
	 * @param string $location Menu location need to render.
	 * @param array  $args Menu args use to render menu.
	 * @return void
	 */
	public static function menu( $location, $args = array() ) {
		$args = apply_filters( 'foxy_default_ui_menu_args', array(
			'theme_location'   => $location,
			'show_logo'        => false,
			'alternative_logo' => false,
		) );
		$args = apply_filters(
			"foxy_ui_menu_{$location}_args",
			$args
		);
		do_action( 'foxy_before_render_menu', $args, $location );
		do_action( "foxy_before_render_{$location}_menu", $args, $location );
		wp_nav_menu( $args );
		do_action( "foxy_after_render_{$location}_menu", $args, $location );
		do_action( 'foxy_after_render_menu', $args, $location );
	}

	/**
	 * Get main menu location
	 *
	 * @return string
	 */
	public static function get_main_menu() {
		return apply_filters( 'foxy_default_main_menu_location', 'primary' );
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
		do_action( 'foxy_before_footer_widget_loop' );
		for ( $index = 1; $index <= $num_footer_widgets; $index++ ) {
			$sidebar_id = 'footer-' . $index;
			do_action( 'foxy_before_footer_widget' );
			do_action( "foxy_before_footer_widget_{$sidebar_id}" );
				dynamic_sidebar( $sidebar_id );
			do_action( "foxy_after_footer_widget_{$sidebar_id}" );
			do_action( 'foxy_after_footer_widget' );
		}
		do_action( 'foxy_after_footer_widget_loop' );
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
}
