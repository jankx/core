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
        $jankxCssVer = fileatime(sprintf(
            '%s/assets/css/jankx.css',
            dirname(JANKX_FRAMEWORK_FILE_LOADER)
        ));
        $this->revisionVersion = substr(md5($jankxCssVer), 0, 6);
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
                    'url' => jankx_core_asset_url('libs/popperjs/popper.js'),
                    'version' => '2.9.1',
                ),
                'slideout' => array(
                    'url' => jankx_core_asset_url('libs/slideout/slideout.js'),
                    'version' => '1.0.1',
                ),
                'micromodal' => array(
                    'url' => jankx_core_asset_url('libs/micromodal/micromodal.js'),
                    'version' => '0.4.6',
                ),
                'choices' => array(
                    'url' => jankx_core_asset_url('libs/Choices/scripts/choices.js'),
                    'version' => '9.0.1',
                ),
                'fslightbox-basic' => array(
                    'url' => jankx_core_asset_url('libs/fslightbox-basic/fslightbox.js'),
                    'version' => '3.2.3',
                ),
                'sharing' => array(
                    'url' => jankx_core_asset_url('libs/vanilla-sharing/vanilla-sharing.umd.js'),
                    'version' => '6.0.5',
                ),
                'tim' => array(
                    'url' => jankx_core_asset_url('libs/tim/tinytim.js'),
                    'version' => '1.0.0'
                )
            )
        );
    }

    public function appendDefaultCSS($styles)
    {
        $jankxCssVer = fileatime(sprintf(
            '%s/assets/css/jankx.css',
            dirname(JANKX_FRAMEWORK_FILE_LOADER)
        ));

        return array_merge(
            $styles,
            array(
                'jankx-base' => array(
                    'url' => jankx_core_asset_url('css/jankx.css'),
                    'version' => $this->revisionVersion,
                ),
                'choices' => array(
                    'url' => jankx_core_asset_url('libs/Choices/styles/choices.css'),
                    'version' => '9.0.1',
                )
            )
        );
    }

    public function load()
    {
        add_action('wp_enqueue_scripts', array($this, 'registerThemeAssets'), 30);
        add_action('wp_enqueue_scripts', array($this, 'callDefaultAssets'), 50);

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

    public function registerThemeAssets()
    {
        $jankxCssDeps = array('jankx-base');
        $stylesheetName = Jankx::theme()->get_stylesheet();

        if (is_child_theme()) {
            $stylesheetUri = sprintf('%s/style.css', get_template_directory_uri());
            $jankxTemplate = wp_get_theme(Jankx::templateStylesheet());
            $jankxCssDeps[] = $jankxTemplate->get_stylesheet();
            css(
                $jankxTemplate->get_stylesheet(),
                $stylesheetUri,
                array(),
                $jankxTemplate->version
            );
        }

        css(
            $stylesheetName,
            get_stylesheet_uri(),
            apply_filters('jankx_asset_css_dependences', $jankxCssDeps, $stylesheetName),
            Jankx::theme()->version
        );

        $assetDirectory = sprintf('%s/assets', realpath(dirname(JANKX_FRAMEWORK_FILE_LOADER) . '/../../..'));
        $appJsVer = Jankx::theme()->version;
        $appJsName = '';


        if (file_exists($appjs = sprintf('%s/js/app.js', $assetDirectory))) {
            $appJsName = 'app';
            $abspath = constant('ABSPATH');
            if (PHP_OS === 'WINNT') {
                $abspath = str_replace('\\', '/', $abspath);
                $appjs = str_replace('\\', '/', $appjs);
            }
            js(
                $appJsName,
                str_replace($abspath, site_url('/'), $appjs),
                apply_filters('jankx_asset_js_dependences', array('jankx-common', 'scroll-to-smooth')),
                $appJsVer,
                true
            );
        }

        $this->mainStylesheet = apply_filters('jankx_main_stylesheet', $stylesheetName, $jankxCssDeps);
        $this->mainJs         = apply_filters('jankx_main_js', $appJsName);
    }



    public function callDefaultAssets()
    {
        css($this->mainStylesheet);

        if (!empty($this->mainJs)) {
            js($this->mainJs);
        }
    }
}
