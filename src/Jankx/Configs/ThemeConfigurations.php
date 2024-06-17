<?php

namespace Jankx\Configs;

class ThemeConfigurations
{
    private $templateName;

    private $layouts = [];

    private $site = [];

    private $customs = [];


    // Getters
    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function getLayouts()
    {
        return $this->layouts;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getCustoms()
    {
        return $this->customs;
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

    public function setSite($site)
    {
        $this->site = $site;
    }

    public function setCustoms($customs)
    {
        $this->customs = $customs;
    }
}
