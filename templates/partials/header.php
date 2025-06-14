<?php
if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}
 ?>
<!DOCTYPE html>
<html
    class="<?php echo isset($html_class) ? implode(' ', (array) $html_class) : 'no-js'; ?>"
    <?php language_attributes(); ?>
>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <title><?php echo jankx_frontend_title(); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>

        <?php do_action('jankx/template/header/before'); ?>
            <?php
            if (jankx_get_site_layout() !== 'jankx-fullpage') {
                jankx_component(
                    'header',
                    apply_filters('jankx_component_header_props', array(
                    'preset' => 'default'
                    )),
                    true
                );
            }
            ?>
        <?php do_action('jankx/template/header/after'); ?>
