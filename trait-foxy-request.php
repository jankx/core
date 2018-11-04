<?php
trait Foxy_Request {
	public static function is_admin() {
		return is_admin() && ! defined( 'DOING_AJAX' );
	}

	public static function is_ajax() {
		return defined( 'DOING_AJAX' );
	}

	public function is_cron() {
		return defined( 'DOING_CRON' );
	}

	public static function is_cli() {
		defined( 'WP_CLI' );
	}

	public static function is_frontend() {
		return ! is_admin() && ! defined( 'DOING_CRON' ) && defined( 'DOING_AJAX' );
	}
}
