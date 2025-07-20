<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

abstract class AssetItem implements AssetInterface
{
    protected $hasDependences = false;
    protected $dependences = [];
    protected $id;

    protected $url = '';
    protected $minUrl = '';

    protected $version = null;
    protected $preload = false;

    public function __construct($id, $url, $dependences, $version, $preload)
    {
        $this->id = $id;
        $this->dependences = $dependences;
        $this->version = $version;
        $this->preload = $preload;

        $this->setUrl($url);

        if ($dependences) {
            $this->hasDependences = true;
        }
    }

    public function hasDependences()
    {
        return $this->hasDependences;
    }

    public function getDependences()
    {
        return $this->dependences;
    }

    /**
     * @param string|array $url Accept string URL or array
     *
     * @return string
     */
    public function setUrl($url)
    {
        if (is_array($url) && !empty($url)) {
            $keys = array_keys($url);
            $keys = array_map(function ($key) {
                return strtolower(preg_replace('/[^0-9a-zA-z]/', '', $key));
            }, $keys);
            $values = array_values($url);
            $arr = array_combine($keys, $values);
            if (isset($arr['urlmin'])) {
                $this->minUrl = $arr['urlmin'];
            }
            $this->url = isset($arr['url']) ? $arr['url'] : $values[0];

            // Clean variables
            unset($url, $arr, $values, $keys);
        } else {
            $this->url = $url;
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (AssetManager::isLoadMinifyAsset() && !empty($this->minUrl)) {
            return $this->minUrl;
        }
        return $this->url;
    }
}
