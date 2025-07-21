<?php

namespace Jankx\SiteLayout\Admin\Metabox;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Security\Jankx_Security_Helper;

use Jankx\SiteLayout\SiteLayout;

class PostLayout
{
    const POST_LAYOUT_META_KEY = 'jankx_post_layout';

    protected $supportPostTypes;
    protected $context;
    protected $priority;

    public function __construct()
    {
        $this->supportPostTypes = apply_filters('jankx_post_layout_post_types', array('post', 'page'));
        $this->context          = apply_filters('jankx_post_layout_context', 'advanced');
        $this->priority         = apply_filters('jankx_post_layout_priority', 'high');
    }

    public function addMetabox()
    {
        add_meta_box(
            self::POST_LAYOUT_META_KEY,
            __('Post Layouts', 'jankx'),
            array($this, 'render'),
            $this->supportPostTypes,
            $this->context,
            $this->priority
        );
    }

    public function render()
    {
        $siteLayout    = SiteLayout::getInstance();
        $currentLayout = $siteLayout->getLayout(true);
        $layouts       = $siteLayout->getSupportLayouts();
        $metaKey       = self::POST_LAYOUT_META_KEY;
        $templateFile  = sprintf(
            '%s/templates/admin/site-layout/metabox.php',
            JANKX_CORE_DIRECTORY
        );
        require_once $templateFile;
    }

    public function savePost($postID, $post)
    {
        /**
         * If the post is editing is not support post type. It will be skipped.
         */
        if (!in_array($post->post_type, $this->supportPostTypes)) {
            return;
        }

        // Verify nonce for security
        if (!wp_verify_nonce(Jankx_Security_Helper::get_post_data('post_layout_nonce', ''), 'save_post_layout')) {
            return;
        }

        if (isset($_POST[self::POST_LAYOUT_META_KEY])) {
            $layout_value = sanitize_text_field($_POST[self::POST_LAYOUT_META_KEY]);

            if (empty($layout_value)) {
                delete_post_meta($postID, self::POST_LAYOUT_META_KEY);
            } else {
                update_post_meta($postID, self::POST_LAYOUT_META_KEY, $layout_value);
            }
        }
    }
}
