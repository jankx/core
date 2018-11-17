<?php
/**
 * This file define all base method of option framework can be use in child class
 *
 * @package Foxy/Core
 * @author Puleeno Nguyen <puleeno@gmail.com
 * @license GPLv3
 * @link https://wpclouds.com
 */

/**
 * Foxy_Option_Framework_Base class
 */
abstract class Foxy_Option_Framework_Base implements Foxy_Option_Framework_Interface {
	/**
	 * Set current option key for add new fields
	 *
	 * @var string
	 */
	protected $id;
	protected $loaded_options;

	/**
	 * Foxy_Option_Framework_Base constructor
	 */
	public function __construct() {
		$this->id( apply_filters( 'foxy_default_option_key_name', foxy_get_theme_name() ) );
		$this->set_args( $this->id, $this->default_args() );
	}

	public function id( $id = null ) {
		$this->id = preg_replace( '/-/', '_', $id );
		return $this;
	}

	public function admin_page() {
		$section_groups = $this->load_option_sections();

		$sort_option_callback = apply_filters( 'foxy_sort_option_callback', false );
		if ( is_callable( $sort_option_callback ) ) {
			usort( $section_groups, $sort_option_callback );
		}

		foreach ( $section_groups as $sections ) {
			if ( empty( $sections ) ) {
				continue;
			}
			$this->add_sections( $sections );
		}
		// Free up memory.
		unset( $section_groups, $sort_option_callback, $sections );
	}

	protected function default_args() {
		$theme      = wp_get_theme();
		$theme_name = $theme->get( 'Name' );
		$page_title = sprintf( __( '%s Options', 'foxy' ), $theme_name );
		$menu_title = strlen( $theme_name ) <= 6 ? $page_title : __( 'Theme Options', 'foxy' );
		$args       = array(
			'opt_name'           => $this->id,
			'display_name'       => $theme_name,
			'menu_title'         => $menu_title,
			'page_title'         => $page_title,
			'display_version'    => $theme->get( 'Version' ),
			'google_api_key'     => '',
			'admin_bar'          => true,
			'admin_bar_icon'     => 'fx-settings',
			'admin_bar_priority' => 50,
			'dev_mode'           => false,
			'update_notice'      => false,
			'customizer'         => true,
			'page_priority'      => 60,
			'menu_type'          => 'menu',
			'page_parent'        => 'themes.php',
			'page_permissions'   => 'edit_theme_options',
			'menu_icon'          => '',
			'page_icon'          => 'icon-themes',
			'page_slug'          => 'foxy',
			'save_defaults'      => true,
			'default_show'       => false,
			'show_import_export' => true,
		);

		$args['share_icons'][] = array(
			'url'   => 'https://github.com/foxy-theme/foxy',
			'title' => 'Visit us on GitHub',
			'icon'  => 'fx-github',
		);
		$args['share_icons'][] = array(
			'url'   => 'https://www.facebook.com/foxythemeframework',
			'title' => 'Like us on Facebook',
			'icon'  => 'fx-facebook',
		);
		$args['share_icons'][] = array(
			'url'   => 'https://twitter.com/foxythemeframework',
			'title' => 'Follow us on Twitter',
			'icon'  => 'fx-twitter',
		);

		$args['intro_text']  = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'foxy' );
		$args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'foxy' );

		// Free up memory.
		unset( $theme, $theme_name, $page_title, $menu_title );

		return apply_filters( 'foxy_default_option_args', $args );
	}

	public function load_option_sections() {
		$option_files = $this->search_option_files();
		$sections     = array(
			'general'  => array(),
			'layout'   => array(),
			'advanced' => array(),
			'seo'      => array(),
			'addons'   => array(),
		);

		foreach ( $option_files as $option_file ) {
			$fields = require $option_file;
			if ( ! is_array( $fields ) ) {
				continue;
			}
			$group = str_replace(
				array(
					FOXY_TEMPLATE_DIR,
					FOXY_ACTIVE_THEME_DIR,
				), '', $option_file
			);
			$group = ltrim( $group, 'includes/' );
			$group = ltrim( $group, 'options/' );
			if ( strpos( $group, '/' ) ) {
				$group = explode( '/', $group );
				$group = array_shift( $group );
			} else {
				$group = 'other';
			}
			if ( isset( $sections[ $group ] ) ) {
				$sections[ $group ] = array_merge_recursive( $sections[ $group ], $fields );
			} else {
				$sections[ $group ] = $fields;
			}
		}
		// Free up memory.
		unset( $option_files, $option_file, $fields, $group );

		return $sections;
	}

	private function search_option_files() {
		$search_directories = apply_filters(
			'foxy_options_directories',
			array(
				sprintf( '%soptions', FOXY_TEMPLATE_DIR ),
				sprintf( '%sincludes/options', FOXY_TEMPLATE_DIR ),
				sprintf( '%soptions', FOXY_ACTIVE_THEME_DIR ),
				sprintf( '%sincludes/options', FOXY_ACTIVE_THEME_DIR ),
			)
		);
		$option_files       = array();
		foreach ( (array) $search_directories as $directory ) {
			if ( ! file_exists( $directory ) ) {
				continue;
			}
			$searched_files = glob( $directory . '/{*.php,*/*.php}', GLOB_BRACE );
			$option_files   = array_merge_recursive( $option_files, $searched_files );
		}

		// Free up memory.
		unset( $search_directories, $directory );

		return $option_files;
	}
}
