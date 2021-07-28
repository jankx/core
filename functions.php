<?php
use Jankx\TemplateEngine\Engine;
use Jankx\Template\Page;
use Jankx\Template\Template;

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
            $data,
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

    $css_class = apply_filters(
        'jankx_template_the_container_classes',
        array_merge($css_class, (array)$custom_classes)
    );
    return array_unique($css_class, SORT_STRING);
}

if (!function_exists('jankx_open_container')) {
    function jankx_open_container($custom_classes = '', $context = null)
    {
        do_action('jankx_template_before_open_container', $context);

        $open_html = apply_filters('jankx_template_pre_open_container', null, $context);
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
        do_action("jankx_template_after_close_container", $context);

        $close_html = apply_filters('jankx_template_pre_close_container', null, $context);
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
    return $socialSharing->share_buttons();
}


function jankx_template_directory_uri($path = '')
{
    return sprintf(
        '%s/%s',
        get_template_directory_uri(),
        $path
    );
}

function jankx_core_asset_url($path)
{
    if (!isset($GLOBALS['core_assets_dir'])) {
        $GLOBALS['core_assets_dir'] = jankx_get_path_url(dirname(JANKX_FRAMEWORK_FILE_LOADER));
    }
    return sprintf('%s/assets/%s', $GLOBALS['core_assets_dir'], $path);
}
