<?php
namespace Jankx;

use Icon_Picker;
use Icon_Picker_Type_Font;
use Jankx\IconFonts\IconPickerType;

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
        add_action('init', array($this, 'loadFontFeatures'), 30);
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

        $font_handle = $font_name . '-font';
        if (!isset($instance->fonts[$font_handle])) {
            $instance->fonts[$font_handle] = array(
                'path' => $font_css_path,
                'font-family' => $font_family,
                'version' => $version,
                'name' => $display_name,
            );

            do_action_ref_array(
                'jankx/icon/fonts/new',
                array( $font_name, $font_css_path, $display_name, $font_family, $version )
            );
        }
    }

    public function getFonts()
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
            array_keys($this->getFonts())
        );
    }

    public function register_admin_fonts()
    {
        foreach ($this->getFonts() as $font_handle => $args) {
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
        foreach ($this->getFonts() as $css_handle => $args) {
            css(
                $css_handle,
                jankx_get_path_url($args['path']),
                array(),
                $args['version'],
                'all',
                true // Preload webfont
            )->enqueue();
        }
    }


    public function loadFontFeatures()
    {
        if (!empty($this->fonts)) {
            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'register_admin_fonts'));
            } else {
                add_action('jankx_asset_css_dependences', array($this, 'addIconFontAsDeps'));
            }
            do_action('jankx/icons/feature/init', $this->fonts);

            add_action('icon_picker_default_types', [$this, 'integrateWithMenuIcons']);
            add_action('icon_picker_default_types', [$this, 'registerFontIconForIconPicker'], 20);
        }
    }

    public function integrateWithMenuIcons($iconsOrSections)
    {
        if (!empty($this->fonts)) {
            foreach ($this->fonts as $font) {
                $fontId   = array_get($font, 'font-family', 'noname');
                $fontName = array_get($font, 'name', __('No Name', 'jankx'));

                // Check is not menu icon picker
                if (!isset($iconsOrSections['global']['fields'])) {
                    $iconsOrSections[$fontId] = $fontName;
                    continue;
                }
                $iconsOrSections['global']['fields'][0]['choices'][$fontId] = $fontName;
            }
        }

        return $iconsOrSections;
    }

    public function registerFontIconForIconPicker($fonts)
    {
        if (class_exists(Icon_Picker_Type_Font::class) && !empty($this->fonts)) {
            foreach ($this->fonts as $font) {
                $icon_picker = Icon_Picker::instance();
                $iconPickerType = new IconPickerType();
                $iconPickerType->set_font_data($font);

                $icon_picker->registry->add($iconPickerType);
            }
        }
        return $fonts;
    }
}
