<?php

namespace Jankx\UI;

class SecondaryNavigation
{
    public function __construct()
    {
    }

    public function init()
    {
        add_action('jankx/component/header/content/after', [$this, 'loadSecondaryMenu']);
    }

    public function loadSecondaryMenu()
    {
    }
}
