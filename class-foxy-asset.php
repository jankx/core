<?php
class Foxy_Asset {
	protected static $instance;

	/**
	 * Javascripts will be called via WordPress asset manager
	 *
	 * @var array
	 */

	protected $registered_js  = array();
	protected $registered_css = array();
	protected $js             = array();
	protected $css            = array();
	protected $styles         = array();
	protected $scripts        = array();
	protected $init_scripts   = array();
	protected $libraries      = array();

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	public function __construct() {
		$this->register_libraries();

		$prefix = 'wp';
		if ( Foxy::is_admin() ) {
			$prefix = 'admin';
		}
		add_action( "{$prefix}_enqueue_scripts", array( $this, 'register_scripts' ), 22 );
		add_action( "{$prefix}_enqueue_scripts", array( $this, 'call_scripts' ), 33 );
		add_action( "{$prefix}_head", array( $this, 'header' ), 33 );
		add_action( "{$prefix}_footer", array( $this, 'init_scripts' ), 5 );
		add_action( "{$prefix}_footer", array( $this, 'footer' ), 33 );
	}

	public function js( $handler ) {
		if ( ! in_array( $handler, $this->js, true ) ) {
			$this->js[] = $handler;
		}
		return $this;
	}

	public function css( $handler ) {
		var_dump($this->css);
		if ( ! in_array( $handler, $this->css, true ) ) {
			$this->css[] = $handler;
		}
		return $this;
	}

	public function script( $script, $init = false ) {
		if ($init) {
			if ( ! in_array( $script, $this->init_scripts, true ) ) {
				$this->init_scripts[] = $script;
			}
		} else {
			if ( ! in_array( $script, $this->scripts, true ) ) {
				$this->scripts[] = $script;
			}
		}
		return $this;
	}

	public function style( $style ) {
		if ( ! in_array( $style, $this->styles, true ) ) {
			$this->styles[] = $style;
		}
		return $this;
	}

	public function lib( $lib ) {
		if ( isset( $this->libraries[ $lib ] ) ) {
			$lib_assets = wp_parse_args(
				$this->libraries[ $lib ],
				array(
					'js'  => false,
					'css' => false,
				)
			);
			if ( $lib_assets['js'] ) {
				$this->js( $lib );
			}
			if ( $lib_assets['css'] ) {
				$this->css( $lib );
			}
		}
		return $this;
	}

	private function lib_url( $lib_name, $ver, $ext = 'css', $subfolder = false ) {
		$lib_url = apply_filters( 'foxy_asset_libs_url', get_template_directory_uri() . '/public/lib' );

		$asset_url = sprintf(
			$subfolder ? '%1$s/%2$s-%3$s/%4$s/%2$s.min.%4$s' : '%1$s/%2$s-%3$s/%2$s.min.%4$s',
			$lib_url,
			$lib_name,
			$ver,
			$ext
		);

		return apply_filters( "foxy_asset_lib_{$lib_name}_url", $asset_url, $lib_name, $ver, $lib_url, $ext );
	}

	private function register_libraries() {
		$bootstrap_version = apply_filters( 'foxy_asset_bootstrap_version', '4.1.3' );

		$this->register_js( 'bootstrap', $this->lib_url( 'bootstrap', $bootstrap_version, 'js', true ), array( 'jquery' ), $bootstrap_version, true );
		$this->register_css( 'bootstrap', $this->lib_url( 'bootstrap', $bootstrap_version, 'css', true ), null, $bootstrap_version );
		$this->libraries['bootstrap'] = array(
			'css' => true,
			'js'  => true,
		);

		$this->register_css( 'animate', $this->lib_url( 'animate', '3.7.0', 'css' ), null, '3.7.0' );
		$this->libraries['animate'] = array( 'css' => true );

		do_action( 'foxy_register_assets' );
	}

	public function register_framework_assets() {
		if ( is_admin() ) {
			$this->register_css(
				'admin-foxy',
				Foxy_Admin::asset_url( 'css/foxy.css' ),
				array(),
				FOXY_THEME_FRAMEWORK_VERSION
			)->css( 'admin-foxy' );

			do_action( 'foxy_load_assets_admin' );
		} else {
			$this->register_css(
				foxy_get_template_name(),
				get_template_directory_uri() . '/style.css',
				array( Foxy::get_ui_framework() ),
				FOXY_THEME_FRAMEWORK_VERSION
			)->css( foxy_get_template_name() );

			do_action( 'foxy_load_assets_frontent' );
		}
	}

	public function register_js( $handler, $js_url, $dep = array(), $ver = '', $in_footer = true ) {
		$this->registered_js[ $handler ] = array( $handler, $js_url, $dep, $ver, $in_footer );
		return $this;
	}

	public function register_css( $handler, $css_url, $dep = array(), $ver = '', $media = 'all' ) {
		$this->registered_css[ $handler ] = array( $handler, $css_url, $dep, $ver, $media );
		return $this;
	}

	public function register_scripts() {
		if ( ! empty( $this->registered_css ) ) {
			foreach ( $this->registered_css as $args ) {
				call_user_func_array( 'wp_register_style', $args );
			}
		}
		// Free up memory.
		unset( $this->registered_css, $args );

		if ( ! empty( $this->registered_js ) ) {
			foreach ( $this->registered_js as $args ) {
				call_user_func_array( 'wp_register_script', $args );
			}
		}
		// Free up memory.
		unset( $this->registered_js, $args );
	}

	public function call_scripts() {
		if ( is_array( $this->css ) && count( $this->css ) ) {
			foreach ( $this->css as $css ) {
				wp_enqueue_style( $css );
			}
		}
		// Free up memory.
		unset( $this->css, $css );

		if ( is_array( $this->js ) && count( $this->js ) ) {
			foreach ( $this->js as $js ) {
				wp_enqueue_script( $js );
			}
		}
		// Free up memory.
		unset( $this->js, $js );
	}

	public function header() {
		Foxy::ui()->tag(
			array(
				'name'    => 'style',
				'context' => 'foxy-ui-style-tag',
			)
		);
		echo implode( "\n", $this->styles ); // WPCS: XSS ok.
		echo '</style>';
		// Free up memory.
		unset( $this->styles );
	}

	public function init_scripts() {
		?>
		<script>
			<?php echo implode( "\n", $this->init_scripts ); // WPCS: XSS ok. ?>
		</script>
		<?php
		// Free up memory.
		unset( $this->init_scripts );
	}

	public function footer() {
		?>
		<script>
			(function($){
				<?php echo implode( "\n", $this->scripts ); // WPCS: XSS ok. ?>
			})(jQuery);
		</script>
		<?php
		// Free up memory.
		unset( $this->scripts );
	}
}
