<?php

namespace Jankx\Admin;

use Jankx\GlobalConfigs;
use Jankx\Adapter\Options\Framework;
use Jankx\Adapter\Options\OptionsReader;

class Admin
{
    /**
     * @var \Jankx\Adapter\Options\Interfaces\Adapter
     */
    protected $optionFramework;

    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'setup'), 15);
        add_action('after_setup_theme', array($this, 'init_theme_options'), 30);
    }

    public function setup()
    {
        $shortName = GlobalConfigs::get('theme.shortName');
        $menu_title = apply_filters(
            'jankx_admin_menu_title',
            strlen($shortName) >= 8 ? $shortName : sprintf('%s %s', $shortName, __('Options', 'jankx'))
        );
        $display_name = apply_filters(
            'jankx_admin_menu_display_name',
            GlobalConfigs::get('theme.name')
        );

        $this->optionFramework = Framework::getActiveFramework();
        if ($this->optionFramework) {
            $this->optionFramework->register_admin_menu($menu_title, $display_name);
        }

        add_action('admin_init', array($this, 'setup_admin'));
    }

    public function init_theme_options()
    {
        // Setup theme options
        if ($this->optionFramework) {
            $this->optionFramework->createSections(OptionsReader::getInstance());
        }
    }

    public function setup_admin()
    {
    }
}
