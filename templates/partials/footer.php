    <?php do_action('jankx/template/footer/before'); ?>
        <?php if (jankx_get_site_layout() !== 'jankx-fullpage' && jankx_template_has_footer()) : ?>
            <?php jankx_template('partials/footer-content'); ?>
        <?php endif; ?>
    <?php do_action('jankx_template_after_footer'); ?>

    <?php wp_footer(); ?>
    </body>
</html>
