<?php

namespace Jankx\Asset;

class CustomizableAsset
{
    protected static $engine;

    public static function getEngine()
    {
        if (is_null(self::$engine)) {
            $engine = Engine::create('jankx_asset');

            $engine->setDefaultTemplateDir(sprintf('%s/assets', dirname(JANKX_FRAMEWORK_FILE_LOADER)));
            $engine->setDirectoryInTheme('assets');
            $engine->setupEnvironment();

            do_action_ref_array("jankx_template_engine_{$engine->getName()}_init", array(
                &$engine
            ));

            static::$engine = &$engine;
        }
        return self::$engine;
    }

    public static function loadCustomize($file, $data = array())
    {
        return call_user_func_array(
            array(self::getEngine(), 'render'),
            array($file, $data, false)
        );
    }
}
