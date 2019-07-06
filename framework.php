<?php
/**
 * Jankx Framework Core
 *
 * @package Jankx/Core
 * @author  Puleeno Nguyen <puleeno@gmail.com>
 * @license GPL
 * @link    https://puleeno.com
 */

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

define('JANKX_FRAMEWORK_FILE_LOADER', __FILE__);

if (!function_exists('jankx')) {
    function jankx()
    {
        return Jankx::instance();
    }
}

if (empty($GLOBALS['jankx'])) {
    $GLOBALS['jankx'] = jankx();
}
