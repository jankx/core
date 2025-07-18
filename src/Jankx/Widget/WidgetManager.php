<?php

namespace Jankx\Widget;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Adapter\Options\Framework;
use Jankx\Widget\Widgets\Posts;
use Jankx\Widget\Widgets\Facebook\PagePlugin as FacebookPagePlugin;
use Jankx\Widget\Widgets\CollapaseNavMenu;
use Jankx\Widget\Widgets\CustomFields;
use Jankx\Widget\Widgets\Socials;
use Jankx\Widget\Widgets\ToogleNavMenu;

class WidgetManager
{
    protected static $instance;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
    }

    public function registerWidgets()
    {
        register_widget(Posts::class);
        register_widget(CollapaseNavMenu::class);
        register_widget(CustomFields::class);
        register_widget(Socials::class);
        register_widget(ToogleNavMenu::class);

        $optionFramework = Framework::getActiveFramework();
        if (apply_filters('jankx_widget_enable_facebook_widgets', $optionFramework->getOption('facebook_app_id'))) {
            register_widget(FacebookPagePlugin::class);
        }
    }
}
