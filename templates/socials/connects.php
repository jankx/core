<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<div class="social-sidebar-internal">
    <?php if (!empty($socials)) : ?>
        <ul class="jankx-socials">
            <?php foreach ($socials as $name => $social) : ?>
                <li <?php echo jankx_generate_html_attributes([
                    'class' => ['social-item', 'social-' . $name],
                ]); ?>>
                    <a href="<?php echo array_get($social, 'url'); ?>" target="<?php echo array_get($social, 'target'); ?>"><i class="<?php echo array_get($social, 'icon') ?>"></i></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
