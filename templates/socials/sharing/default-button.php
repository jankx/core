<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<button class="button <?php echo $appearance; ?> text-<?php echo $text_appearance; ?>" style="<?php echo $background_color; ?><?php echo $border_color; ?>">
    <span class="<?php echo jankx_get_font_icon($type); ?>"></span>
    <span class="social-icon-text"><?php echo $name; ?></span>
</button>
