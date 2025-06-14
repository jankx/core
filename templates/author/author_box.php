<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<div class="jankx-author-box">
    <div class="author-box-container">
        <div class="author-box-header">
            <div <?php echo jankx_generate_html_attributes(['class' => 'author-avatar circle']) ?>>
                <img src="<?php echo $avatar_url; ?>" alt="<?php echo $author_name; ?>">
            </div>
            <h3 class="author-name">
                <?php if (!empty($url)) : ?>
                <a href="<?php echo $url; ?>" title="<?php echo $author_name; ?>">
                    <?php echo $author_name; ?>
                </a>
                <?php else : ?>
                    <?php echo $author_name; ?>
                <?php endif; ?>
            </h3>
            <div class="socials circle-style">
                <ul class="socials-list">
                <?php foreach ($links as $icon => $social_url) : ?>
                    <li class="<?php echo sprintf('%s %s', $icon, md5($icon)); ?>">
                        <a class="social-link" href="<?php echo $social_url; ?>">
                            <span class="social-icon <?php echo sprintf('%s%s', jankx_get_font_icon_prefix(), $icon); ?>"></span>
                        </a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php if ($biographical_info) : ?>
        <div class="author-box-body">
            <blockquote><?php echo $biographical_info; ?></blockquote>
        </div>
            <?php do_action('jankx/post/author_box/footer'); ?>
        <?php endif; ?>
    </div>
</div>

