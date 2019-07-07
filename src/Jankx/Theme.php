<?php
namespace Jankx;

class Theme
{
    protected static $instance;
    protected $theme;

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->theme = wp_get_theme(get_stylesheet());
    }

    public function __get($name)
    {
        $value = call_user_func(
            array(
                isset($this->originalTemplate) ? $this->originalTemplate : $this->theme,
                'get'
            ),
            ucfirst($name)
        );
        if (isset($this->originalTemplate)) {
            unset($this->originalTemplate);
        }
        return $value;
    }

    public function __call($name, $arguments)
    {
        $value = call_user_func(
            array(
                isset($this->originalTemplate) ? $this->originalTemplate : $this->theme,
                'get'
            ),
            ucfirst($name)
        );
        if (isset($this->originalTemplate)) {
            unset($this->originalTemplate);
        }
        return $value;
    }


    public function getTemplate()
    {
        if (is_child_theme()) {
            $stylesheet = basename(get_template_directory());
        } else {
            $stylesheet = get_stylesheet();
        }
        $this->originalTemplate = wp_get_theme($stylesheet);
        return $this;
    }

    public function getInstance()
    {
        return isset($this->originalTemplate) ? $this->originalTemplate : $this->theme;
    }
}
