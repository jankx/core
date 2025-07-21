<?php

namespace Jankx;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

/**
 * This is the main class or the main gate to a developer
 * can use it to run all features of the framework.
 *
 * PHP version 5.4 or later
 *
 * @category Jankx
 * @package  Core
 * @author   Puleeno Nguyen <puleeno@gmail.com>
 * @license  MIT (https:///opensource.org/licenses/MIT)
 * @link     https://github.com/jankx/core
 */

use Illuminate\Container\Container;

/**
 * Class Jankx
 *
 * Lớp chính của framework Jankx, cung cấp các chức năng cốt lõi và quản lý các thành phần của framework.
 *
 * @package Jankx
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @version 1.0.0.48
 * @license MIT
 *
 */

class Jankx extends Container
{
    /**
     * Tên của framework
     */
    const FRAMEWORK_NAME    = 'Jankx Framework';

    /**
     * Phiên bản hiện tại của framework
     */
    const FRAMEWORK_VERSION = '1.0.0.48';
}
