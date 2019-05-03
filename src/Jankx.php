<?php
/**
 * This is the main class or the main gate to a developer
 * can use it to run all features of the framework.
 *
 * PHP version 5.4 or later
 *
 * @category Jankx
 * @package  Core
 * @author   Puleeno Nguyen <puleeno@gmail.com>
 * @license  MIT (https://opensource.org/licenses/MIT)
 * @link     https://github.com/jankx/core
 */

/**
 * This class is middle-class interaction between developer and other classes
 */
class Jankx
{
    protected static $instance;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __call($method, $args)
    {
    }

    public static function __callStatic($method, $args)
    {
    }
}
