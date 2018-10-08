<?php
trait Foxy_Layout {
	protected static $footer_widget_num = 3;
	protected static $use_footer_widget = true;
	protected static $use_second_sidebar = true;

	public static function set_footer_num( $num ) {
		self::$footer_widget_num = (int) $num;
	}

	public static function get_footer_num() {
		return (int) self::$footer_widget_num;
	}

	public static function disable_footer_widget() {
		self::$use_footer_widget = false;
	}

	public static function disable_second_sidebar() {
		self::$use_second_sidebar = false;
	}

	public function set_layout() {
	}


	public function get_layout() {
	}

	public function sidebar() {

	}

	public function footer() {

	}
}
