<?php

namespace Jankx\Configs;

use Jankx;

class PostTypeConfigurations
{
    private $type;
    private $slug;

    private $name;

    private $singularName;

    private $options = [];

    private $metas = [];


    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }


    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSingularName()
    {
        if (empty($this->singularName)) {
            return $this->getName();
        }
        return $this->singularName;
    }

    public function setSingularName($singularName)
    {
        $this->singularName = $singularName;
    }

    public function getOptions()
    {
        if (is_array($this->options)) {
            $this->options = [];
        }

        $this->options['slug']   = $this->getSlug();
        $this->options['label']  = __($this->getName(), Jankx::getTextDomain());
        $this->options['labels'] = [
            'name' => __($this->getName(), Jankx::getTextDomain()),
            'singular_name' => __($this->getSingularName(), Jankx::getTextDomain()),
            'add_new' => __('Add New ' . $this->getSingularName(), Jankx::getTextDomain()),
            'add_new_item' => __('Add New ' . $this->getSingularName(), Jankx::getTextDomain()),
            'edit_item' => __('Edit ' . $this->getSingularName(), Jankx::getTextDomain()),
            'all_items' => __('All ' . $this->getSingularName(), Jankx::getTextDomain()),
        ];

        // apply with default options
        return array_merge(
            [
                'public' => true,
            ],
            $this->options
        );
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }


    public function getMetas()
    {
        return $this->metas;
    }

    public function setMetas($metas)
    {
        $this->metas = $metas;
    }
}
