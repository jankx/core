<?php
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

	public static function menu( $location, $args = array() ) {
		$args['theme_location'] = $location;
		wp_nav_menu( $args );
	}

	/**
	 * Check menu has
	 *
	 * @param [type] $location
	 * @return void
	 */
	public static function has_menu( $location ) {
		return has_nav_menu( $location );
	}

	public static function has_footer_widget() {
		return self::$use_footer_widget && self::$footer_widget_num > 0;
	}

	public static function footer_widgets() {
		$footer_widget_num = self::get_footer_num();
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
