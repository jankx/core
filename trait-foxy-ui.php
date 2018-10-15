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
	 * @return void
	 */
	public static function logo() {
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
			'theme_location' => $location,
		) );
		wp_nav_menu(
			apply_filters( "foxy_ui_menu_{$location}_args", $args )
		);
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

	public static function has_footer_widget() {
		self::$footer_widget_num > 0;
	}

	public static function footer_widgets() {
		$footer_widget_num = self::get_footer_num();
		var_dump($footer_widget_num);die;
		do_action( 'foxy_before_footer_widget_loop' );
		for ( $index = 1; $index <= $footer_widget_num; $index++ ) {
			$sidebar_id = 'footer-' . $index;
			do_action( 'foxy_before_footer_widget' );
			dynamic_sidebar( $sidebar_id );
			do_action( 'foxy_after_footer_widget' );
		}
		do_action( 'foxy_after_footer_widget_loop' );
	}

	public static function paginate() {

	}

	public static function breadcrumb() {}
}
