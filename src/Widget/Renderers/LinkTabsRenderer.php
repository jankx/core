<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Widget\Renderers\Base;

class LinkTabsRenderer extends Base
{
    protected $tabs = array();
    protected $options = array();

    public function setTabs($tabs)
    {
        if (count($tabs) > 0) {
            $this->tabs = $tabs;
        }
    }

    public function render()
    {
        return $this->loadTemplate('widget/link-tabs', array(
            'tabs' => $this->tabs,
        ), false);
    }
}
