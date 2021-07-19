<?php
namespace Jankx;

class IconFonts
{
    protected static $instance;

    protected $fonts = array();

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', array($this, 'register_admin_fonts'));
        } else {
            add_action('jankx_asset_css_dependences', array($this, 'addIconFontAsDeps'));
        }
    }

    /**
     * Register new font icon
     *
     * Note: Please call this static method before hook `wp_enqueue_scripts` to get a best result.
     */
    public static function add($font_name, $font_css_path, $version = null, $display_name = null, $font_family = null)
    {
        if (!file_exists($font_css_path)) {
            error_log(sprintf('Icon font "%s" is not found: %s', $font_name, $font_css_path));
            return;
        }

        $instance = static::getInstance();
        if (is_null($font_family)) {
            $font_family = $font_name;
        }

        if (!isset($instance->fonts[$font_name])) {
            $instance->fonts[$font_name] = array(
                'path' => $font_css_path,
                'version' => $version,
                'name' => $display_name,
            );

            do_action(
                'jankx/icon/fonts/new',
                $font_name . '-font',
                $font_css_path,
                $display_name,
                $font_family,
                $version
            );
        }
    }

    public function get_fonts()
    {
        return apply_filters(
            'jankx/icon/fonts',
            $this->fonts
        );
    }

    public function addIconFontAsDeps($deps)
    {
        return array_merge(
            $deps,
            array_keys($this->get_fonts())
        );
    }

    public function register_admin_fonts()
    {
        foreach ($this->get_fonts() as $font_handle => $args) {
            wp_register_style(
                $font_handle,
                jankx_get_path_url($args['path']),
                array(),
                $args['version']
            );

            // Call fonts
            wp_enqueue_style($font_handle);
        }
    }

    public function register_scripts()
    {
        foreach ($this->get_fonts() as $css_handle => $args) {
            css(
                $css_handle,
                jankx_get_path_url($args['path']),
                array(),
                $args['version']
            );
        }
    }
}
