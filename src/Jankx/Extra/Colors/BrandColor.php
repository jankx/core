<?php

namespace Jankx\Extra\Colors;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

class BrandColor
{
    protected $brandName;
    protected $appearance = 'light';
    protected BrandColorValue $defaultColor;
/**
     * Summary of colors
     * @var BrandColorValue[]
     */
    protected $colors = [];

    public function __construct($brandName)
    {
        $this->brandName = $brandName;
    }

    public function getBrandName()
    {
        return $this->brandName;
    }

    // Set default appearance for brand name
    public function setAppearance($appearance)
    {
        $this->appearance = $appearance;
    }

    public function addColorValue($name, BrandColorValue $value)
    {
        if (is_null($name)) {
            $name = $value->getId();
        }
        if (empty($value->getAppearance())) {
            $value->setAppearance($this->appearance);
        }
        $this->colors[$name] = $value;
    }


    public function getColors()
    {
        return $this->colors;
    }

    public function getColorById($colorId): ?BrandColorValue
    {
        if (isset($this->colors[$colorId])) {
            return $this->colors[$colorId];
        }
        return null;
    }

    public function getCssBackgroundStyle($colorId = 'primary'): string
    {
        $value = $this->getColorById($colorId);
        if (empty($value)) {
            return '';
        }

        return sprintf('background-color: %s;', $value->getValue());
    }

    public function getCssBorderStyle($colorId = 'primary'): string
    {
        $value = $this->getColorById($colorId);
        if (empty($value)) {
            return '';
        }

        return sprintf(' border-color: %s;', $value->getValue());
    }
}
