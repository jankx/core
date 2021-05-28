<?php
namespace Jankx;

use Jankx;
use Jankx\Template\Page;
use Jankx\Template\Template;
use Jankx\TemplateEngine\Context;
use Jankx\TemplateEngine\FunctionWrapper;

class TemplateLoader
{
    protected static $templateLoaded;

    protected $pageType;
    protected $template;

    protected function loadPageType()
    {
        $tag_templates = array_unique(
            array_merge(
                apply_filters('jankx_custom_template_tags', array()),
                array(
                    'is_embed',
                    'is_404',
                    'is_search',
                    'is_home',
                    'is_front_page',
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
        $this->template = apply_filters('template_include', false);
    }

    public function generate_templates($type)
    {
        return $type;
    }

    public function generateSearchFiles()
    {
        $this->pageType = $this->loadPageType();
        if (!wp_using_themes()) {
            $this->initJankxThemeSystem();
        }

        $method = sprintf('generate_%s_templates', $this->pageType);
        $callback = apply_filters(
            'jankx_generate_templates_callback',
            array($this, $method),
            $this->pageType
        );

        $this->templates = is_callable($callback)
            ? call_user_func(is_callable($callback))
            : $this->generate_templates($this->pageType);

        $page = Page::getInstance();
        $page->setContext($this->pageType);

        if ($this->template === false) {
            return jankx();
        } elseif (!is_null($this->template)) {
            include $this->template;
        }
    }

    public function createTemplateEngine()
    {
        Template::createEngine(
            Jankx::ENGINE_ID,
            apply_filters('jankx_theme_template_directory_name', 'templates'),
            sprintf(
                '%s/templates',
                dirname(JANKX_FRAMEWORK_FILE_LOADER)
            ),
            apply_filters_ref_array(
                'jankx_theme_template_engine',
                [
                    'wordpress',
                    &$this
                ]
            )
        );
    }

    public function load()
    {
        add_action('after_setup_theme', array($this, 'createTemplateEngine'), 15);

        // Call the Jankx Page
        add_action('wp', array($this, 'generateSearchFiles'), 30);

        // Sharing data
        add_action('jankx_prepare_render_template', array($this, 'initSharingData'));
    }

    public function initSharingData()
    {
        Context::shares(array(
            'open_container' => new FunctionWrapper('jankx_open_container', array(), true),
            'close_container' => new FunctionWrapper('jankx_close_container', array(), true),
        ));
    }
}
