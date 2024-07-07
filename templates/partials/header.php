<!DOCTYPE html>
<html
    class="<?php echo isset($html_class) ? implode(' ', (array) $html_class) : 'no-js'; ?>"
    <?php language_attributes(); ?>
>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <title><?php echo jankx_frontend_title(); ?></title>
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
