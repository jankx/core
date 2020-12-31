<?php
namespace Jankx;

class Comments
{
    public static function init()
    {
        add_action('jankx_template_after_content', array(__CLASS__, 'show_comments'));
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
}
