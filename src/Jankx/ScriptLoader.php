<?php

namespace Jankx;

use Jankx;

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
                    'url' => [
                        'url' => jankx_core_asset_url('libs/popperjs/popper.js'),
                        'url.min' => jankx_core_asset_url('libs/popperjs/popper.min.js')
                    ],
                    'version' => '2.9.1',
                ),
                'slideout' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/slideout/slideout.js'),
                        'url.min' => jankx_core_asset_url('libs/slideout/slideout.min.js'),
                    ],
                    'version' => '1.0.1',
                ),
                'micromodal' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/micromodal/micromodal.js'),
                        'url.min' => jankx_core_asset_url('libs/micromodal/micromodal.min.js'),
                    ],
                    'version' => '0.4.6',
                ),
                'choices' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/Choices/scripts/choices.js'),
                        'url.min' => jankx_core_asset_url('libs/Choices/scripts/choices.min.js')
                    ],
                    'version' => '9.0.1',
                ),
                'sharing' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/vanilla-sharing/vanilla-sharing.umd.js'),
                        'url.min' => jankx_core_asset_url('libs/vanilla-sharing/vanilla-sharing.min.js'),
                    ],
                    'version' => '6.0.5',
                ),
                'tim' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/tim/tinytim.js'),
                        'url.min' => jankx_core_asset_url('libs/tim/tinytim.min.js'),
                    ],
                    'version' => '1.0.0'
                ),
                'ispin' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/ispinjs-2.0.1/js/ispin.js'),
                        'url.min' => jankx_core_asset_url('libs/ispinjs-2.0.1/js/ispin.min.js'),
                    ],
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
                    'url' => [
                        'url' => jankx_core_asset_url('css/jankx.css'),
                        'url.min' => jankx_core_asset_url('css/jankx.min.css')
                    ],
                    'version' => $this->revisionVersion,
                ),
                'choices' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/Choices/styles/choices.css'),
                        'url.min' => jankx_core_asset_url('libs/Choices/styles/choices.min.css')
                    ],
                    'version' => '9.0.1',
                ),
                'ispin' => array(
                    'url' => [
                        'url' => jankx_core_asset_url('libs/ispinjs-2.0.1/css/ispin.css'),
                        'url.min' => jankx_core_asset_url('libs/ispinjs-2.0.1/css/ispin.min.css'),
                    ],
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

            init_script(sprintf(
                '<script>%s</script>',
                $templateJsFunc
            ), true);
        }
    }
}
