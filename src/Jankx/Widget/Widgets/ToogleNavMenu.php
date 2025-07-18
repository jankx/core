<?php

namespace Jankx\Widget\Widgets;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use WP_Widget;
use Jankx;

class ToogleNavMenu extends WP_Widget
{
    public function __construct()
    {
        $options = array(
            'name' => sprintf(
                '&lt;%s&gt; %s',
                Jankx::templateName(),
                __('Toogle Menu', 'jankx')
            ),
            'classname' => 'widget_nav_menu widget-jankx-collapase-menu'
        );

        return parent::__construct(
            'jankx_collapase_menu',
            sprintf(
                '&lt;%s&gt; %s',
                Jankx::templateName(),
                __('Toogle Menu', 'jankx')
            ),
            $options
        );
    }

    public function form($instance)
    {
        $menus = wp_get_nav_menus();
        $current_menu = array_get($instance, 'nav_menu');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo esc_html(__('Title')); ?></label>
            <input
                type="text"
                id="<?php echo $this->get_field_id('title'); ?>"
                class="widefat"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php echo array_get($instance, 'title'); ?>"
            >
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php echo esc_html(__('Select Menu:')); ?></label>
            <select
                name="<?php echo $this->get_field_name('nav_menu'); ?>"
                id="<?php echo $this->get_field_id('nav_menu'); ?>"
            >
                <option value=""><?php echo __('— Select —'); ?></option>
                <?php foreach ($menus as $menu) : ?>
                    <option value="<?php echo $menu->term_id; ?>"<?php selected($current_menu, $menu->term_id); ?>>
                        <?php echo $menu->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_items'); ?>">
                <input
                    type="checkbox"
                    id="<?php echo $this->get_field_id('show_items'); ?>"
                    name="<?php echo $this->get_field_name('show_items'); ?>"
                    value="yes"
                    <?php checked('yes', array_get($instance, 'show_items', 'no')); ?>
                />
                <?php echo esc_html(__('Show items', 'jankx')); ?>
            </label>
        </p>
        <?php
    }

    public function widget($args, $instance)
    {
        // Get menu.
        $nav_menu = ! empty($instance['nav_menu']) ? wp_get_nav_menu_object($instance['nav_menu']) : false;

        if (! $nav_menu) {
            return;
        }

        $default_title = __('Menu');
        $title         = ! empty($instance['title']) ? $instance['title'] : '';

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $format = current_theme_supports('html5', 'navigation-widgets') ? 'html5' : 'xhtml';

        /**
         * Filters the HTML format of widgets with navigation links.
         *
         * @since 5.5.0
         *
         * @param string $format The type of markup to use in widgets with navigation links.
         *                       Accepts 'html5', 'xhtml'.
         */
        $format = apply_filters('navigation_widgets_format', $format);

        if ('html5' === $format) {
            // The title may be filtered: Strip out HTML and make sure the aria-label is never empty.
            $title      = trim(strip_tags($title));
            $aria_label = $title ? $title : $default_title;

            $nav_menu_args = array(
                'fallback_cb'          => '',
                'menu'                 => $nav_menu,
                'container'            => 'nav',
                'container_aria_label' => $aria_label,
                'items_wrap'           => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            );
        } else {
            $nav_menu_args = array(
                'fallback_cb' => '',
                'menu'        => $nav_menu,
            );
        }

        /**
         * Filters the arguments for the Navigation Menu widget.
         *
         * @since 4.2.0
         * @since 4.4.0 Added the `$instance` parameter.
         *
         * @param array   $nav_menu_args {
         *     An array of arguments passed to wp_nav_menu() to retrieve a navigation menu.
         *
         *     @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
         *     @type mixed         $menu        Menu ID, slug, or name.
         * }
         * @param WP_Term $nav_menu      Nav menu object for the current menu.
         * @param array   $args          Display arguments for the current widget.
         * @param array   $instance      Array of settings for the current widget.
         */
        wp_nav_menu(apply_filters('widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance));

        echo $args['after_widget'];
    }
}
