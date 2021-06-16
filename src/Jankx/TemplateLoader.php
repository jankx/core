<?php
namespace Jankx;

use Jankx;
use Jankx\Template\Page;
use Jankx\Template\Template;
use Jankx\TemplateEngine\Context;
use Jankx\TemplateEngine\FunctionWrapper;
use Jankx\TemplateEngine\Engines\WordPress;

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
        return 'home';
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

    public function get_single_templates()
    {
        $object = get_queried_object();
        $templates = array();

        if (! empty($object->post_type)) {
            $template = get_page_template_slug($object);
            if ($template && 0 === validate_file($template)) {
                $templates[] = $template;
            }

            $name_decoded = urldecode($object->post_name);
            if ($name_decoded !== $object->post_name) {
                $templates[] = "single-{$object->post_type}-{$name_decoded}";
            }

            $templates[] = "single-{$object->post_type}-{$object->post_name}";
            $templates[] = "single-{$object->post_type}";
        }

        $templates[] = 'single';

        return $templates;
    }

    public function get_front_page_templates() {
        return array(
            'front-page'
        );
    }

    public function generateSearchFiles()
    {
        $this->pageType = $this->loadPageType();
        if (!wp_using_themes()) {
            $this->initJankxThemeSystem();
        }

        $page = Page::getInstance();
        $page->setContext($this->pageType);

        $callback = apply_filters(
            'jankx_generate_templates_callback',
            array($this, sprintf('get_%s_templates', $this->pageType)),
            $this->pageType
        );

        if (is_callable($callback)) {
            $page->setTemplates(call_user_func($callback));
        }

        if ($this->template === false) {
            return jankx();
        }

        if (!is_null($this->template)) {
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
                    WordPress::ENGINE_NAME,
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
        add_action('jankx_prepare_render_template', array(Context::class, 'init'));
    }
}
