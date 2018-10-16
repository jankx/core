<?php
trait Foxy_Layout {
	protected static $footer_widget_num = 3;
	protected static $use_second_sidebar = true;

	public static function set_num_footer_widgets( $num ) {
		self::$footer_widget_num = (int) $num;
	}

	public static function get_num_footer_widgets() {
		return (int) self::$footer_widget_num;
	}

	public static function has_footer_widget() {
		return self::$footer_widget_num > 0;
	}

	public static function use_second_sidebar( $use = true ) {
		self::$use_second_sidebar = (bool) $use;
	}

	public static function get_second_sidebar() {
		return self::$use_second_sidebar;
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
