<?php

namespace Jankx\Configs;

class ThemeConfigurations
{
    private $templateName;

    private $layouts = [];


    // Getters
    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function getLayouts($layouts)
    {
        return $this->layouts;
    }

    public function __get($name)
    {
        $property = str_replace('get', '', $name);
        $property = preg_replace_callback('/^[\w]/', function ($c) {
            var_dump($c);
        }, $property);
    }

    // Setters
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    public function setLayouts($layouts)
    {
        $this->layouts = $layouts;
    }
}
