<?php

namespace Jankx\Widget\Widgets\Facebook;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx;
use WP_Widget;
use Jankx\Widget\Renderers\Facebook\PagePlugin as PagePluginRenderer;

class PagePlugin extends WP_Widget
{
    const WIDGET_ID = 'jankx-facebook-page-plugin';

    protected $renderer;

    public function __construct()
    {
        $options = array(
            'classname' => 'jankx-fb-pageplugin',
            'description' => __('Display Facebook page plugin support cached preview', 'jankx')
        );
        parent::__construct(
            static::WIDGET_ID,
            sprintf(
                '&lt;%s&gt; %s',
                Jankx::templateName(),
                __('Facebook Page Plugin', 'jankx')
            ),
            $options
        );
        $this->renderer = new PagePluginRenderer();
    }

    public function form($instance)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('fanpage_url'); ?>">
                <?php _e('Facebook Page URL', 'jankx') ?>
            </label>
            <input
                type="text"
                class="widefat"
                id="<?php echo $this->get_field_id('fanpage_url'); ?>"
                name="<?php echo $this->get_field_name('fanpage_url'); ?>"
                value="<?php echo $instance['fanpage_url']; ?>"
            />
        </p>
        <?php
    }

    public function widget($args, $instance)
    {
        PagePluginRenderer::prepare(
            array(
                'href' => array_get($instance, 'fanpage_url')
            ),
            $this->renderer
        );
        echo (string) $this->renderer;
    }
}
