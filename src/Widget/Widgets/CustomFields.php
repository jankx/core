<?php

namespace Jankx\Widget\Widgets;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx;
use WP_Widget;

class CustomFields extends WP_Widget
{
    public function __construct()
    {
        $options = array(
            'name' => sprintf(
                '&lt;%s&gt; %s',
                Jankx::templateName(),
                __('Custom Fields', 'jankx')
            ),
            'classname' => 'jankx_customfields',
        );
        parent::__construct('jankx_custom_fields', sprintf(
            '&lt;%s&gt; %s',
            Jankx::templateName(),
            __('Custom Fields', 'jankx')
        ), $options);
    }

    public function form($args)
    {
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input
                type="text"
                id="<?php echo $this->get_field_id('title'); ?>"
                class="widefat"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php echo array_get($args, 'title'); ?>"
            />
        </p>
        <?php
    }

    public function widget($args, $instance)
    {
        echo array_get($args, 'before_widget');
        if (isset($instance['title'])) {
            echo array_get($args, 'before_title');
            echo array_get($instance, 'title');
            echo array_get($args, 'after_title');
        }
        ?>
        <p>
            3755 Commercial St SE Salem, Corner with Sunny Boulevard, 3755 Commercial OR 97302
        </p>

        <p>
            (305) 555-4446
        </p>

        <p>
            (305) 555-4555
        </p>

        <p>
            youremail@gmail.com
        </p>

        <p>
            wpestatetheme
        </p>

        <p>
            WP RESIDENCE
        </p>
        <?php
        echo array_get($args, 'after_widget');
    }
}
