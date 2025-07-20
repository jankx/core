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
    exit('Cheating huh?');
}

if (!defined('JANKX_FRAMEWORK_FILE_LOADER')) {
    define('JANKX_FRAMEWORK_FILE_LOADER', __FILE__);

    if (empty($GLOBALS['jankx'])) {
        $jankxInstance = \Jankx\Jankx::getInstance();

        add_action(
            'after_setup_theme',
            array($jankxInstance, 'setup'),
            2
        );
    }
}

if (!defined('JANKX_FRAMEWORK_DIRECTORY')) {
define('JANKX_FRAMEWORK_DIRECTORY', dirname(JANKX_FRAMEWORK_FILE_LOADER));
}

if (!defined('JANKX_FRAMEWORK_INCLUDE_DIRECTORY')) {
    define('JANKX_FRAMEWORK_INCLUDE_DIRECTORY', implode(DIRECTORY_SEPARATOR, [JANKX_FRAMEWORK_DIRECTORY, 'includes']));
}

define('JANKX_CORE_DIRECTORY', dirname(__FILE__));
