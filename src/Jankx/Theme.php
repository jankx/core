<?php
namespace Jankx;

class Theme
{
    protected static $instance;
    protected $theme;

    public static function getInstance()
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

    public function template()
    {
        if (is_child_theme()) {
            $stylesheet = basename(get_template_directory());
        } else {
            $stylesheet = get_stylesheet();
        }
        $this->template = wp_get_theme($stylesheet);
        return $this;
    }

    public function __get($name)
    {
        $value = call_user_func(
            array(
                isset($this->template) ? $this->template : $this->theme,
                'get'
            ),
            ucfirst($name)
        );
        if (isset($this->template)) {
            unset($this->template);
        }
        return $value;
    }

    public function __call($name, $arguments)
    {
        $value = call_user_func(
            array(
                isset($this->template) ? $this->template : $this->theme,
                'get'
            ),
            ucfirst($name)
        );
        if (isset($this->template)) {
            unset($this->template);
        }
        return $value;
    }
}
