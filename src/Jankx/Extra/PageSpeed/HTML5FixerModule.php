<?php

namespace Jankx\Extra\PageSpeed;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Masterminds\HTML5;

class HTML5FixerModule extends BaseModule
{
    public function init()
    {
        ob_start();
    }

    public function execute()
    {
        $html = ob_get_clean();
        $html = str_replace("as='script' rel='prefetch'", "as='script' rel='preload'", $html);

        // Parse the document. $dom is a DOMDocument.
        $html5 = new HTML5();
        $dom = $html5->loadHTML($html);

        print $html5->saveHTML($dom);
    }
}
