<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
 ?>
<div class="mobile-header-elements">
    <?php foreach ($elements as $element => $props) : ?>
        <?php if (!is_callable($props['callback'])) {
            continue;
        } ?>
        <div class="mobile-header-element <?php echo 'element-' . $element; ?>">
            <?php
                echo call_user_func_array($props['callback'], array(
                    $props,
                    $element
                ));
            ?>
        </div>
    <?php endforeach; ?>
</div>