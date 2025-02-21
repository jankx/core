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

if (empty($GLOBALS['jankx'])) {
    $jankxInstance = Jankx::getInstance();

    add_action(
        'after_setup_theme',
        array($jankxInstance, 'setup'),
        2
    );
}
