<?php

namespace Jankx\Configs;

use Jankx;

class ThemeConfigurations
{
    protected $name;
    protected $shortName;

    protected $version;

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

    public function getVersion()
    {
        if (is_null($this->version)) {
            return Jankx::FRAMEWORK_VERSION;
        }
        return $this->version;
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

    public function setVersion($version)
    {
        $this->version = $version;
    }
}
