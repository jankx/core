<?php
namespace Jankx\Social;

final class Sharing
{
    protected static $_instance;
    protected static $initialized;

    public static function get_instance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    private function __construct()
    {
        add_action('init', array($this, 'init_scripts'));
        add_action('init', array($this, 'init_sharing_info'));
    }

    public function init_scripts()
    {
    }

    public function init_sharing_info()
    {
        static::$initialized = true;
    }

    protected function enabled_socials()
    {
    }

    public function share_buttons($socials = null)
    {
        // When social sharing is not initialized log the error
        if (!static::$initialized) {
            return error_log(__('Jankx social sharing is not initialized yet', 'jankx'));
        }

        if (is_null($socials)) {
            $socials = $this->enabled_socials();
        }
    }
}
