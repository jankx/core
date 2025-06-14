<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<button data-load-items="<?php echo $items; ?>" data-load-more="<?php echo $wrap_id; ?>" class="jankx-load-more-button">
    <?php _e('Load more', 'jankx'); ?>
</button>
