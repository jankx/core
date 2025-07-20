<?php

namespace Jankx\Security;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class Upload
{
    public function allow_new_file_types()
    {
        add_filter('upload_mimes', array($this, 'allow_upload_svg_files'));
    }

    public function allow_upload_svg_files($mimes)
    {
        // Adding SVG format to allowed upload mime types
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }
}
