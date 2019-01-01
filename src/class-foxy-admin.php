<?php
class Foxy_Admin {

	protected static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->setting_up_ui_framework();
		Foxy::instance()->admin = function() {
			return self::instance();
		};
		Foxy::option()->admin_page();

		add_action( 'current_screen', array( $this, 'setup_screen_edit_post' ) );
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
		$current_screen = get_current_screen();
		if ( 'post' !== $current_screen->base ) {
			return;
		}

		$hide_title_supports = apply_filters( 'foxy_post_type_support_hide_title', array( 'post', 'page' ) );
		if ( in_array( $current_screen->post_type, $hide_title_supports, true ) ) {
			add_action( 'post_submitbox_misc_actions', array( Foxy_Admin_UI_Common::class, 'choose_hide_post_title' ) );
		}

		add_action( 'add_meta_boxes', array( $this, 'choose_site_layout' ) );
		add_action( 'save_post', array( $this, 'save_foxy_framework_fields' ), 10, 2 );
	}

	public function save_foxy_framework_fields( $post_id, $post ) {
		if ( isset( $_POST['foxy_hide_post_title'] ) ) {
			update_post_meta( $post_id, 'foxy_hide_post_title', 'yes' );
		}

		if ( isset( $_POST['foxy_site_layout'] ) && '' !== $_POST['foxy_site_layout'] ) {
			update_post_meta( $post_id, 'foxy_site_layout', $_POST['foxy_site_layout'] );
		} else {
			delete_post_meta( $post_id, 'foxy_site_layout' );
		}
	}

	public function choose_site_layout( $post ) {
		$foxy_supported_custom_layout = apply_filters( 'foxy_post_type_support_custom_layout', array( 'page', 'post' ) );
		add_meta_box( 'foxy-site-layout', __( 'Site Layout', 'foxy' ), array( Foxy_Admin_UI_Common::class, 'choose_site_layout' ), $foxy_supported_custom_layout );
	}

	public static function asset_url( $path = null ) {
		$template_directory = get_template_directory_uri();
		$asset_url          = sprintf( '%s/admin/%s', $template_directory, $path );
		return apply_filters( 'foxy_admin_asset_url', $asset_url, $path, $template_directory );
	}
}
