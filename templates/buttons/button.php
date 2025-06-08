<?php
    $styles = [];
if (!empty($background)) {
    $styles[] = sprintf('background: %s;', $background);
}
?>
<div class="floating-button">
    <a href="<?= $this->e($link); ?>" target="<?php echo $target; ?>">
        <?php if (!empty($icon)) : ?>
            <span class="button-icon <?= $this->e($effect); ?>-effect"
                <?php echo jankx_generate_html_attributes([
                    'style' => $styles
                ]); ?>
            >
                <?php if (strpos($icon, '//') === false) : ?>
                <span class="icon <?php echo jankx_get_font_icon($icon); ?>"></span>
                <?php else : ?>
                    <img src="<?php echo $icon; ?>" alt="<?php echo $text; ?>">
                <?php endif; ?>
            </span>
        <?php endif; ?>
        <span class="button-text"><?php echo $text; ?></span>
    </a>
</div>
