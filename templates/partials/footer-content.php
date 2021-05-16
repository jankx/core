<footer id="jankx-site-footer" class="jankx-site-footer">

    <?php do_action('jankx_template_before_footer_content'); ?>

        <?php
            $site_name = get_bloginfo('name');
            $site_url = site_url();
            jankx_component(
                'footer',
                array(
                    'copyright' => sprintf(
                        __('Copyright &copy; %1$d <a href="%2$s">%3$s</a>.', 'jankx'),
                        date('Y'),
                        $site_url,
                        $site_name
                    ),
                    'site_name' => $site_name,
                    'site_url' => $site_url,
                ),
                array(
                    'echo' => true
                )
            ); ?>

    <?php do_action('jankx_template_after_footer_content'); ?>

</footer>