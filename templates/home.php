<?php
/**
 * Content page index
 */

/**
 * The index page content will be render via action hook
 *
 * Hooked:
 *  - Jankx\PostLayout\PostTemplateLoader::render
 */

do_action('jankx_template_page_index_content', 'home');
