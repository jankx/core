<?php

namespace Jankx\SiteLayout;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class GutenbergLayoutLoader extends LayoutLoader
{
    protected $layout;
    protected $engine;
    protected $fullContent = false;
}
