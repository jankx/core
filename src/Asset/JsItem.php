<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class JsItem extends AssetItem
{
    protected $isRegistered = false;
    protected $localizeScripts = array();

    public $isFooterScript  = true;
    public $preload         = false;

    public function __construct($id, $url, $dependences = array(), $version = null, $isFooterScript = true, $preload = false)
    {
        parent::__construct(
            $id,
            $url,
            $dependences,
            $version,
            $preload
        );
        $this->isFooterScript = $isFooterScript;
    }

    public function call()
    {
        if ($this->isRegistered) {
            wp_enqueue_script($this->id);
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
            add_filter('script_loader_tag', array($this, 'createPreloadScript'), 10, 3);
        }

        $status = wp_register_script(
            $this->id,
            $this->getUrl(),
            $this->dependences,
            $this->version,
            $this->isFooterScript
        );
        if (!$status) {
            error_log(sprintf('Register script %s is error', $this->id));
            return;
        }

        foreach ($this->localizeScripts as $object_name => $i10n) {
            wp_localize_script($this->id, $object_name, $i10n);
        }
    }

    public function createPreloadScript($tag, $handle, $src)
    {
        if ($handle !== $this->id) {
            return $tag;
        }

        $tag = preg_replace(
            '/^(<[^ ]+)/',
            '$1 rel="reload" as="script"',
            $tag
        );
        if ($this->preload) {
            remove_filter('script_loader_tag', array($this, 'createPreloadScript'), 10);
        }
        return $tag;
    }

    public function addLocalizeScript($object_name, $i10n)
    {
        $this->localizeScripts[$object_name] = $i10n;
    }
}
