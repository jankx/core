<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class Cache
{
    protected static $globalCss = array();
    protected static $globalJs = array();

    protected static function generateAssetFilePath()
    {
    }

    public static function addGlobalCss($css)
    {
        if (empty($css)) {
            return;
        }
        static::$globalCss[] = $css;
    }

    public static function addGlobalJs($js)
    {
    }

    public static function addCss($css, $type, $object_id = null)
    {
    }

    public static function addJs($js, $type, $object_id = null)
    {
    }

    /**
     * Safely create directory with validation
     *
     * @param string $dir_path
     * @return bool
     */
    protected static function safe_mkdir($dir_path)
    {
        // Validate path
        if (empty($dir_path) || !is_string($dir_path)) {
            return false;
        }

        // Prevent directory traversal
        $real_path = realpath(dirname($dir_path));
        if ($real_path === false) {
            return false;
        }

        // Check if directory already exists
        if (is_dir($dir_path)) {
            return true;
        }

        // Create directory with proper permissions
        try {
            return mkdir($dir_path, 0755, true);
        } catch (Exception $e) {
            error_log('Jankx Cache: Failed to create directory - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Safely write file with validation
     *
     * @param string $file_path
     * @param string $content
     * @return bool
     */
    protected static function safe_write_file($file_path, $content)
    {
        // Validate inputs
        if (empty($file_path) || !is_string($file_path) || !is_string($content)) {
            return false;
        }

        // Prevent directory traversal
        $real_path = realpath(dirname($file_path));
        if ($real_path === false) {
            return false;
        }

        // Ensure directory exists
        $dir_path = dirname($file_path);
        if (!is_dir($dir_path) && !self::safe_mkdir($dir_path)) {
            return false;
        }

        // Write file with error handling
        try {
            $handle = fopen($file_path, 'w');
            if ($handle === false) {
                return false;
            }

            $result = fwrite($handle, $content);
            fclose($handle);

            return $result !== false;
        } catch (Exception $e) {
            error_log('Jankx Cache: Failed to write file - ' . $e->getMessage());
            return false;
        }
    }

    protected static function loadGlobalCss()
    {
        if (!has_action('jankx_asset_generate_global_css_cache') && defined('JANKX_CACHE_DIR_URL')) {
            add_action('jankx_asset_generate_global_css_cache', function ($globalCss) {
                $cacheDir = rtrim(JANKX_CACHE_DIR, '/');
                $globalCssFile = sprintf('%s/global.css', $cacheDir);

                // Validate cache directory path
                if (!function_exists('jankx_validate_path')) {
                    // Fallback to old method if helper not available
                    if (!file_exists($globalCssFile)) {
                        if (!is_dir($cacheDir) && !self::safe_mkdir($cacheDir)) {
                            error_log('Jankx Cache: Failed to create cache directory');
                            return;
                        }

                        $css_content = implode("\n", $globalCss);
                        if (!self::safe_write_file($globalCssFile, $css_content)) {
                            error_log('Jankx Cache: Failed to write global CSS file');
                            return;
                        }
                    }
                } else {
                    // Use path validator
                    $valid_cache_dir = jankx_validate_path($cacheDir);
                    $valid_css_file = jankx_validate_path($globalCssFile, $cacheDir);

                    if ($valid_cache_dir === false || $valid_css_file === false) {
                        error_log('Jankx Cache: Invalid cache path detected');
                        return;
                    }

                    if (!file_exists($valid_css_file)) {
                        if (!is_dir($valid_cache_dir) && !self::safe_mkdir($valid_cache_dir)) {
                            error_log('Jankx Cache: Failed to create cache directory');
                            return;
                        }

                        $css_content = implode("\n", $globalCss);
                        if (!self::safe_write_file($valid_css_file, $css_content)) {
                            error_log('Jankx Cache: Failed to write global CSS file');
                            return;
                        }
                    }
                }

                wp_enqueue_style(
                    'jankx-css-global',
                    sprintf('%s/global.css', rtrim(JANKX_CACHE_DIR_URL, '/')),
                    array(),
                    filemtime($globalCssFile)
                );
            });
        }
        do_action(
            'jankx_asset_generate_global_css_cache',
            apply_filters(
                'jankx/asset/cache/global_css',
                static::$globalCss
            )
        );
    }

    public static function load()
    {
        static::loadGlobalCss();
    }

    public static function globalCssIsExists()
    {
        return apply_filters(
            'jankx_asset_global_css_cache_exists',
            defined('JANKX_CACHE_DIR') && file_exists(sprintf('%s/global.css', rtrim(JANKX_CACHE_DIR, '/')))
        );
    }
}
