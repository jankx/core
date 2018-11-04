<?php

class Foxy_Asset {
	protected static $instance;

	protected $js = array();
	protected $css = array();
	protected $styles = array();
	protected $scripts = array();
	protected $library = array();
	protected $use_cdn = false;

	public static function instance() {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'call_scripts' ), 33 );
		add_action( 'wp_head', array( $this, 'header' ), 33 );
		add_action( 'wp_footer', array( $this, 'footer' ), 33 );
	}
	public function js() {
		return self;
	}

	public function css() {
		return self;
	}

	public function script() {
		return self;
	}

	public function style() {
		return self;
	}

	public function lib() {
		return self;
	}

	public function call_scripts() {

	}

	public function header() {
	}

	public function footer() {
	}
}
