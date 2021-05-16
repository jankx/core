<!DOCTYPE html>
<html
    class="<?php echo isset($html_class) ? implode(' ', (array) $html_class) : 'no-js'; ?>"
    <?php language_attributes(); ?>
>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <title><?php wp_title(); ?></title>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>

        <?php do_action('jankx_template_before_header'); ?>
            <?php
            jankx_component(
                'header',
                apply_filters('jankx_component_header_props', array(
                    'preset' => 'default'
                )),
                apply_filters('jankx_component_header_options', array(
                    'echo' => true,
                ))
            );
            ?>
        <?php do_action('jankx_template_after_header'); ?>
