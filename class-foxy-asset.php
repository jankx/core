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
		return $this;
	}

	public function css() {
		return $this;
	}

	public function script() {
		return $this;
	}

	public function style() {
		return $this;
	}

	public function lib() {
		return $this;
	}

	public function call_scripts() {

	}

	public function header() {
	}

	public function footer() {
	}
}
