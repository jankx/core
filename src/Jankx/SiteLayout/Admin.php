<?php

namespace Jankx\SiteLayout;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\SiteLayout\Admin\Metabox\PostLayout;

class Admin
{
    protected $postLayout;


    public function __construct()
    {
        $this->postLayout = new PostLayout();

        $this->initHooks();
    }


    public function initHooks()
    {
        add_action('add_meta_boxes', array($this->postLayout, 'addMetabox'));
        add_action('save_post', array($this->postLayout, 'savePost'), 10, 2);
    }
}
