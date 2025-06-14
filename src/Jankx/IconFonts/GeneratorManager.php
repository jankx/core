<?php

namespace Jankx\IconFonts;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\IconFonts\Generator\Fontastic;

class GeneratorManager
{
    protected static $generators;

    public static function loadGenerators()
    {
        $generator_classes = apply_filters('jankx_megu_icon_font_generators', array(
            Fontastic::class,
        ));
        foreach ($generator_classes as $generator_class) {
            $generator = new $generator_class();
            if (!is_a($generator, FontIconGenerator::class)) {
                continue;
            }
            static::$generators[] = $generator;
        }
    }

    public static function getGenerators()
    {
        if (is_null(static::$generators)) {
            static::loadGenerators();
        }
        return static::$generators;
    }

    /**
     * @return \Jankx\IconFonts\FontIconGenerator|null
     */
    public static function detectGenerator($font_name, $path, $font_family, $version = null)
    {
        $generators = static::getGenerators();

        foreach ($generators as $generator) {
            $generator->setFontPath($path);
            $generator->setFontName($font_name);
            $generator->setFontFamily($font_family);
            $generator->setVersion($version);

            if ($generator->isMatched()) {
                return $generator;
            }
        }
    }
}
