<?php
namespace Jankx;

use Jankx;

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
        add_action('wp_prepare_themes_for_js', function ($prepared_themes) {
            if (apply_filters('jankx_custom_theme', false)) {
                foreach ($prepared_themes as $index => $prepared_theme) {
                    if ($prepared_theme['name'] !== Jankx::FRAMEWORK_NAME) {
                        continue;
                    }
                    $prepared_themes[$index] = apply_filters(
                        "jankx_custom_theme_info",
                        $prepared_theme
                    );
                }
            }
            return $prepared_themes;
        });
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
        $this->originalTemplate = $this->theme->parent;
        return $this;
    }

    public function getInstance()
    {
        return isset($this->originalTemplate) ? $this->originalTemplate : $this->theme;
    }
}
