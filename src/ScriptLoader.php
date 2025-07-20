<?php

namespace Jankx;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Jankx;

class ScriptLoader
{
    protected $theme;

    protected $mainJs;
    protected $mainStylesheet;

    protected $revisionVersion;

    public function __construct()
    {
        $this->revisionVersion = substr(md5(Jankx::FRAMEWORK_VERSION), 0, 6);
    }

    public function appendDefaultJS($scripts)
    {
        return array_merge(
            $scripts,
            array(
                'modernizr' => array(
                    'url' => jankx_core_asset_url('libs/modernizr-3.7.1.min.js'),
                    'version' => '3.7.1',
                ),
                'scroll-to-smooth' => array(
                    'url' => jankx_core_asset_url('libs/scrollToSmooth/scrolltosmooth.min.js'),
                    'version' => '2.2.1',
                ),
                'popperjs' => array(
                    'url' => jankx_core_asset_url('libs/popperjs/popper.min.js'),
                    'version' => '2.9.1',
                ),
                'slideout' => array(
                    'url' => jankx_core_asset_url('libs/slideout/slideout.min.js'),
                    'version' => '1.0.1',
                ),
                'mmenu-light.polyfills' => array(
                    'url' => jankx_core_asset_url('libs/mmenu-light-3.2.2/mmenu-light.polyfills.js'),
                    'version' => '3.2.2',
                ),
                'mmenu-light' => array(
                    'url' => jankx_core_asset_url('libs/mmenu-light-3.2.2/mmenu-light.js'),
                    'dependences' => ['mmenu-light.polyfills'],
                    'version' => '3.2.2',
                ),
                'micromodal' => array(
                    'url' => jankx_core_asset_url('libs/micromodal/micromodal.min.js'),
                    'version' => '0.4.6',
                ),
                'choices' => array(
                    'url' => jankx_core_asset_url('libs/Choices/scripts/choices.min.js'),
                    'version' => '11.0.6',
                ),
                'sharing' => array(
                    'url' => jankx_core_asset_url('libs/vanilla-sharing/vanilla-sharing.min.js'),
                    'version' => '6.0.5',
                ),
                'tim' => array(
                    'url' => jankx_core_asset_url('libs/tim/tinytim.min.js'),
                    'version' => '1.0.0'
                ),
                'ispin' => array(
                    'url' => jankx_core_asset_url('libs/ispinjs-2.0.1/js/ispin.min.js'),
                    'version' => '2.0.1'
                )
            )
        );
    }

    public function appendDefaultCSS($styles)
    {
        return array_merge(
            $styles,
            array(
                'jankx-base' => array(
                    'url' => jankx_core_asset_url('css/jankx.min.css'),
                    'version' => $this->revisionVersion,
                ),
                'choices' => array(
                    'url' => jankx_core_asset_url('libs/Choices/styles/choices.min.css'),
                    'version' => '11.0.6',
                ),

                'mmenu-light' => array(
                    'url' => jankx_core_asset_url('libs/mmenu-light-3.2.2/mmenu-light.css'),
                    'version' => '3.2.2',
                ),
                'ispin' => array(
                    'url' => jankx_core_asset_url('libs/ispinjs-2.0.1/css/ispin.min.css'),
                    'version' => '2.0.1'
                )
            )
        );
    }

    public function load()
    {
        add_filter('jankx_default_css_resources', array($this, 'appendDefaultCSS'));
        add_filter('jankx_default_js_resources', array($this, 'appendDefaultJS'));

        if (current_theme_supports('render_js_template')) {
            $templateJsFunc = file_get_contents(sprintf(
                '%s/resources/lib/JavaScript-Templates.js',
                jankx_core_asset_directory()
            ));

            // Sử dụng wp_add_inline_script thay vì init_script
            wp_add_inline_script('jankx-common', sprintf('<script>%s</script>', $templateJsFunc));
        }
    }
}
