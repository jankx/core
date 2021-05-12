<?php
namespace Jankx;

use Jankx\Template\Page;
use Jankx\Template\Template;

class TemplateLoader
{
    protected static $templateLoaded;

    public function load()
    {
        if (!static::$templateLoaded) {
            /**
             * Create a flag to check Jankx template library is loaded
             */
            static::$templateLoaded = true;

            $pageTemplate = Page::getInstance();

            // Call the Jankx Page
            add_filter('template_include', array($pageTemplate, 'callTemplate'), 99);
        }
        Template::setDefautLoader();
    }
}
