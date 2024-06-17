<?php

namespace Jankx\Configs;

class ThemeConfigurations
{
    protected $name;
    protected $shortName;

    protected $templateName;

    protected $layouts = [];

    protected $site = [];

    protected $customs = [];


    // Getters
    public function getName()
    {
        return $this->name;
    }

    public function getShortName()
    {
        return $this->shortName;
    }


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
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

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
