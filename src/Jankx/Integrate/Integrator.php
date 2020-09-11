<?php
/**
 * Jankx Compatible class
 *
 * @package Jankx
 * @subpackage Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @since 1.0.0
 */
namespace Jankx\Integrate;

class Integrator implements \Iterator
{
    protected static $instance;

    protected $currentIndex = 0;
    protected $compatibles  = array();

    public static function getInstance()
    {
        // Only run after the plugins are loaded
        if (!did_action('plugins_loaded')) {
            return;
        }
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
    }

    // Iterator implementation
    public function key()
    {
    }

    public function current()
    {
    }

    public function valid()
    {
        return false;
    }

    public function next()
    {
    }

    public function rewind()
    {
        $this->currentIndex = 0;
        $this->compatibles  = array();

        $this->deletePlugins();
    }
    // End Iterator

    protected function deletePlugins()
    {
    }

    public function integrate()
    {
        foreach ($this as $compatibleObject) {
        }
    }
}
