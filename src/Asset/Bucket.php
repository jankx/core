<?php

namespace Jankx\Asset;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Exception;

class Bucket
{
    protected static $instance;
    protected static $lastHandle;
    protected static $lastHandleIsCss;

    public $headerScripts = [];
    public $headerStyles = [];
    public $stylesheets = [];
    public $initFooterScripts = [];
    public $footerScripts = [];
    public $executeFooterScripts = [];

    public $enqueueCSS = [];
    public $enqueueJS = [];

    /**
     * @return static
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        // Reset lastHandle;
        static::$lastHandle = '';
        return self::$instance;
    }

    /**
     * @return \Jankx\Asset\Bucket
     */
    public function css($handle, $cssUrl = null, $dependences = [], $version = null, $media = 'all', $preload = false)
    {
        if (!empty($cssUrl)) {
            $cssItem = new CssItem($handle, $cssUrl, $dependences, $version, $media, $preload);
            $this->stylesheets[$handle] = $cssItem;
            static::$lastHandle = $handle;
        } elseif ($this->isRegistered($handle)) {
            $this->enqueueCSS[] = $handle;
        } else {
            // Log CSS error
        }
        static::$lastHandleIsCss = true;

        return $this;
    }

    public function style($cssContent, $media = 'all')
    {
        $this->headerStyles[$media][] = $cssContent;

        return $this;
    }

    /**
     * @param string $handle the handler ID
     *
     * @return self
     */
    public function js($handle, $jsUrl = null, $dependences = [], $version = null, $isFooterScript = true, $preload = false)
    {
        if (!empty($jsUrl)) {
            $jsItem = new JsItem($handle, $jsUrl, $dependences, $version, $isFooterScript, $preload);
            $this->footerScripts[$handle] = $jsItem;

            static::$lastHandle = $handle;
        } elseif ($this->isRegistered($handle, false)) {
            $this->enqueueJS[] = $handle;
        } else {
            // Log JS error
        }
        static::$lastHandleIsCss = false;

        return $this;
    }

    public function script($jsContent, $isHeaderScript = false)
    {
        if ($isHeaderScript) {
            $this->headerScripts[] = $jsContent;
        } else {
            $this->initFooterScripts[] = $jsContent;
        }

        return $this;
    }

    public function executeScript($jsContent, $autoWrapByScriptTag = false)
    {
        if ($autoWrapByScriptTag) {
            $jsContent = '<script>' . $jsContent . '</script>';
        }
        $this->executeFooterScripts[] = $jsContent;

        return $this;
    }

    public function getHeaderScripts()
    {
        return $this->headerScripts;
    }

    public function getStyles()
    {
        return $this->headerStyles;
    }

    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    public function getStylesheet($handle)
    {
        if (isset($this->stylesheets[$handle])) {
            return $this->stylesheets[$handle];
        }
    }

    public function getJavascript($handle)
    {
        if (isset($this->footerScripts[$handle])) {
            return $this->footerScripts[$handle];
        }
    }

    public function getInitFooterScripts()
    {
        return $this->initFooterScripts;
    }

    public function getFooterScipts()
    {
        return $this->footerScripts;
    }

    public function getExcuteFooterScripts()
    {
        return $this->executeFooterScripts;
    }

    public function getEnqueueCss()
    {
        return $this->enqueueCSS;
    }

    public function getEnqueueJs()
    {
        return $this->enqueueJS;
    }

    /**
     * @return boolean
     */
    public function isRegistered($handle, $isStylesheet = true)
    {
        /**
         * Get all handle keys
         */
        $handles = $isStylesheet ?
            array_keys($this->stylesheets) :
            array_keys($this->footerScripts);

        return in_array($handle, $handles, true);
    }

    /**
     * @return self
     */
    public function localize($object_name, $i10n, $handle = null)
    {
        if (is_null($handle)) {
            if (empty(static::$lastHandle)) {
                throw new Exception('JS handle must be have a value');
            }
            $handle = static::$lastHandle;
        }

        $jsItem = $this->getJavascript($handle);
        if ($jsItem) {
            $jsItem->addLocalizeScript($object_name, $i10n);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function enqueue()
    {
        if (!static::$lastHandle) {
            return;
        }

        if (static::$lastHandleIsCss) {
            array_push($this->enqueueCSS, static::$lastHandle);
        } else {
            array_push($this->enqueueJS, static::$lastHandle);
        }
        static::$lastHandle = null;
        static::$lastHandleIsCss = null;

        return $this;
    }
}
