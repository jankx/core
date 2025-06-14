<?php

namespace Jankx\Extra\Colors;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

class BrandColorValue
{
    protected $id;
    protected $value;

    protected $textAppearance = 'light';

    protected $borderColor;

    protected $appearance;

    public function __construct($id, $value = null, $appearance = null)
    {
        $this->id = $id;
        $this->value = $value;
        if (in_array($appearance, [null, 'light', 'dark'])) {
            $this->appearance = $appearance;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }
    public function getValue(): string
    {
        return $this->value;
    }

    public function setAppearance($appearance)
    {
        if (in_array($appearance, ['light', 'dark', null])) {
            $this->appearance = $appearance;
        }
        return $this;
    }
    public function getAppearance()
    {
        return $this->appearance;
    }

    public function setTextAppearance($appearance)
    {
        if (in_array($appearance, ['light', 'dark', null])) {
            $this->textAppearance = $appearance;
        }
        return $this;
    }
    public function getTextAppearance()
    {
        return $this->textAppearance;
    }

    public function __tostring()
    {
        return $this->getValue();
    }
}
