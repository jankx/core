<?php

namespace Jankx;

class Comments
{
    public static function init($wp)
    {
        global $wp_query;
        if ($wp_query->is_singular(apply_filters('jankx_enable_comment_post_types', 'post'))) {
            add_action('jankx/template/page/content/after', array(__CLASS__, 'show_comments'));
            add_action('jankx_template_after_list_comments', array(__CLASS__, 'show_comment_form'));
            add_action('jankx_template_comment_before_bobdy', array(__CLASS__, 'show_comment_author_avatar'), 10, 2);
        }
    }

    public static function show_comments()
    {
        if ((comments_open() || get_comments_number() ) && ! post_password_required()) {
            ?>
            <div class="comments-wrapper section-inner">

                <?php comments_template(); ?>

                </div><!-- .comments-wrapper -->

            <?php
        }
    }

    public static function show_comment_form($comments)
    {
        if (comments_open() || pings_open()) {
            if ($comments) {
                jankx_template('comment/separator');
            }

            jankx_template('comment/comment-form');
        } elseif (is_single()) {
            if ($comments) {
                jankx_template('comment/separator');
            }
            jankx_template('comment/closed-comment');
        }
    }

    public static function show_comment_author_avatar($comment, $args)
    {
        $comment_author_url = get_comment_author_url($comment);
        $avatar             = get_avatar($comment, $args['avatar_size']);

        jankx_template('comment/avatar', array(
            'avatar' => $avatar,
            'comment_author_url' => $comment_author_url,
        ));
    }
}
