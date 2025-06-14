<?php

namespace Jankx\Extra;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Extra\Colors\BrandColor;
use Jankx\Extra\Colors\BrandColorValue;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class BrandColors
{
    public static $brandColors = [];


    public static function addColor(BrandColor $color)
    {
        if (!is_null($color)) {
            static::$brandColors[$color->getBrandName()] = $color;
        }
    }


    // lazyload method.
    protected static function loadBrandColors()
    {
        $brandData = implode(DIRECTORY_SEPARATOR, [dirname(JANKX_FRAMEWORK_FILE_LOADER), 'assets', 'resources', 'brandcolors.json']);
        if (!file_exists($brandData)) {
            return;
        }
        $data = json_decode(file_get_contents($brandData), true);
        if (!(is_array($data) &&  count($data) > 0)) {
            return;
        }
        // aliasName => destName
        $aliasColors = [
            'fbButton' => 'facebook',
            'fbFeed' => 'facebook'
        ];

        foreach ($data as $brandName => $colorData) {
            if (isset($colorData['alias'])) {
                $aliasColors[$brandName] = $colorData['alias'];
                continue;
            }
            $brandColor = new BrandColor($brandName);
            $defaultAppearance = array_get($colorData, 'appearance', false);
            $defaultTextAppearance = array_get($colorData, 'text_appearance', null);
            if ($defaultAppearance) {
                $brandColor->setAppearance($defaultAppearance);
            }
            $colors = array_get($colorData, 'colors', []);
            if (is_string($colors)) {
                $colors = [
                    'primary' => [
                        'color' => $colors,
                        'appearance' => $defaultAppearance,
                        'text_appearance' => $defaultTextAppearance
                    ]
                ];
            }

            foreach ($colors as $colorId => $colorValue) {
                $brandColorValue = new BrandColorValue($colorId);
                if (is_string($colorValue)) {
                    $colorValue = [
                        'color' => $colorValue
                    ];
                }
                $brandColorValue->setValue(array_get($colorValue, 'color'));
                $brandColorValue->setAppearance(array_get($colorValue, 'appearance', $defaultAppearance));
                $brandColorValue->setTextAppearance(array_get($colorValue, 'text_appearance', 'light'));

                // value
                $brandColor->addColorValue($colorId, $brandColorValue);
            }

            static::$brandColors[$brandColor->getBrandName()] = $brandColor;
        }

        if (count($aliasColors) > 0) {
            foreach ($aliasColors as $brandColor => $source) {
                if (!isset(static::$brandColors[$source])) {
                    continue;
                }
                static::$brandColors[$brandColor] = &static::$brandColors[$source];
            }
        }
    }

    public static function getBrandColors()
    {
        if (empty(static::$brandColors)) {
            static::loadBrandColors();
        }
        return static::$brandColors;
    }

    public static function getBrandColorByName($name): ?BrandColor
    {
        if (empty(static::$brandColors)) {
            static::loadBrandColors();
        }
        if (isset(static::$brandColors[$name])) {
            return static::$brandColors[$name];
        }
        return null;
    }
}
