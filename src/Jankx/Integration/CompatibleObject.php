<?php
namespace Jankx\Integration;

class CompatibleObject
{
    protected $type;
    protected $path;
    protected $integrator;

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Set compatible type
     *
     * @param string $type Kind of object, default support `plugin` and `theme`
     * @return \Jankx\Integration\CompatibleObject
     */
    public function setType($type = 'plugin')
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the path of object
     *
     * @param string $path The path of object
     * @return \Jankx\Integration\CompatibleObject
     */
    public function setPath($path = '')
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set integrator class
     *
     * @param string $integrator
     * @return \Jankx\Integration\CompatibleObject
     */
    public function setIntegrator($integrator)
    {
        $this->integrator = $integrator;
        return $this;
    }

    public static function build($args = array())
    {
        $object = new static();
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                $method = sprintf('set%s', preg_replace_callback(array('/(^[a-z])/', '/_([a-z])/'), function ($matches) {
                    if (isset($matches[1])) {
                        return strtoupper($matches[1]);
                    }
                }, $key));
                if (method_exists($object, $method)) {
                    $object->$method($val);
                }
            }
        }
        return $object;
    }
}
