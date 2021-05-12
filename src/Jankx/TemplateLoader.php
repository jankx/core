<?php
namespace Jankx;

use Jankx\Template\Page;
use Jankx\Template\Template;

class TemplateLoader
{
    protected static $templateLoaded;

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

        $pageTemplate = Page::getInstance();

            // Call the Jankx Page
        add_filter('template_include', array($pageTemplate, 'callTemplate'), 99);

        Template::setDefautLoader();
    }
}
