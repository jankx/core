<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class CssItem extends AssetItem
{
    protected $isRegistered = false;
    public $media           = 'all';
    public $preload         = false;

    public function __construct($id, $url, $dependences = array(), $version = null, $media = 'all', $preload = false)
    {
        parent::__construct(
            $id,
            $url,
            $dependences,
            $version,
            $preload
        );
        $this->media = $media;
    }

    public function call()
    {
        if ($this->isRegistered) {
            wp_enqueue_style($this->id);
        } else {
            // Log error css is not registered
        }
    }

    public function register()
    {
        if ($this->isRegistered) {
            return;
        }
        $this->isRegistered = true;

        if ($this->preload) {
            add_filter('style_loader_tag', array($this, 'createLinkPreload'), 10, 4);
        }
        return wp_register_style(
            $this->id,
            $this->getUrl(),
            $this->dependences,
            $this->version,
            $this->media
        );
    }

    public function createLinkPreload($tag, $handle, $href, $media)
    {
        if ($handle !== $this->id) {
            return $tag;
        }
        if ($this->preload) {
            $preload_tag = preg_replace(
                '/^(<[^ ]+ rel=)(\'|\")(\w+)(\2) (id=\'[^\']+\') {1,}(href=\'[^\']+\')/',
                '$1$2preload$4 $6 as=$2style$4',
                $tag
            );

            return sprintf("%s%s", $preload_tag, $tag);
            // Remove filter after replace the link
            remove_filter('style_loader_tag', array($this, 'createLinkPreload'), 10);
        }
        return $tag;
    }
}
