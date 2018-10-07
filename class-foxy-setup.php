<?php
/**
 * Foxy Framework
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL-3
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
		$this->define_constants();
		$this->autoload();
		$this->init_hooks();

		Foxy::instance();
	}

	/**
	 * Method check and define constant if not defined before
	 *
	 * @param string          $name   Constant name.
	 * @param string|bool|int $val    Constant value.
	 * @return void
	 */
	private function define( $name, $val ) {
		if ( ! defined( $name ) ) {
			define( $name, $val );
		}
	}

	/**
	 * Define all constant will be used in Foxy Core
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->define( 'FOXY_FRAMEWORK_CORE', dirname( FOXY_FRAMEWORK_FILE ) . '/' );
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
	 * Autoload classe and trait files.
	 *
	 * @return void
	 */
	public function autoload() {
		$search_prefixs = array( 'class', 'trait', 'interface' );
		spl_autoload_register( function( $class_name ) use ( $search_prefixs ) {
			foreach ( $search_prefixs as $prefix ) {
				$real_file = sprintf(
					'%1$s%2$s-%3$s.php',
					FOXY_FRAMEWORK_CORE,
					$prefix,
					$this->convert_class_to_file( $class_name )
				);
				if ( file_exists( $real_file ) ) {
					require_once $real_file;
				}
			}
		} );
	}

	/**
	 * Init hooks for Foxy core
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'menus' ), 33 );
		add_action( 'init', array( $this, 'sidebars' ), 33 );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function menus() {
		$nav_menus = apply_filters(
			'foxy_nav_menus', array(
				'primary' => __( 'Primary Navigation', 'foxy' ),
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
		$this->register_footer_widgets();
	}

	/**
	 * Register footer widgets
	 */
	private function register_footer_widgets() {
		$footer_num = Foxy::get_footer_num();
		$sidebar_args = apply_filters(
			'foxy_sidebar_defaults_args', array(
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			)
		);

		for ( $index = 1; $index <= $footer_num; $index++ ) {
			$sidebar_args['id']   = sprintf( 'footer-%d', $index );
			$sidebar_args['name'] = sprintf( __( 'Footer %d', 'foxy' ), $index );
			$sidebar_args['description'] = sprintf( __( 'Footer %d', 'foxy' ), $index );
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
}
