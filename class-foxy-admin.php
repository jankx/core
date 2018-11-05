<?php
class Foxy_Admin {

    protected static $instance;

    public static function instance() {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->setting_up_ui_framework();

        Foxy::instance()->admin = function() {
            return self::instance();
        };
    }


    public function setting_up_ui_framework() {
        /**
		 * Setup CSS framework for Foxy
		 */
        $ui_framework_name       = apply_filters( 'foxy_admin_ui_framework', 'admin' );
		$ui_framework_class_name = apply_filters(
			'foxy_ui_framework_class_name',
			sprintf( 'Foxy_UI_Framework_%s', ucfirst( $ui_framework_name ) ),
			$ui_framework_name
        );
		Foxy::instance()->set_ui_framework(
			new $ui_framework_class_name()
		);
    }

    public function common() {
        return Foxy_Admin_UI_Common::instance();
    }
}
