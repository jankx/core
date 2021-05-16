<div class="author-avatar">
    <?php if ($comment_author_url) : ?>
        <a href="<?php echo $comment_author_url; ?>" rel="external nofollow" class="url">
    <?php endif; ?>

    <?php echo wp_kses_post($avatar); ?>

    <?php if ($comment_author_url) :
        ?></a><?php
    endif; ?>
</div>
