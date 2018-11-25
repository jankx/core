 <?php
/**
 * Bootstrap CSS framework driver for Foxy UI Framework
 *
 * @package Foxy/Core
 * @subpackage UI
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https:// code not be implement.wpclouds.com
 */

/**
 * Foxy_UI_Framey./;'[work_Bootstrap class
 * Default Version: Bootstrap 4
 */
class Foxy_UI_Framework_Bootstrap extends Foxy_UI_Framework_Base {
	/**
	 * Bootstrap major version
	 *
	 * @var int
	 */
	protected $major_version = 4;

	/**
	 * Bootsttrap UI Framework constructor
	 */
	public function __construct() {
		$this->major_version = $this->bootstrap_version();
		$this->{'init_class_name_bootstrap_' . $this->major_version}();
		Foxy::asset()->lib( 'bootstrap' );

		add_filter( 'foxy_default_ui_menu_args', array( $this, 'menu_args' ) );
		add_action( 'foxy_before_render_menu', array( $this, 'bootstrap_open_navigation_tag' ), 5, 2 );
		add_action( 'foxy_after_render_menu', array( $this, 'bootstrap_close_navigation_tag' ), 15 );
		add_filter( 'foxy_logo_class_name', array( $this, 'bootstrap_navbrand_class' ) );
		add_filter( 'foxy_sidebar_default_footer_args', array( $this, 'add_class_to_footer_widgets' ) );
	}

	/**
	 * Bootstrap UI Framework name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bootstrap';
	}

	/**
	 * Get bootstrap version
	 * Other plugin can change version via hook `foxy_ui_bootstrap_framework_version`
	 *
	 * @return string
	 */
	protected function bootstrap_version() {
		$bootstrap_version = apply_filters( 'foxy_asset_bootstrap_version', '4.1.3' );
		$version_major     = explode( '.', $bootstrap_version );
		return array_shift( $version_major );
	}

	public function init_class_name_bootstrap_3() {
		$this->mobile_class_prefix       = 'col-xs';
		$this->small_tablet_class_prefix = 'col-sm-';
		$this->tablet_class_prefix       = 'col-md-';
		$this->desktop_class_prefix      = 'col-lg-';
		$this->extra_class_prefix        = 'col-sx-';
	}

	public function init_class_name_bootstrap_4() {
		$this->mobile_class_prefix       = 'col-';
		$this->small_tablet_class_prefix = 'col-sm-';
		$this->tablet_class_prefix       = 'col-md-';
		$this->desktop_class_prefix      = 'col-lg-';
		$this->extra_class_prefix        = 'col-xl-';
	}

	public function menu_args( $args ) {
		$walker_class = sprintf( 'Foxy_Walker_Bootstrap%d_Menu', $this->major_version );
		return wp_parse_args(
			array(
				'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
				'container'       => 'div',
				'container_class' => 'collapse navbar-collapse',
				'container_id'    => 'foxy-menu-' . $args['theme_location'],
				'menu_class'      => 'navbar-nav',
				'fallback_cb'     => $walker_class . '::fallback',
				'walker'          => new $walker_class(),
			), $args
		);
	}

	public function bootstrap_open_navigation_tag( $args, $location ) {
		if ( ! $args['ui_framework'] ) {
			return;
		}
		?>
		<nav class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#foxy-menu-<?php echo esc_attr( $location ); ?>" aria-controls="foxy-menu-<?php echo esc_attr( $location ); ?>" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		<?php
		if ( $location === Foxy::get_main_menu() || (bool) $args['show_logo'] ) {
			Foxy::logo( $args['alternative_logo'] );
		}
	}

	/**
	 * Close navigtation tag
	 *
	 * @param array $args Menu arguments.
	 * @return void
	 */
	public function bootstrap_close_navigation_tag( $args ) {
		if ( ! $args['ui_framework'] ) {
			return;
		}
		echo '</div></nav>';
	}

	public function bootstrap_navbrand_class( $class_name ) {
		return $class_name . ' navbar-brand';
	}

	public function add_class_to_footer_widgets( $default_args ) {
		$footer_widget                 = Foxy::get_num_footer_widgets();
		$default_args['before_widget'] = '<div id="%1$s" class="widget col-sm-6 col-lg-' . ( 12 / $footer_widget ) . ' %2$s">';
		return $default_args;
	}


	/**
	 * Create container block
	 *
	 * @param boolean $close_tag Output close tag for container.
	 * @return string
	 */
	public function container( $close_tag = false ) {
		if ( empty( $close_tag ) ) {
			echo '<div class="container">';
		} else {
			echo '</div>';
		}

	}

	public function pull_and_push_layout( $args ) {
		$class_name = '';
		if ( $this->major_version == 4 ) {
			if ( ! empty( $args['pull'] ) ) {
				$class_name .= ' order-1';
			}
			if ( ! empty( $args['push'] ) ) {
				$class_name .= ' order-2';
			}
		}
		return $class_name;
	}
}
