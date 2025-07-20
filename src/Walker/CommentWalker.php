<?php

namespace Jankx\Walker;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Walker_Comment;

/**
 * CUSTOM COMMENT WALKER
 * A custom walker for comments, based on the walker in Twenty Nineteen.
 */
class CommentWalker extends Walker_Comment
{
    /**
     * Outputs a comment in the HTML5 format.
     *
     * @see wp_list_comments()
     * @see https://developer.wordpress.org/reference/functions/get_comment_author_url/
     * @see https://developer.wordpress.org/reference/functions/get_comment_author/
     * @see https://developer.wordpress.org/reference/functions/get_avatar/
     * @see https://developer.wordpress.org/reference/functions/get_comment_reply_link/
     * @see https://developer.wordpress.org/reference/functions/get_edit_comment_link/
     *
     * @param WP_Comment $comment Comment to display.
     * @param int        $depth   Depth of the current comment.
     * @param array      $args    An array of arguments.
     */
    protected function html5_comment($comment, $depth, $args)
    {
        // Open comment item
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        printf(
            '<%s id="comment-%d" %s>',
            $tag,
            $comment->ID,
            comment_class(
                $this->has_children ? 'parent' : '',
                $comment,
                null,
                false
            )
        );

        $comment_author     = get_comment_author($comment);
        $comment_timestamp  = sprintf(__('%1$s at %2$s', 'jankx'), get_comment_date('', $comment), get_comment_time());
        $comment_date       = sprintf(__('%1$s', 'jankx'), get_comment_date('', $comment));
        $comment_reply_link = get_comment_reply_link(
            array_merge(
                $args,
                array(
                    'add_below' => 'div-comment',
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="comment-reply">',
                    'after'     => '</span>',
                )
            )
        );
        $by_post_author   = jankx_is_comment_by_post_author($comment);

        // Comment body
        jankx_template(
            'comment/comment',
            apply_filters(
                'jankx_template_comment_item_data',
                compact(
                    'args',
                    'comment',
                    'depth',
                    'comment_author',
                    'comment_timestamp',
                    'comment_date',
                    'comment_reply_link',
                    'by_post_author'
                )
            )
        );
    }
}
