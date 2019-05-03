<?php
/**
 * Load Jankx config file.
 * Jankx config file use to customize Jankx core function or Jankx addon
 *
 * @package Jankx/Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @link https://puleeno.com
 */

namespace Jankx\Core\Traits;
/**
 * Config class
 */
trait Config {
	/**
	 * Get jankx config from PHP file
	 *
	 * @param string $config_file Config file path.
	 * @param mixed  $default_value Default value if config file does not exists.
	 * @return mixed
	 */
	public static function load_config( $config_file, $default_value = false ) {
		$config_diretories = apply_filters(
			'config_directories', array(
				ACTIVE_THEME_DIR . 'configs/',
				TEMPLATE_DIR . 'configs/',
			)
		);

		foreach ( $config_diretories as $config_directory ) {
			if ( file_exists( $config_directory . $config_file ) ) {
				return require $config_directory . $config_file;
			}
		}

		return $default_value;
	}
}
