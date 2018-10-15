<?php
trait Foxy_Layout {
	protected static $footer_widget_num = 3;
	protected static $use_second_sidebar = true;

	public static function set_footer_num( $num ) {
		self::$footer_widget_num = (int) $num;
	}

	public static function get_footer_num() {
		return (int) self::$footer_widget_num;
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
