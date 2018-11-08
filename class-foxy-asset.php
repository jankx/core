<?php
class Foxy_Asset {
	protected static $instance;
	protected $registered_css = array();
	protected $registered_js = array();
	protected $js = array();
	protected $css = array();
	protected $styles = array();
	protected $scripts = array();
	protected $libraries = array();

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	public function __construct() {
		$prefix = 'wp';
		if ( Foxy::is_admin() ) {
			$prefix = 'admin';
		}
		add_action( "{$prefix}_enqueue_scripts", array( $this, 'register_libraries' ), 3 );
		add_action( "{$prefix}_enqueue_scripts", array( $this, 'call_scripts' ), 33 );
		add_action( "{$prefix}_head", array( $this, 'header' ), 33 );
		add_action( "{$prefix}_footer", array( $this, 'footer' ), 33 );
	}
	public function js( $handler ) {
		if ( ! in_array( $handler, $this->js ) ) {
			$this->js[] = $handler;
		}
		return $this;
	}

	public function css( $handler ) {
		if ( ! in_array( $handler, $this->css ) ) {
			$this->css[] = $handler;
		}
		return $this;
	}

	public function script( $script ) {
		if ( ! in_array( $script, $this->scripts, true ) ) {
			$this->scripts[] = $script;
		}
		return $this;
	}

	public function style() {
		if ( ! in_array( $style, $this->styles, true ) ) {
			$this->styles[] = $style;
		}
		return $this;
	}

	public function lib() {
		return $this;
	}

	private function register_libraries() {
	}

	public function register_js() {

	}

	public function register_css( $handler, $css_url, $dep = array(), $ver = '', $media = 'all' ) {

	}

	public function header() {
		Foxy::ui()->tag( array( 'name' => 'style', 'context' => 'foxy-ui-style-tag' ) );
		echo implode( "\n", $this->styles ); // WPCS: XSS ok.
		echo '</style>';
	}

	public function footer() {
		?>
		<script>
			(function($){
				<?php echo implode( "\n", $this->scripts ); // WPCS: XSS ok. ?>
			})(jQuery);
		</script>
		<?php
	}
}
