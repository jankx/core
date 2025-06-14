<?php 
if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}
 ?>
<footer id="jankx-site-footer" class="jankx-site-footer">
    <?php do_action('jankx/template/footer/widgets'); ?>

    <?php do_action('jankx/template/footer/content/before'); ?>

        <?php echo jankx_component('footer'); ?>

    <?php do_action('jankx/template/footer/content/after'); ?>

</footer>