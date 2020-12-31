<?php
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
