<?php
/**
 * Foxy Framework
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL-3
 * @link https://wpclouds.com
 */

/**
 * Foxy_Setup class will create Foxy instance and setup all theme features
 *
 * @since 1.0.0
 */
class Foxy_Setup {
	/**
	 * Foxy_Setup instance
	 *
	 * @var Foxy_Setup
	 */
	protected static $instance;

	/**
	 * Foxy instance
	 *
	 * @var Foxy
	 */
	protected $foxy;

	/**
	 * Foxy initialize
	 *
	 * @return Foxy_Setup instance
	 */
	public static function initialize() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class Foxy_Setup constructor
	 *
	 * Define constants, register autoload and init hooks
	 */
	public function __construct() {
		/**
		 * Foxy compatibility with other thirdparty
		 */
		$this->compatibility();

		// Register autoload class.
		$this->autoload();

		// Define foxy constants.
		$this->define_constants();

		/**
		 * Include foxy helpers
		 */
		$this->includes();

		// Init action & filter hooks.
		$this->init_hooks();

		// Load foxy addons.
		$this->load_addons();

		$this->load_assets();

		$this->foxy = Foxy::instance();
		if ( Foxy::is_admin() ) {
			Foxy_Admin::instance();
		}
		// Set foxy instance to global for other integrate.
		$GLOBALS['foxy'] = $this->foxy;

		do_action( 'foxy_loaded' );
	}



	/**
	 * Define all constant will be used in Foxy Core
	 *
	 * @return void
	 */
	public function define_constants() {
		Foxy::define( 'FOXY_FRAMEWORK_CORE', dirname( FOXY_FRAMEWORK_FILE ) . '/' );
		Foxy::define( 'FOXY_ACTIVE_THEME_DIR', get_stylesheet_directory() . '/' );
		Foxy::define( 'FOXY_TEMPLATE_DIR', get_template_directory() . '/' );
	}

	/**
	 * Convert file name from class name for PHP autoloading
	 *
	 * @param string $class_name Class name is called.
	 * @return string
	 */
	private function convert_class_to_file( $class_name ) {
		return strtolower( str_replace( '_', '-', $class_name ) );
	}

	/**
	 * Foxy compatibility
	 *
	 * @return void
	 */
	public function compatibility() {
		$foxy_dir = dirname( FOXY_FRAMEWORK_FILE ) . '/';

		/**
		 * WordPress compatibility
		 */
		require_once $foxy_dir . 'function-wordpress-compatibility.php';
		require_once $foxy_dir . 'function-foxy-helpers.php';

		// Free up memory.
		unset( $foxy_dir );
	}

	/**
	 * Autoload classe and trait files.
	 *
	 * @return void
	 */
	public function autoload() {
		$search_prefixs = array( 'class', 'trait', 'interface' );
		spl_autoload_register(
			function( $class_name ) use ( $search_prefixs ) {
				foreach ( $search_prefixs as $prefix ) {
					$real_file = sprintf(
						'%1$s/%2$s-%3$s.php',
						dirname( FOXY_FRAMEWORK_FILE ),
						$prefix,
						$this->convert_class_to_file( $class_name )
					);
					if ( file_exists( $real_file ) ) {
						require_once $real_file;
					}
				}
			}
		);
	}

	/**
	 * Include foxy helpers and nested files
	 *
	 * @return void
	 */
	public function includes() {
		require_once FOXY_FRAMEWORK_CORE . 'function-foxy-template.php';
		require_once FOXY_FRAMEWORK_CORE . 'function-foxy-content-template.php';
	}

	/**
	 * Init hooks for Foxy core
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'after_setup_theme', array( $this, 'core_init' ) );
		add_action( 'init', array( $this, 'menus' ), 33 );
		add_action( 'init', array( $this, 'datas' ), 33 );
		add_action( 'widgets_init', array( $this, 'sidebars' ), 33 );
		add_action( 'template_redirect', array( $this, 'layout_setup' ) );
	}

	/**
	 * Foxy core initilize
	 *
	 * @return void
	 */
	public function core_init() {
		/**
		 * Use thumbnail for post and other post type
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add theme support posts format
		 */
		add_theme_support( 'post-formats', array( 'aside', 'standard', 'image', 'video' ) );
	}

	/**
	 * Setup WordPress menus
	 *
	 * @return void
	 */
	public function menus() {
		$nav_menus = Foxy::load_config(
			'menu.php',
			apply_filters(
				'foxy_nav_menus',
				array(
					'primary' => __( 'Primary Navigation', 'foxy' ),
				)
			)
		);
		/**
		 * Register all menus
		 */
		register_nav_menus( $nav_menus );
	}

	/**
	 * Register sidebar from theme settings
	 *
	 * @return void
	 */
	public function sidebars() {
		/**
		 * Default sidebar args
		 */
		$sidebar_args = apply_filters(
			'foxy_footer_widgets_defaults_args', array(
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		/**
		 * Primary sidebar args
		 */
		$primary_sidebar_args = apply_filters(
			'foxy_primary_sidebar_args',
			wp_parse_args(
				array(
					'id'          => 'primary',
					'name'        => __( 'Primary Sidebar', 'foxy' ),
					'description' => __( 'Primary sidebar wiget area', 'foxy' ),
				),
				$sidebar_args
			)
		);
		register_sidebar( $primary_sidebar_args );

		if ( Foxy::has_second_sidebar() ) {
			/**
			 * Second sidebar args
			 */
			$primary_sidebar_args = apply_filters(
				'foxy_second_sidebar_args',
				wp_parse_args(
					array(
						'id'          => 'second',
						'name'        => __( 'Second Sidebar', 'foxy' ),
						'description' => __( 'Second sidebar wiget area', 'foxy' ),
					),
					$sidebar_args
				)
			);
			register_sidebar( $primary_sidebar_args );
		}

		/**
		 * Action hook for add other sidebar
		 * Integrate with other plugins and theme functions.
		 */
		do_action( 'foxy_register_additional_sidebars', $sidebar_args );

		$this->register_footer_widgets( $sidebar_args );
	}

	/**
	 * Register footer widgets
	 *
	 * @param array $sidebar_args Default sidebar args.
	 * @return void
	 */
	private function register_footer_widgets( $sidebar_args = array() ) {
		/**
		 * Get number of footer widgets
		 */
		$num_footer_widgets = Foxy::get_num_footer_widgets();

		/**
		 * Footer sidebar default args
		 */
		$sidebar_args = apply_filters( 'foxy_sidebar_default_footer_args', $sidebar_args );

		for ( $index = 1; $index <= $num_footer_widgets; $index++ ) {
			$sidebar_args['id'] = sprintf( 'footer-%d', $index );

			/* translators: %s: Footer widget index */
			$sidebar_args['name'] = sprintf( __( 'Footer %d', 'foxy' ), $index );

			/* translators: %s: Footer widget area */
			$sidebar_args['description'] = sprintf( __( 'Footer %d widget area', 'foxy' ), $index );
			/**
			 * Create filter hooks for footer widget
			 */
			$sidebar_args = apply_filters( "foxy_sidebar_footer_{$index}_args", $sidebar_args );

			/**
			 * Register sidebar
			 */
			register_sidebar( $sidebar_args );
		}
	}

	/**
	 * WordPress data integration
	 *
	 * This function will initialize WordPress data such as: post, page, category, post meta,etc
	 *
	 * @return void
	 */
	public function datas() {
		/**
		 * Get instance of Foxy_Data to register data
		 */
		Foxy_Data::instance();


	}

	/**
	 * Load foxy addons
	 *
	 * @return void
	 */
	public function load_addons() {
		/**
		 * Get active foxy addons
		 */
		$addons = Foxy::get_active_addons();
		foreach ( $addons as $addon ) {
			require_once $addon;
		}
	}


	public function load_assets() {
		/**
		 * Set foxy asset instance to foxy core.
		 */
		Foxy::instance()->asset = function() {
			return Foxy_Asset::instance();
		};

		/**
		 * Initial asset
		 */
		Foxy::asset()
			->register_css(
				foxy_get_template_name(),
				get_template_directory_uri() . '/style.css',
				array( Foxy::get_ui_framework() ),
				FOXY_THEME_FRAMEWORK_VERSION
			)->css( foxy_get_template_name() );
	}

	/**
	 * Render layout for framework
	 *
	 * @return void
	 */
	public function layout_setup() {
		/**
		 * Setup CSS framework for Foxy
		 */
		$ui_framework_name       = Foxy::get_ui_framework();
		$ui_framework_class_name = apply_filters(
			'foxy_ui_framework_class_name',
			sprintf( 'Foxy_UI_Framework_%s', ucfirst( $ui_framework_name ) ),
			$ui_framework_name
		);
		$this->foxy->set_ui_framework(
			new $ui_framework_class_name()
		);

		Foxy_UI_Layout_Renderer::render();
	}
}
