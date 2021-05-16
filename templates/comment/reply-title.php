<?php
if (! have_comments()) {
    _e('Leave a comment', 'jankx');
} elseif (1 === $comments_number) {
    /* translators: %s: Post title. */
    printf(_x('One reply on &ldquo;%s&rdquo;', 'comments title', 'jankx'), get_the_title());
} else {
    printf(
        /* translators: 1: Number of comments, 2: Post title. */
        _nx(
            '%1$s reply on &ldquo;%2$s&rdquo;',
            '%1$s replies on &ldquo;%2$s&rdquo;',
            $comments_number,
            'comments title',
            'jankx'
        ),
        number_format_i18n($comments_number),
        get_the_title()
    );
}
