<?php

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

if (! function_exists('jankx')) {
    function jankx()
    {
        $page = Page::getInstance();
        $page->render();
    }
}

if (! function_exists('jankx_template')) {
    function jankx_template($templates, $data = array(), $echo = true)
    {
        $templateEngine = Template::getEngine(Jankx::ENGINE_ID);

        if (! ( $templateEngine instanceof Engine )) {
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
        array_merge($css_class, (array)$custom_classes)
    );
    return array_unique($css_class, SORT_STRING);
}

if (!function_exists('jankx_open_container')) {
    function jankx_open_container($custom_classes = '', $context = null)
    {
        do_action('jankx/template/container/open/before', $context);

        $open_html = apply_filters('jankx/template/container/open/pre', null, $context);
        if (is_null($open_html)) {
            $open_html = sprintf(
                '<div class="%s">',
                implode(' ', jankx_container_css_class($custom_classes))
            );
        }

        echo $open_html;
    }
}

if (!function_exists('jankx_close_container')) {
    function jankx_close_container($context = null)
    {
        do_action("jankx/template/container/close/after", $context);

        $close_html = apply_filters('jankx/template/container/close/pre', null, $context);
        if (is_null($close_html)) {
            $close_html = '</div>';
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
    $args      = wp_parse_args(
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
            array( 'fields' => 'ids' )
        );
        if (! empty($category_ids)) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => $category_ids,
            );
        }

        $tag_ids = wp_get_post_tags(
            $post_id,
            array( 'fields' => 'ids' )
        );
        if (! empty($tag_ids)) {
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'id',
                'terms'    => $tag_ids,
            );
        }
    }

    if (! empty($tax_query)) {
        $tax_query['relation'] = 'OR';
        $args['tax_query']     = $tax_query;
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

        if (! empty($user) && ! empty($post)) {
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

function jankx_get_logo_image($props)
{
    echo jankx_component('logo', array(
        'text' => get_bloginfo('name'),
    ));
}

function jankx_get_toggle_hamburger_menu($props)
{
    jankx_template('common/hamburger-menu');
}

function jankx_get_site_layout($skipDefault = false)
{
    return SiteLayout::getInstance()->getLayout($skipDefault);
}

if (!function_exists('jankx_is_support_block_template')) {
    function jankx_is_support_block_template()
    {
        return apply_filters(
            'jankx/gutenberg/enabled',
            get_theme_support('block-templates') && (!empty($_wp_current_template_content) || !is_single())
        );
    }
}
