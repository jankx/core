<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <?php do_action('jankx_template_comment_before_bobdy', $comment, $args); ?>

        <div class="body">
            <header class="comment-header">
                <?php printf(
                    '<h4 class="comment-author fn">%1$s</h4><span class="screen-reader-text says">%2$s</span>',
                    esc_html($comment_author),
                    __('says:', 'jankx')
                ); ?>
                <div class="comment-metadata">
                    <?php
                    /* translators: 1: Comment date, 2: Comment time. */
                    printf(
                        '<a href="%s"><time datetime="%s" title="%s">%s</time></a>',
                        esc_url(get_comment_link($comment, $args)),
                        get_comment_time('c'),
                        esc_attr($comment_timestamp),
                        esc_html($comment_timestamp)
                    );

                    if (get_edit_comment_link()) {
                        printf(
                            ' <span aria-hidden="true">&bull;</span> <a class="comment-edit-link" href="%s">%s</a>',
                            esc_url(get_edit_comment_link()),
                            __('Edit', 'jankx')
                        );
                    }
                    ?>
                </div><!-- .comment-metadata -->
            </header><!-- .comment-meta -->

            <div class="comment-content entry-content">
                <?php
                comment_text();
                if ('0' === $comment->comment_approved) {
                    ?>
                    <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'jankx'); ?></p>
                    <?php
                }
                ?>
            </div><!-- .comment-content -->

            <?php
            if ($comment_reply_link || $by_post_author) {
                ?>
                <footer class="comment-footer-meta">
                    <?php
                    if ($comment_reply_link) {
                        echo $comment_reply_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Link is escaped in https://developer.wordpress.org/reference/functions/get_comment_reply_link/
                    }
                    if ($by_post_author) {
                        echo '<span class="by-post-author">' . __('By Post Author', 'jankx') . '</span>';
                    }
                    ?>
                </footer>
                <?php
            }
            ?>
        </div>

        <?php do_action('jankx_template_comment_after_bobdy', $comment); ?>
</article><!-- .comment-body -->
