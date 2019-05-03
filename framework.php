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

if (!function_exists('jankx')) {
    function jankx()
    {
        return Jankx::instance();
    }
}

if (empty($GLOBALS['jankx'])) {
    $GLOBALS['jankx'] = jankx();
}
