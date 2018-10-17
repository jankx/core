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
 * Foxy_UI_Framework_Bootstrap class
 * Default Version: Bootstrap 4
 */
class Foxy_UI_Framework_Bootstrap extends Foxy_UI_Framework_Base {
	/**
	 * Boostrap version use for Foxy UI Bootstrap
	 *
	 * @var string
	 */
	protected $version = '4.1.3';

	/**
	 * Bootstrap major version
	 *
	 * @var int
	 */
	protected $major_version = 4;

	/**
	 * Bootstrap asset directory location
	 *
	 * @var string
	 */
	protected $assets_dir;

	/**
	 * Bootsttrap UI Framework constructor
	 */
	public function __construct() {
		$this->version = $this->bootstrap_version();

		$this->major_version = (int) substr(
			$this->version,
			0,
			strpos( $this->version, '.' )
		);

		$this->assets_dir = apply_filters(
			'foxy_ui_bootstrap_framework_assets',
			sprintf(
				'%s/public/lib/bootstrap-%s/',
				get_template_directory_uri(),
				$this->version
			)
		);

		add_filter( 'foxy_default_ui_menu_args', array( $this, 'menu_args' ) );
		add_action( 'foxy_before_render_menu', array( $this, 'bootstrap_open_navigation_tag' ), 5, 2 );
		add_action( 'foxy_after_render_menu', array( $this, 'bootstrap_close_navigation_tag' ), 15 );
		add_filter( 'foxy_logo_class_name', array( $this, 'bootstrap_navbrand_class' ) );
		add_filter( 'foxy_sidebar_default_footer_args', array( $this, 'add_class_to_footer_widgets' ) );

		parent::__construct();
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
		return apply_filters( 'foxy_ui_bootstrap_framework_version', $this->version );
	}

	/**
	 * Register bootstrap assets for framework
	 *
	 * @return void
	 */
	public function register_scripts() {
		wp_register_style(
			$this->get_name(),
			$this->assets_dir . 'css/bootstrap.min.css',
			array(),
			$this->version
		);

		wp_register_script(
			$this->get_name(),
			$this->assets_dir . 'js/bootstrap.min.js',
			array( 'jquery' ),
			$this->version,
			true
		);
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

	public function bootstrap_close_navigation_tag( $args ) {
		if ( ! $args['ui_framework'] ) {
			return;
		}
		?>
				</div>
			</div>
		</nav>
		<?php
	}

	public function bootstrap_navbrand_class( $class_name ) {
		return $class_name . ' navbar-brand';
	}

	public function add_class_to_footer_widgets( $default_args ) {
		$footer_widget = Foxy::get_num_footer_widgets();
		$default_args['before_widget'] = '<div id="%1$s" class="widget col-sm-6 col-lg-' . (12/$footer_widget) . ' %2$s">';
		return $default_args;
	}


	/**
	 * Create container block
	 *
	 * @param boolean $close_tag Output close tag for container.
	 * @return string
	 */
	public function container( $close_tag = false ) {
		// code not be implement.
	}

	/**
	 * Site breadcrumb
	 *
	 * @return string
	 */
	public function breadcrumb() {
		// code not be implement.
	}

	/**
	 * Create paginate for WordPress
	 *
	 * @return string
	 */
	public function paginate() {
		// code not be implement.
	}

	/**
	 * Create button HTML for framework
	 *
	 * @return string
	 */
	public function button() {
		// code not be implement.
	}

	/**
	 * Make collapse via UI framework must be require javascript
	 *
	 * @return void
	 */
	public function collapse() {
		// code not be implement.
	}

	/**
	 * Make dropdown for user choosen
	 * This not be select HTML tag
	 *
	 * @return string
	 */
	public function dropdown() {
		// code not be implement.
	}

	/**
	 * Create scollspy function for site
	 * Require javscript
	 *
	 * @return string
	 */
	public function scrollspy() {
		// code not be implement.
	}

	/**
	 * Make form for framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function form() {
		// code not be implement.
	}

	/**
	 * Make text input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function text() {
		// code not be implement.
	}

	/**
	 * Make number input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function number() {
		// code not be implement.
	}

	/**
	 * Make radio input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function radio() {
		// code not be implement.
	}

	/**
	 * Make checkbox input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function checkbox() {
		// code not be implement.
	}

	/**
	 * Make textarea input for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function textarea() {
		// code not be implement.
	}

	/**
	 * Make select choosen for form tag of framework
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function select() {
		// code not be implement.
	}

	/**
	 * Make alert message
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function alert() {
		// code not be implement.
	}

	/**
	 * Make navigation
	 * Use HTML tag with classname to integrate with UI framework
	 *
	 * @return string
	 */
	public function nav() {
		// code not be implement.
	}
}
