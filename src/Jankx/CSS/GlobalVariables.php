<?php

namespace Jankx\CSS;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\GlobalConfigs;

class GlobalVariables
{
    protected static $variables;

    protected static function findBodyFontFamily($defaultFont)
    {
        $fontFamilies = GlobalConfigs::get('settings.typography.fontFamilies', []);
        if (count($fontFamilies) > 0) {
            foreach ($fontFamilies as $fontFamily) {
                if (array_get($fontFamily, 'slug') === 'body') {
                    return array_get($fontFamily, 'fontFamily');
                }
            }
        }
        return $defaultFont;
    }

    protected static function getDefaultVariables()
    {
        return array(
            'typography-primary-font' => self::findBodyFontFamily('Arial, Verdana, Tahoma, sans-serif'),
            'typography-global-font-size' => "15px",
            'typography-global-line-height' => "24px",
            'typography-secondary-font' => "Arial, sans-serif",
            'typography-text-font' => "Arial, sans-serif",
            'typography-accent-font' => "Arial, sans-serif",
            'typography-menu-font' => "Arial, sans-serif",
            'typography-default-font' => "Arial, sans-serif",
            'placeholder-text-color' => '#767676',
            'primary-color' => '#0C101B',
            'secondary-color' => '#464646',
            'border-color' => '#dddddd',
            'text-color' => '#464646',
            'text-success-color' => '#155724',
            'bg-success-color' => '#d4edda',
            'social-icon-size' => '36px',
        );
    }

    public static function init()
    {
        if (is_null(static::$variables)) {
            static::$variables = apply_filters(
                'jankx/css/variables',
                static::getDefaultVariables()
            );
        }
        add_action('wp_head', array(__CLASS__, 'defineGlobalVariables'), 5);
    }

    protected static function writeValue($value, $variableName = null)
    {
        return $value;
    }

    public static function defineGlobalVariables()
    {
        $globalVariables = static::$variables;
        ?>
        <!-- Jankx Global CSS Variables -->
        <style>
            :root {
                <?php
                foreach ($globalVariables as $globalVariable => $value) {
                    printf("--%s: %s;\n", $globalVariable, static::writeValue($value, $globalVariable));
                }
                ?>
            }
        </style>
        <!-- / Global CSS Variables. -->
        <?php
    }
}
