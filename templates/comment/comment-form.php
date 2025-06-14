<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

comment_form(
    array(
        'class_form'         => 'section-inner thin max-percentage',
        'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h2>',
    )
);
