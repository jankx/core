<?php

namespace Jankx;

use WP_Post_Type;
use WP_User;
use Jankx;
use Jankx\Template\Page;
use Jankx\Template\Template;
use Jankx\TemplateEngine\Context;
use Jankx\TemplateEngine\Engines\WordPress;
use Jankx\PostLayout\PostLayoutManager;

class TemplateAndLayout
{
    protected static $instance;
    protected static $templateLoaded;

    protected $pageType;
    protected $template;
    protected $templateEngine;
    protected $templateFile;

    public static function get_instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
    }

    public function detectCurrentPageType()
    {
        do_action('jankx_template_loader_page_type', $this->pageType, $this);
        if (is_null($this->pageType)) {
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
                    $this->pageType = strpos($tag_template, 'archive') !== false
                        ? 'archive'
                        : str_replace('is_', '', $tag_template);
                    break;
                }
            }
        }
        return empty($this->pageType) ? 'index' : $this->pageType;
    }

    public function setTemplateFile($templateFile)
    {
        $this->templateFile = $templateFile;
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
        $this->templateFile = apply_filters('template_include', false);
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

    public function get_tax_templates()
    {
        $term = get_queried_object();

        $templates = array();

        if (! empty($term->slug)) {
            $taxonomy = $term->taxonomy;

            $slug_decoded = urldecode($term->slug);
            if ($slug_decoded !== $term->slug) {
                $templates[] = "taxonomy-$taxonomy-{$slug_decoded}";
            }

            $templates[] = "taxonomy-$taxonomy-{$term->slug}";
            $templates[] = "taxonomy-$taxonomy";
        }
        $templates[] = 'taxonomy';

        return $templates;
    }

    public function get_archive_templates()
    {
        $post_types = array_filter((array) get_query_var('post_type'));

        $templates = array();

        if (count($post_types) == 1) {
            $post_type   = reset($post_types);
            $templates[] = "archive-{$post_type}";
        }
        $templates[] = 'archive';

        return $templates;
    }

    public function get_post_type_archive_templates()
    {
        $post_type = get_query_var('post_type');
        if (is_array($post_type)) {
            $post_type = reset($post_type);
        }

        $obj = get_post_type_object($post_type);
        if (! ( $obj instanceof WP_Post_Type ) || ! $obj->has_archive) {
            return '';
        }

        return $this->get_archive_templates();
    }

    function get_category_templates()
    {
        $category = get_queried_object();

        $templates = array();

        if (! empty($category->slug)) {
            $slug_decoded = urldecode($category->slug);
            if ($slug_decoded !== $category->slug) {
                $templates[] = "category-{$slug_decoded}";
            }

            $templates[] = "category-{$category->slug}";
            $templates[] = "category-{$category->term_id}";
        }
        $templates[] = 'category';
        $templates[] = 'archive';

        return $templates;
    }

    function get_tag_templates()
    {
        $tag = get_queried_object();

        $templates = array();

        if (! empty($tag->slug)) {
            $slug_decoded = urldecode($tag->slug);
            if ($slug_decoded !== $tag->slug) {
                $templates[] = "tag-{$slug_decoded}";
            }

            $templates[] = "tag-{$tag->slug}";
            $templates[] = "tag-{$tag->term_id}";
        }
        $templates[] = 'tag';
        $templates[] = 'archive';

        return $templates;
    }

    public function get_404_templates()
    {
        return array(
            'not_found',
            '404'
        );
    }

    function get_attachment_templates()
    {
        $attachment = get_queried_object();

        $templates = array();

        if ($attachment) {
            if (false !== strpos($attachment->post_mime_type, '/')) {
                list( $type, $subtype ) = explode('/', $attachment->post_mime_type);
            } else {
                list( $type, $subtype ) = array( $attachment->post_mime_type, '' );
            }

            if (! empty($subtype)) {
                $templates[] = "{$type}-{$subtype}";
                $templates[] = "{$subtype}";
            }
            $templates[] = "{$type}";
        }
        $templates[] = 'attachment';

        return $templates;
    }

    public function get_page_templates()
    {
        $id       = get_queried_object_id();
        $template = get_page_template_slug();
        $pagename = get_query_var('pagename');

        if (! $pagename && $id) {
            // If a static page is set as the front page, $pagename will not be set.
            // Retrieve it from the queried object.
            $post = get_queried_object();
            if ($post) {
                $pagename = $post->post_name;
            }
        }

        $templates = array();
        if ($template && 0 === validate_file($template)) {
            $templates[] = $template;
        }
        if ($pagename) {
            $pagename_decoded = urldecode($pagename);
            if ($pagename_decoded !== $pagename) {
                $templates[] = "page-{$pagename_decoded}";
            }
            $templates[] = "page-{$pagename}";
        }
        if ($id) {
            $templates[] = "page-{$id}";
        }
        $templates[] = 'page';

        return $templates;
    }

    function get_embed_templates()
    {
        $object = get_queried_object();

        $templates = array();

        if (! empty($object->post_type)) {
            $post_format = get_post_format($object);
            if ($post_format) {
                $templates[] = "embed-{$object->post_type}-{$post_format}";
            }
            $templates[] = "embed-{$object->post_type}";
        }

        $templates[] = 'embed';

        return $templates;
    }

    public function get_home_templates()
    {
        return ['home'];
    }

    function get_author_templates()
    {
        $author = get_queried_object();

        $templates = array();

        if ($author instanceof WP_User) {
            $templates[] = "author-{$author->user_nicename}";
            $templates[] = "author-{$author->ID}";
        }
        $templates[] = 'author';

        return $templates;
    }

    public function get_front_page_templates()
    {
        return array(
            'front-page',
            'page'
        );
    }

    public function include()
    {

        if (!constant('WP_USE_THEMES') || is_robots() || is_feed() || is_trackback() || is_favicon()) {
            return;
        }

        $this->pageType = $this->detectCurrentPageType();
        $templates = array();
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

        // var_dump("{$this->pageType}_template_hierarchy");die;

        // archive_template_hierarchy

        if (is_callable($callback)) {
            $templates = call_user_func($callback);
            $page->setTemplates(
                apply_filters("{$this->pageType}_template_hierarchy", $templates)
            );
        }

        do_action_ref_array('jankx/template/renderer/pre', array(
            &$page,
            $this->templateFile,
            &$this->templateEngine,
            &$templates,
            $this
        ));

        if (function_exists('jankx_is_support_block_template') && jankx_is_support_block_template()) {
            // setup Gutenberg structure
            locate_block_template(
                is_null($this->templateFile) ? $page->getContext() : $this->templateFile,
                $page->getContext(),
                $page->getTemplates()
            );
        }

        if (empty($this->templateFile) || apply_filters('jankx/template/engine/jankx/force-enable', false)) {
            return jankx();
        }

        if (!empty($this->templateFile)) {
            include $this->templateFile;
        } else {
            error_log(__('The template not found!', 'jankx'));
        }
    }

    public function createTemplateEngine()
    {
        $this->templateEngine = Template::createEngine(
            Jankx::ENGINE_ID,
            apply_filters('jankx/theme/template/directory', 'templates'),
            sprintf(
                '%s/templates',
                dirname(JANKX_FRAMEWORK_FILE_LOADER)
            ),
            apply_filters_ref_array(
                'jankx/template/engine/apply',
                [
                    WordPress::ENGINE_NAME,
                    &$this
                ]
            )
        );
        static::$templateLoaded = true;
    }

    public function load()
    {
        // Call the Jankx Page
        add_action('wp', array($this, 'include'), 30);

        // Sharing data
        add_action('jankx/template/renderer/pre', array(Context::class, 'init'));
    }

    public static function getTemplateEngine()
    {
        if (static::$templateLoaded) {
            $instance = static::get_instance();

            return $instance->templateEngine;
        }
    }

    public static function getPostLayoutManager()
    {
        if (static::$templateLoaded) {
            $instance = static::get_instance();

            return PostLayoutManager::getInstance(
                $instance->templateEngine
            );
        }
    }
}
