<?php
namespace Jankx;

use Jankx\Template\Page;
use Jankx\Template\Template;

class TemplateLoader
{
    protected static $templateLoaded;

    protected $page;
    protected $pageType;

    public function __construct()
    {
        $this->page     = Page::getInstance();
    }

    protected function loadPageType()
    {
        $tag_templates = array_unique(
            array_merge(
                apply_filters('jankx_custom_template_tags', array()),
                array(
                    'is_embed',
                    'is_404',
                    'is_search',
                    'is_front_page',
                    'is_home',
                    'is_privacy_policy',
                    'is_post_type_archive',
                    'is_tax',
                    'is_attachment',
                    'is_single',
                    'is_page',
                    'is_singular',
                    'is_category',
                    'is_tag',
                    'is_author',
                    'is_date',
                    'is_archive',
                )
            )
        );
        foreach ($tag_templates as $tag_template) {
            if (call_user_func($tag_template)) {
                return str_replace('is_', '', $tag_template);
            }
        }
    }

    public function initJankxThemeSystem()
    {
        /**
         * Loads the correct template based on the visitor's url
         *
         * @package WordPress
         */
        do_action('template_redirect');

        /**
         * Filters the path of the current template before including it.
         *
         * @since 3.0.0
         *
         * @param string $template The path of the template to include.
         */
        $template = apply_filters('template_include', null);

        if (!$template) {
            return jankx();
        }
        include $template;
    }

    public function generate_home_templates()
    {
        return 'home';
    }

    public function generateSearchFiles()
    {
        $this->pageType = $this->loadPageType();
        if (!wp_using_themes()) {
            $this->initJankxThemeSystem();
        }

        $method = sprintf('generate_%s_tempates', $this->pageType);
        $callback = apply_filters(
            'jankx_generate_templates_callback',
            array($this, $method),
            $this->pageType
        );
        $templates = is_callable($callback)
            ? call_user_func(is_callable($callback))
            : $this->generate_home_templates();

        return jankx_template($templates);
    }

    public function load($templateDir)
    {
        Template::getLoader(
            $templateDir,
            apply_filters('jankx_theme_template_directory_name', 'templates'),
            apply_filters_ref_array(
                'jankx_theme_template_engine',
                [
                    'wordpress',
                    &$this
                ]
            )
        );

        // Call the Jankx Page
        add_action('wp', array($this, 'generateSearchFiles'));

        Template::setDefautLoader();
    }
}
