<?php
namespace Jankx\Integration\Elementor;

use Jankx\Template\Page;
use Jankx\SiteLayout\SiteLayout;

class Layout
{
    public function customTemplates()
    {
        add_filter('jankx_template_pre_get_current_site_layout', array($this, 'makeFullwidthLayout'));
        add_action('template_redirect', array($this, 'integrateTemplateClasses'), 30);
    }

    public function makeFullwidthLayout($pre)
    {
        $page = Page::getInstance();
        $templateFile = $page->getTemplateFile();

        if (preg_match('/elementor\/.+\/templates\/header\-footer\.php$/', $templateFile)) {
            return SiteLayout::LAYOUT_FULL_WIDTH;
        }
        return $pre;
    }

    public function integrateTemplateClasses()
    {
        if (is_singular() && \Elementor\Plugin::instance()->db->is_built_with_elementor(get_the_ID())) {
            add_filter('jankx_template_disable_main_content_sidebar_container', '__return_true');

            if (apply_filters('jankx_template_enable_compatible_elementor_container', true)) {
                add_action('jankx_template_before_open_container', array($this, 'openElementorSelectionClass'));
                add_action('jankx_template_after_close_container', array($this, 'closeElementorSelectionClass'));

                add_filter('jankx_template_disable_base_css', '__return_true');
                add_filter('jankx_template_the_container_classes', array($this, 'addElementorContainerClass'));
            }
        }
    }

    public function removeContentSidebarContainer()
    {
        remove_action('jankx_template_after_header', array($this, 'openJankxSidebarContentContainer'), 20);
        remove_action('jankx_template_before_footer', array($this, 'closeJankxSidebarContentContainer'), 4);
    }

    public function openElementorSelectionClass()
    {
        echo '<div class="elementor-section elementor-section-boxed jankx-elementor">';
    }

    public function closeElementorSelectionClass()
    {
        echo '</div><!-- End elementor-section by Jankx framework -->';
    }

    public function addElementorContainerClass($classes)
    {
        $classes[] = 'elementor-container';

        return $classes;
    }
}
