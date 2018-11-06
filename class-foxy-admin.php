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
        $this->setup_screen_edit_post();
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

    public function setup_screen_edit_post() {
        add_action( 'post_submitbox_misc_actions', array( Foxy_Admin_UI_Common::class, 'choose_hide_post_title' ) );
        add_action( 'save_post', array( $this, 'save_foxy_framework_fields' ), 10, 2 );
    }

    public function save_foxy_framework_fields( $post_id, $post )  {
        if ( isset( $_POST['foxy_hide_post_title'] ) ) {
            update_post_meta( $post_id, 'foxy_hide_post_title', 'yes' );
        } else {
            delete_post_meta( $post_id, 'foxy_hide_post_title' );
        }
    }
}
