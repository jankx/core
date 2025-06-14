<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
.jankx-base .jankx-container.has-wrap {
    max-width: <?php printf('%s%s', $desktop['width'], $desktop['unit'] !='custom' ? $desktop['unit'] : ''); ?>;
}
@media(max-width: <?php echo $breakpoints['lg'] ?>px) {
    .jankx-base .jankx-container.has-wrap {
        max-width: <?php printf('%s%s', $tablet['width'], $tablet['unit'] !='custom' ? $tablet['unit'] : ''); ?>;
    }
}
@media(max-width: <?php echo $breakpoints['md'] ?>px) {
    .jankx-base .jankx-container.has-wrap {
        max-width: <?php printf('%s%s', $mobile['width'], $mobile['unit'] !='custom' ? $mobile['unit'] : ''); ?>;
    }
}
