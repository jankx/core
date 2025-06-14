<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
 ?>
<div class="social-sidebar-internal">
    <?php if (!empty($socials)) : ?>
        <ul class="jankx-socials">
            <?php foreach ($socials as $name => $social) :
                if (!isset($social['icon']['type'])) {
                    continue;
                }
                ?>
            <li <?php echo jankx_generate_html_attributes([
                'class' => ['social-item', 'social-' . $name],
            ]); ?>>
                <a href="<?php echo array_get($social, 'url'); ?>" target="<?php echo array_get($social, 'target'); ?>">
                    <?php if ($social['icon']['type'] === 'html') : ?>
                        <?php echo $social['icon']['html']; ?>
                    <?php else : ?>
                        <img src="<?php echo $social['icon']['url']; ?>" alt="<?php echo $name; ?>" />
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
