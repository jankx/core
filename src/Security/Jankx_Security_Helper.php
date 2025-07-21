<?php

namespace Jankx\Security;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

/**
 * Security Helper Functions for Jankx Framework
 */

class Jankx_Security_Helper
{
    /**
     * Safely get POST data with validation
     *
     * @param string $key
     * @param string $default
     * @param string $sanitize_callback
     * @return mixed
     */
    public static function get_post_data($key, $default = '', $sanitize_callback = 'sanitize_text_field')
    {
        if (!isset($_POST[$key])) {
            return $default;
        }

        $value = $_POST[$key];

        if (is_callable($sanitize_callback)) {
            return call_user_func($sanitize_callback, $value);
        }

        return sanitize_text_field($value);
    }

    /**
     * Safely get GET data with validation
     *
     * @param string $key
     * @param string $default
     * @param string $sanitize_callback
     * @return mixed
     */
    public static function get_get_data($key, $default = '', $sanitize_callback = 'sanitize_text_field')
    {
        if (!isset($_GET[$key])) {
            return $default;
        }

        $value = $_GET[$key];

        if (is_callable($sanitize_callback)) {
            return call_user_func($sanitize_callback, $value);
        }

        return sanitize_text_field($value);
    }

    /**
     * Safely get integer from GET data
     *
     * @param string $key
     * @param int $default
     * @return int
     */
    public static function get_get_int($key, $default = 0)
    {
        $value = self::get_get_data($key, $default);
        return absint($value);
    }

    /**
     * Safely get integer from POST data
     *
     * @param string $key
     * @param int $default
     * @return int
     */
    public static function get_post_int($key, $default = 0)
    {
        $value = self::get_post_data($key, $default);
        return absint($value);
    }

    /**
     * Verify nonce with proper error handling
     *
     * @param string $nonce_key
     * @param string $action
     * @return bool
     */
    public static function verify_nonce($nonce_key, $action)
    {
        if (!isset($_POST[$nonce_key])) {
            return false;
        }

        return wp_verify_nonce($_POST[$nonce_key], $action);
    }

    /**
     * Safe file operations with error handling
     *
     * @param string $file_path
     * @param string $operation
     * @param mixed $data
     * @return mixed
     */
    public static function safe_file_operation($file_path, $operation = 'read', $data = null)
    {
        // Validate file path
        if (empty($file_path) || !is_string($file_path)) {
            return false;
        }

        // Prevent directory traversal
        $real_path = realpath($file_path);
        if ($real_path === false) {
            return false;
        }

        try {
            switch ($operation) {
                case 'read':
                    if (!file_exists($real_path) || !is_readable($real_path)) {
                        return false;
                    }
                    return file_get_contents($real_path);

                case 'write':
                    if (!is_writable(dirname($real_path))) {
                        return false;
                    }
                    return file_put_contents($real_path, $data);

                case 'append':
                    if (!is_writable($real_path)) {
                        return false;
                    }
                    return file_put_contents($real_path, $data, FILE_APPEND | LOCK_EX);

                default:
                    return false;
            }
        } catch (Exception $e) {
            error_log('Jankx Security: File operation failed - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sanitize SVG content
     *
     * @param string $svg_content
     * @return string
     */
    public static function sanitize_svg($svg_content)
    {
        // Remove potentially dangerous elements and attributes
        $dangerous_elements = array('script', 'object', 'embed', 'iframe');
        $dangerous_attributes = array('onload', 'onerror', 'onclick', 'onmouseover');

        // Remove dangerous elements
        foreach ($dangerous_elements as $element) {
            $svg_content = preg_replace('/<' . $element . '[^>]*>.*?<\/' . $element . '>/is', '', $svg_content);
        }

        // Remove dangerous attributes
        foreach ($dangerous_attributes as $attr) {
            $svg_content = preg_replace('/\s+' . $attr . '\s*=\s*["\'][^"\']*["\']/i', '', $svg_content);
        }

        return $svg_content;
    }

    /**
     * Validate and sanitize URL
     *
     * @param string $url
     * @return string
     */
    public static function sanitize_url($url)
    {
        $url = esc_url_raw($url);

        // Additional validation for localhost URLs
        if (strpos($url, 'localhost') !== false || strpos($url, '127.0.0.1') !== false) {
            // Only allow localhost in development
            if (!defined('WP_DEBUG') || !WP_DEBUG) {
                return '';
            }
        }

        return $url;
    }
}

// Helper functions for backward compatibility
if (!function_exists('jankx_get_post_data')) {
    function jankx_get_post_data($key, $default = '', $sanitize_callback = 'sanitize_text_field')
    {
        return Jankx_Security_Helper::get_post_data($key, $default, $sanitize_callback);
    }
}

if (!function_exists('jankx_get_get_data')) {
    function jankx_get_get_data($key, $default = '', $sanitize_callback = 'sanitize_text_field')
    {
        return Jankx_Security_Helper::get_get_data($key, $default, $sanitize_callback);
    }
}

if (!function_exists('jankx_verify_nonce')) {
    function jankx_verify_nonce($nonce_key, $action)
    {
        return Jankx_Security_Helper::verify_nonce($nonce_key, $action);
    }
}
