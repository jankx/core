<?php
namespace Jankx\Integrate;

class CompatibleObject
{
    protected $type;
    protected $path;

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
     * @return \Jankx\CompatibleObject
     */
    public function setType($type = 'plugin')
    {
        return $this;
    }

    /**
     * Set the path of object
     *
     * @param string $path The path of object
     * @return \Jankx\CompatibleObject;
     */
    public function setPath($path = '')
    {
        return $this;
    }

    public static function build($args = array())
    {
        $object = new static();
        if (is_array($args)) {
        }
        return $object;
    }
}
