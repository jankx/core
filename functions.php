<?php

use Jankx\Component\Abstracts\ComponentComposite;
use Jankx\Component\Registry;
use Jankx\SiteLayout\SiteLayout;
use Jankx\TemplateEngine\Engine;
use Jankx\Template\Page;
use Jankx\Template\Template;
use Jankx\TemplateEngine\Context;

/**
 * The Jankx render helper
 *
 * @return void
 */

if (!function_exists('jankx')) {
    function jankx()
    {
        $page = Page::getInstance();
        $page->render();
    }
}

if (!function_exists('jankx_template')) {
    function jankx_template($templates, $data = array(), $echo = true)
    {
        $templateEngine = Template::getEngine(Jankx::ENGINE_ID);

        if (!($templateEngine instanceof Engine)) {
            throw new \Exception(
                sprintf('The template engine must be is instance of %s', Engine::class)
            );
        }

        return $templateEngine->render(
            $templates,
            array_merge(Context::get(), $data),
            $echo
        );
    }
}

function jankx_container_css_class($custom_classes = '')
{
    $css_class = array(
        'jankx-container',
        'container',
    );

    if (empty($custom_classes)) {
        $custom_classes = array();
    }

    if (apply_filters('jankx/css/class/container/wrap', true)) {
        $css_class[] = 'has-wrap';
    }

    $css_class = apply_filters(
        'jankx/template/container/classes',
        array_merge($css_class, (array) $custom_classes)
    );
    return array_unique($css_class, SORT_STRING);
}

if (!function_exists('jankx_open_container')) {
    function jankx_open_container($custom_classes = '', $context = null, $echo = true)
    {
        do_action('jankx/template/container/open/before', $context);

        $open_html = apply_filters('jankx/template/container/open/pre', null, $context);
        if (is_null($open_html)) {
            $open_html = sprintf(
                '<div class="%s">',
                implode(' ', jankx_container_css_class($custom_classes))
            );
        }

        if (!$echo) {
            return $open_html;
        }
        echo $open_html;
    }
}

if (!function_exists('jankx_close_container')) {
    function jankx_close_container($context = null, $echo = true)
    {
        do_action("jankx/template/container/close/after", $context);

        $close_html = apply_filters('jankx/template/container/close/pre', null, $context);
        if (is_null($close_html)) {
            $close_html = '</div>';
        }

        if (!$echo) {
            return $close_html;
        }
        echo $close_html;
    }
}


function jankx_get_related_query($args = array(), $post_id = null)
{
    if (is_null($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    $args = wp_parse_args(
        $args,
        array(
            'post_type' => 'post',
            'post__not_in' => array($post_id)
        )
    );
    $tax_query = array();

    if ('post' === $args['post_type']) {
        $category_ids = wp_get_post_categories(
            $post_id,
            array('fields' => 'ids')
        );
        if (!empty($category_ids)) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $category_ids,
            );
        }

        $tag_ids = wp_get_post_tags(
            $post_id,
            array('fields' => 'ids')
        );
        if (!empty($tag_ids)) {
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field' => 'id',
                'terms' => $tag_ids,
            );
        }
    }

    if (!empty($tax_query)) {
        $tax_query['relation'] = 'OR';
        $args['tax_query'] = $tax_query;
    }

    return new WP_Query(
        apply_filters(
            'jankx_related_query_args',
            $args
        )
    );
}

function jankx_is_comment_by_post_author($comment = null)
{

    if (is_object($comment) && $comment->user_id > 0) {
        $user = get_userdata($comment->user_id);
        $post = get_post($comment->comment_post_ID);

        if (!empty($user) && !empty($post)) {
            return $comment->user_id === $post->post_author;
        }
    }
    return false;
}

function jankx_social_share_buttons($socials = null)
{
    $socialSharing = \Jankx\Social\Sharing::get_instance();
    return $socialSharing->share_buttons($socials);
}


function jankx_template_directory_uri($path = '')
{
    return sprintf(
        '%s/%s',
        get_template_directory_uri(),
        $path
    );
}

function jankx_core_asset_directory()
{
    if (!isset($GLOBALS['core_assets_dir'])) {
        $GLOBALS['core_assets_dir'] = jankx_get_path_url(dirname(JANKX_FRAMEWORK_FILE_LOADER));
    }
    return $GLOBALS['core_assets_dir'];
}


function jankx_core_asset_url($path)
{
    $core_assets_dir = jankx_core_asset_directory();
    return sprintf('%s/assets/%s', $core_assets_dir, $path);
}


/**
 * Jankx component
 *
 * @param string $name The component name
 * @param array $props Component property or data
 * @param array $args The options of component
 *
 * @return Jankx\Component\Constracts\Component | null;
 */
function jankx_component($name, $props = array(), $echo = false)
{
    // Get all components are supported
    $components = Registry::getComponents();

    if (!isset($components[$name])) {
        error_log(
            sprintf(
                __('The component `%s` is not registered in Jankx system', 'jankx'),
                $name
            )
        );
        return null;
    }

    // Create component object
    $componentClass = array_get($components, $name);
    $component = new $componentClass($props);

    if (is_a($component, ComponentComposite::class)) {
        // The component output
        if (!$echo) {
            return $component;
        }
        echo $component;
    }
    return $component;
}


function jankx_get_logo_image($props)
{
    echo jankx_component('logo', array(
        'text' => get_bloginfo('name'),
    ));
}

function jankx_get_toggle_hamburger_menu($props = [], $echo = true)
{
    return jankx_template('common/hamburger-menu', $props, $echo);
}

function jankx_get_site_layout($skipDefault = false)
{
    return SiteLayout::getInstance()->getLayout($skipDefault);
}

function jankx_the_custom_title($post = null, $echo = true)
{
    if (empty($post)) {
        $post = $GLOBALS['post'];
    }

    if (is_numeric($post)) {
        $post = get_post($post);
    }

    $callback = function ($title, $postId) use ($post) {
        if ($post->ID !== $postId) {
            return $title;
        }
        $customTitle = get_post_meta($post->ID, 'custom_title', true);
        if (empty($customTitle)) {
            return $title;
        }
        return $customTitle;
    };

    add_filter('the_title', $callback, 10, 2);
    $title = get_the_title($post);
    remove_filter('the_title', $callback, 10);


    if ($echo === false) {
        return $title;
    }
    echo $title;
}

function jankx_single_term_title($term = null, $type = 'term', $echo = true)
{
    if (is_null($term)) {
        $term = get_queried_object();
    }
    if (empty($type)) {
        if (is_category()) {
            $type = 'cat';
        } elseif (is_tag()) {
            $type = 'tag';
        } elseif (is_tax()) {
            $type = 'term';
        }
    }

    $customTitleCallback = function ($title) use ($term) {
        $customTitle = get_term_meta($term->term_id, 'custom_display_title', true);
        if (empty($customTitle)) {
            return $title;
        }
        return $customTitle;
    };

    add_filter("single_{$type}_title", $customTitleCallback, 10);
    $title = single_term_title('', false);
    remove_filter("single_{$type}_title", $customTitleCallback, 10);

    if ($echo === false) {
        return $title;
    }
    echo $title;
}

/**
 * Summary of jankx_get_user_avatar
 *
 * @param integer|WP_User $userId
 * @return string
 */
function jankx_get_user_avatar_url($userId = null)
{
    $user = $userId instanceof WP_User ? $userId : get_user($userId);
    if (empty($user)) {
        return '';
    }

    return apply_filters(
        'jankx/user/avatar',
        get_avatar_url($user->user_email),
        $user
    );
}


function jankx_get_user_link($userId, $type)
{
    $link = get_user_meta($userId, $type, true);
    if (empty($link)) {
        return '';
    }

    if (preg_match('/https?:/', $link)) {
        return $link;
    }

    switch ($type) {
        case 'twitter':
        case 'x':
            return sprintf('https://x.com/%s', $link);
        case 'mastodon':
            return sprintf('https://mastodon.social/@%s', $link);
        case 'facebook':
            return sprintf('https://www.facebook.com/%s', $link);
        case 'instagram':
            return sprintf('https://instagram.com/%s', $link);
        case 'linkedin':
            return sprintf('https://linkedin.com/%s', $link);
        case 'myspace':
            return sprintf('https://myspace.com/%s', $link);
        case 'soundcloud':
            return sprintf('https://soundcloud.com/%s', $link);
        case 'tumblr':
            return sprintf('https://www.tumblr.com/%s', $link);
    }
    return '';
}