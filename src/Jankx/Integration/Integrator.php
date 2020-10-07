<?php
/**
 * Jankx Compatible class
 *
 * @package Jankx
 * @subpackage Core
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @since 1.0.0
 */
namespace Jankx\Integration;

use Jankx\Integration\CompatibleObject;
use Jankx\Integration\Elementor\Elementor;
use Jankx\Integration\Constract;

class Integrator implements \Iterator
{
    protected static $instance;

    protected $currentIndex = 0;
    protected $compatibles  = array();

    // Iterator implementation
    public function key()
    {
        return $this->currentIndex;
    }

    public function current()
    {
        return $this->compatibles[$this->currentIndex];
    }

    public function valid()
    {
        return isset($this->compatibles[$this->currentIndex]);
    }

    public function next()
    {
        $this->currentIndex += 1;
    }

    public function rewind()
    {
        $this->currentIndex = 0;
        $this->compatibles  = array();

        $this->detectPlugins();
    }
    // End Iterator

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

    protected function detectPlugins()
    {
        $activePlugins = get_option('active_plugins');
        if (($index = array_search('elementor/elementor.php', $activePlugins)) !== false) {
            array_push($this->compatibles, CompatibleObject::build(array(
                'type' => 'plugin',
                'path' => $activePlugins[$index],
                'integrator' => Elementor::class,
            )));
        }
    }

    public function integrate()
    {
        foreach ($this as $compatibleObject) {
            $integratorCls = $compatibleObject->integrator;
            if (empty($integratorCls)) {
                continue;
            }
            $integrator    = new $integratorCls();
            if (is_a($integrator, Constract::class)) {
                $integrator->integrate();
            }
        }
    }
}
