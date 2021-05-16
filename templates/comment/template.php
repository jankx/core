<?php defined('ABSPATH') || exit('Cheating huh?'); ?>

<div class="comments" id="comments">
    <div class="comments-header section-inner small max-percentage">

        <h2 class="comment-reply-title">
            <?php echo $comment_reply_title; ?>
        </h2><!-- .comments-title -->

    </div><!-- .comments-header -->

    <div class="comments-inner section-inner thin max-percentage">
        <?php
        wp_list_comments($list_comments_args);

        if ($comment_pagination) :
            $pagination_classes = '';

            // If we're only showing the "Next" link, add a class indicating so.
            if (false === strpos($comment_pagination, 'prev page-numbers')) {
                $pagination_classes = ' only-next';
            }
            ?>

            <nav class="comments-pagination pagination<?php echo $pagination_classes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?>" aria-label="<?php esc_attr_e('Comments', 'twentytwenty'); ?>">
                <?php echo wp_kses_post($comment_pagination); ?>
            </nav>
        <?php endif; ?>

    </div><!-- .comments-inner -->

</div><!-- comments -->
