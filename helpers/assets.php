<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
if (!function_exists('asset')) {
    function asset()
    {
        return \Jankx\Asset\Bucket::instance();
    }
}

if (!function_exists('css')) {
    /**
     * @return Jankx\Asset\Bucket;
     */
    function css($handler, $cssUrl = null, $dependences = [], $version = null, $media = 'all', $preload = false)
    {
        return call_user_func(
            array(asset(), 'css'),
            $handler,
            $cssUrl,
            $dependences,
            $version,
            $media,
            $preload
        );
    }
}

if (!function_exists('js')) {
    function js($handler, $jsUrl = null, $dependences = [], $version = null, $isFooterScript = true, $preload = false)
    {
        return call_user_func(
            array(asset(), 'js'),
            $handler,
            $jsUrl,
            $dependences,
            $version,
            $isFooterScript,
            $preload
        );
    }
}

if (!function_exists('style')) {
    function style($cssContent, $media = 'all')
    {
        return call_user_func(
            array(asset(), 'style'),
            $cssContent,
            $media
        );
    }
}

if (!function_exists('init_script')) {
    function init_script($js, $isHeaderScript = false)
    {
        return call_user_func(
            array(asset(), 'script'),
            $js,
            $isHeaderScript
        );
    }
}

if (!function_exists('execute_script')) {
    function execute_script($jsContent, $autoWrapByScriptTag = false)
    {
        return call_user_func(
            array(asset(), 'executeScript'),
            $jsContent,
            $autoWrapByScriptTag
        );
    }
}

if (!function_exists('is_registered_asset')) {
    function is_registered_asset($handler, $isStylesheet = true)
    {
        return call_user_func(
            array(asset(), 'isRegistered'),
            $handler,
            $isStylesheet
        );
    }
}


if (!function_exists('localize_script')) {
    function localize_script($handler, $object_name, $i10n)
    {
        return call_user_func(
            array(asset(), 'localize'),
            $handler,
            $object_name,
            $i10n
        );
    }
}
