<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\TemplateEngine\Engine;
use Jankx\Widget\Constracts\Renderer;
use Jankx\TemplateAndLayout;

abstract class Base implements Renderer
{
    protected $templateEngine;

    protected $options = array();
    protected $layoutOptions = array();

    public function __toString()
    {
        return (string) $this->render();
    }

    public function setOption($optionName, $optionValue)
    {
        $this->options[$optionName] = $optionValue;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    public function setOptions($options)
    {
        if (is_array($options)) {
            foreach ($options as $key => $val) {
                $method = preg_replace_callback(array('/^([a-z])/', '/[_|-]([a-z])/', '/.+/'), function ($matches) {
                    if (isset($matches[1])) {
                        return strtoupper($matches[1]);
                    }
                    return sprintf('set%s', $matches[0]);
                }, $key);

                if (method_exists($this, $method)) {
                    $this->$method($val);
                } else {
                    $this->setOption($key, $val);
                }
            }
        }
        return $this;
    }
    public function addOption($optionName, $optionValue)
    {
        $this->options[$optionName] = $optionValue;
    }

    public function getOption($name, $defaultValue = null)
    {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        return $defaultValue;
    }

    public function addLayoutOption($optionName, $optionValue)
    {
        $this->layoutOptions[$optionName] = $optionValue;
    }

    public function setLayoutOptions($options)
    {
        if (!is_array($options)) {
            return;
        }

        foreach ($options as $optionName => $optionValue) {
            $this->addLayoutOption($optionName, $optionValue);
        }
    }

    public function getLayoutOptions()
    {
        return $this->layoutOptions;
    }

    public function getLayoutOption($name, $defaultValue = null)
    {
        if (isset($this->layoutOptions[$name])) {
            return $this->layoutOptions[$name];
        }
        return $defaultValue;
    }

    public static function prepare($args, $renderer = null)
    {
        if (is_null($renderer) || !is_a($renderer, Renderer::class)) {
            $renderer = new static();
        }
        return $renderer->setOptions($args);
    }

    public function setTemplateEngine($templateEngine)
    {
        if (is_a($templateEngine, Engine::class)) {
            $this->templateEngine = &$templateEngine;
        }
    }

    public function loadTemplate($templateName, $data = array(), $echo = false)
    {
        if (is_null($this->templateEngine)) {
            $this->templateEngine = TemplateAndLayout::getTemplateEngine();
        }
        return $this->templateEngine->render($templateName, $data, $echo);
    }
}
