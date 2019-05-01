<?php
namespace Jankx\Core\Traits;

trait Request {
	public static function is_admin() {
		return is_admin() && ! defined( 'DOING_AJAX' );
	}

	public static function is_ajax() {
		return defined( 'DOING_AJAX' );
	}

	public static function is_cron() {
		return defined( 'DOING_CRON' );
	}

	public static function is_cli() {
		defined( 'WP_CLI' );
	}

	public static function is_frontend() {
		return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
	}
}
