<?php

class Foxy_Admin_UI_Common {
	protected static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function admin_featured_column() {
	}

	public function choose_site_layout() {
	}

	public function widget_post_layout( $instance ) {
	}

	public function widget_post_taxonomy_layout( $instance ) {
	}

	public function edit_post_choose_layout() {
	}

	public function edit_taxonomy_choose_layout() {
	}

	public function post_type_archive_choose_layout() {
	}
}
