<?php

namespace Jankx\SiteLayout\Admin\Menu;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx;
use Jankx\SiteLayout\Menu\Renderer\NavItemRenderer;
use Mega_Menu_Walker;

class JankxItems
{
    protected static $jankxNavItems;

    protected $renderer;
    protected $override_columns_hidden;

    public function __construct()
    {
        $this->renderer = new NavItemRenderer();
    }

    public function register()
    {
        if (wp_is_request('admin')) {
            add_action('admin_head-nav-menus.php', array( $this, 'add_menu_meta_boxes' ));
            add_filter('wp_setup_nav_menu_item', array($this, 'setup_jankx_items'));

            add_filter('manage_nav-menus_columns', array($this, 'setup_jankx_extra_features'), 15);

            add_action('wp_nav_menu_item_custom_fields', array($this, 'add_custom_subtitle_field'), 10, 4);
            add_action('wp_nav_menu_item_custom_fields', array($this, 'add_custom_subtitle_position'), 10, 4);

            add_action('wp_nav_menu_item_custom_fields', array($this, 'add_custom_menu_item_display'), 10, 5);

            add_filter("update_user_metadata", array($this, 'hide_jankx_menu_columns'), 10, 4);
        }

        if (wp_is_request('frontend')) {
            add_filter('wp_nav_menu_objects', array($this->renderer, 'resetWalkerSupportHookStartEl'));

            $renderer = &$this->renderer;

            add_filter('wp_nav_menu_args', function ($args) use ($renderer) {
                if (is_a($args['walker'], Mega_Menu_Walker::class)) {
                    add_filter('megamenu_walker_nav_menu_start_el', array($renderer, 'renderMenuItem'), 10, 4);
                } else {
                    add_filter('walker_nav_menu_start_el', array($renderer, 'renderMenuItem'), 10, 4);
                }
                return $args;
            }, 100000);

            add_filter('nav_menu_css_class', array($this, 'clean_menu_css_classes'));
            add_filter('nav_menu_item_title', array($this->renderer, 'renderMenuItemSubtitle'), 10, 4);

            add_filter('wp_nav_menu_items', array($this->renderer, 'customDisplayMenuItem'), 10, 2);
        }

        add_action('save_post', array($this, 'save_jankx_menu_item_metas'), 10, 2);
    }

    public function add_menu_meta_boxes()
    {
        add_meta_box(
            'jankx_nav_links',
            class_exists(Jankx::class) ? Jankx::templateName() : __('Jankx Framework', 'jankx'),
            array( $this, 'nav_menu_links' ),
            'nav-menus',
            'side'
        );
    }

    public static function get_nav_items()
    {
        if (!is_null(static::$jankxNavItems)) {
            return static::$jankxNavItems;
        }

        static::$jankxNavItems = array(
            'jankx-logo' => __('Site Logo', 'jankx'),
            'jankx-search-form' => __('Search Form', 'jankx'),
            'jankx-hotline' => __('Hotline', 'jankx'),
        );
        static::$jankxNavItems = apply_filters(
            'jankx/layout/site/menu/itemtypes',
            static::$jankxNavItems
        );

        return static::$jankxNavItems;
    }

    protected function create_menu_nav_item($key)
    {
        $title = '';
        if ($key === 'jankx-logo') {
            $title = get_bloginfo('name');
        } else {
            $title = static::$jankxNavItems[$key];
        }

        $item = wp_parse_args(array(), array(
            'type' => $key,
            'title' => $title,
            'url' => "#jankx-{$key}",
            'classes' => null
        ));

        return apply_filters("jankx_site_layout_{$key}_menu_item", $item, $key);
    }

    protected function render_menu_item_hidden_input($index, $item)
    {
        foreach ($item as $type => $value) : ?>
            <?php if (is_null($value)) : ?>
                <input type="hidden"
                    class="menu-item-<?php echo $type; ?>"
                    name="menu-item[<?php echo esc_attr($index); ?>][menu-item-<?php echo $type; ?>]"
                />
            <?php else : ?>
                <input
                    type="hidden"
                    class="menu-item-type"
                    name="menu-item[<?php echo esc_attr($index); ?>][menu-item-<?php echo $type; ?>]<?php  ?>"
                    value="<?php echo $value; ?>"
                />
            <?php endif; ?>
            <?php
        endforeach;
    }

    public function nav_menu_links()
    {
        $items = static::get_nav_items();
        ?>
        <div id="posttype-jankx-nav-items" class="posttypediv">
            <div id="tabs-panel-jankx-nav-items" class="tabs-panel tabs-panel-active">
                <ul id="jankx-nav-items-checklist" class="categorychecklist form-no-clear">
                    <?php
                    $i = -1;
                    foreach ($items as $key => $value) :
                        $item = $this->create_menu_nav_item($key);
                        ?>
                        <li>
                            <label class="menu-item-title">
                                <input
                                    type="checkbox"
                                    class="menu-item-checkbox"
                                    name="menu-item[<?php echo esc_attr($i); ?>][menu-item-object-id]"
                                    value="<?php echo esc_attr($i); ?>"
                                />
                                <?php echo esc_html($value); ?>
                            </label>
                            <?php $this->render_menu_item_hidden_input($i, $item); ?>
                        </li>
                        <?php
                        $i--;
                    endforeach;
                    ?>
                </ul>
            </div>
            <p class="button-controls">
                <span class="add-to-menu">
                    <button
                        type="submit"
                        class="button-secondary submit-add-to-menu right"
                        value="<?php esc_attr_e('Add to menu', 'jankx'); ?>"
                        name="add-post-type-menu-item"
                        id="submit-posttype-jankx-nav-items"
                    >
                        <?php esc_html_e('Add to menu', 'jankx'); ?>
                    </button>
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
        <?php
    }

    public function setup_jankx_items($menu_item)
    {
        $items = static::get_nav_items();

        if (isset($items[$menu_item->type])) {
            $menu_item->type_label = sprintf('Jankx %s', $items[$menu_item->type]);
        }

        return $menu_item;
    }

    public function setup_jankx_extra_features($columns)
    {
        $columns = array_merge($columns, array(
            'subtitle' => __('Jankx Subtitle', 'jankx'),
            'menu_item_display' => __('Display', 'jankx'),
        ));
        return $columns;
    }

    public function add_custom_subtitle_field($item_id, $item, $depth, $args)
    {
        $subtitle = get_post_meta($item_id, '_jankx_menu_item_subtitle', true);
        ?>
        <p class="field-subtitle description description-wide">
            <label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
                <?php _e('Jankx Subtitle', 'jankx'); ?><br>
                <input
                    type="text"
                    id="edit-menu-item-subtitle-<?php echo $item_id; ?>"
                    class="widefat code edit-menu-item-subtitle"
                    name="menu-item-subtitle[<?php echo $item->ID; ?>]"
                    value="<?php echo trim($subtitle); ?>"
                />
                <span class="description">
                    <?php _e('The subtitle will be displayed in below item text', 'jankx'); ?>
                </span>
            </label>
        </p>
        <?php
    }

    public function add_custom_subtitle_position($item_id, $item, $depth, $args)
    {
        $subtitle_position = get_post_meta($item_id, '_jankx_menu_item_subtitle_position', true);
        $options = array(
            '' => __('Default'),
            'top' => __('Top'),
            'bottom' => __('Bottom'),
            'before' => __('Before'),
            'before' => __('After'),
        );
        ?>
        <p class="description field-subtitle description-wide">
            <label for="edit-menu-item-title-23019">
                <?php _e('Subtitle position', 'jankx'); ?>
                <select class="widefat" name="menu-item-subtitle-position[<?php echo $item->ID; ?>]" id="">
                    <?php foreach ($options as $key => $value) : ?>
                    <option value="<?php echo $key ?>"<?php selected($key, $subtitle_position); ?>><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

        </p>
        <?php
    }

    public function add_custom_menu_item_display($item_id, $item, $depth, $args)
    {
        $item_width = get_post_meta($item_id, '_jankx_menu_item_width', true);
        $item_position = get_post_meta($item_id, '_jankx_menu_item_position', true);
        $widths = array(
            '' => __('Normal'),
            'stretch' => __('Stretch', 'jankx')
        );
        $positions = array(
            '' => __('Left (Default)', 'jankx'),
            'center' => __('Center', 'jankx'),
            'right' => __('Right'),
        );
        ?>
        <p class="description field-menu_item_display description-wide">
            <label for="edit-menu-item-title-23019">
                <?php _e('Width', 'jankx'); ?>
                <select class="widefat" name="menu-item-display-width[<?php echo $item->ID; ?>]" id="">
                    <?php foreach ($widths as $key => $value) : ?>
                    <option value="<?php echo $key ?>"<?php selected($key, $item_width); ?>><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p class="description field-menu_item_display description-wide">
            <label for="edit-menu-item-title-23019">
                <?php _e('Position', 'jankx'); ?>
                <select class="widefat" name="menu-item-display-position[<?php echo $item->ID; ?>]" id="">
                    <?php foreach ($positions as $key => $value) : ?>
                    <option value="<?php echo $key ?>"<?php selected($key, $item_position); ?>><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <?php
    }

    public function save_jankx_menu_item_metas()
    {
        if (!empty($_POST['menu-item-subtitle'])) {
            $subtitles = $_POST['menu-item-subtitle'];
            foreach ($subtitles as $post_ID => $subtitle) {
                $menuItem = get_post($post_ID);
                if (!$menuItem || $menuItem->post_type !== 'nav_menu_item') {
                    continue;
                }
                if (trim($subtitle)) {
                    update_post_meta($post_ID, '_jankx_menu_item_subtitle', $subtitle);
                } else {
                    delete_post_meta($post_ID, '_jankx_menu_item_subtitle', $subtitle);
                }
            }
        }

        if (!empty($_POST['menu-item-subtitle-position'])) {
            $subtitles = $_POST['menu-item-subtitle-position'];

            foreach ($subtitles as $post_ID => $subtitle) {
                $menuItem = get_post($post_ID);
                if (!$menuItem || $menuItem->post_type !== 'nav_menu_item') {
                    continue;
                }
                if (trim($subtitle)) {
                    update_post_meta($post_ID, '_jankx_menu_item_subtitle_position', $subtitle);
                } else {
                    delete_post_meta($post_ID, '_jankx_menu_item_subtitle_position', $subtitle);
                }
            }
        }

        if (!empty($_POST['menu-item-display-width'])) {
            $widths = $_POST['menu-item-display-width'];
            foreach ($widths as $post_ID => $width) {
                $menuItem = get_post($post_ID);
                if (!$menuItem || $menuItem->post_type !== 'nav_menu_item') {
                    continue;
                }
                if (trim($width)) {
                    update_post_meta($post_ID, '_jankx_menu_item_width', $width);
                } else {
                    delete_post_meta($post_ID, '_jankx_menu_item_width', $width);
                }
            }
        }

        if (!empty($_POST['menu-item-display-position'])) {
            $positions = $_POST['menu-item-display-position'];
            foreach ($positions as $post_ID => $position) {
                $menuItem = get_post($post_ID);
                if (!$menuItem || $menuItem->post_type !== 'nav_menu_item') {
                    continue;
                }
                if (trim($position)) {
                    update_post_meta($post_ID, '_jankx_menu_item_position', $position);
                } else {
                    delete_post_meta($post_ID, '_jankx_menu_item_position', $position);
                }
            }
        }
    }

    public function clean_menu_css_classes($classes)
    {
        array_pop($classes);

        if (false !== ($index = array_search('menu-item-object-', $classes))) {
            unset($classes[$index]);
        }

        return $classes;
    }

    public function hide_jankx_menu_columns($check, $object_id, $meta_key, $meta_value)
    {
        if ($meta_key !== 'managenav-menuscolumnshidden' || false !== get_user_option('managenav-menuscolumnshidden') || $this->override_columns_hidden) {
            return $check;
        }

        $this->override_columns_hidden = true;

        array_push($meta_value, 'subtitle');
        array_push($meta_value, 'menu_item_display');

        update_user_option(
            $object_id,
            $meta_key,
            $meta_value,
            true
        );

        return $this->override_columns_hidden;
    }
}
