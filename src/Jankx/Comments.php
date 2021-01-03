<?php
namespace Jankx;

class Comments
{
    public static function init()
    {
        add_action('jankx_template_after_content', array(__CLASS__, 'show_comments'));
        add_action('jankx_template_after_list_comments', array(__CLASS__, 'show_comment_form'));
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
}
