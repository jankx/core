<?php

namespace Jankx\UX;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class Mobile
{
    public function makeImageLookGood()
    {
        add_filter("option_medium_size_w", array($this, 'changeSizeIfIsSmall'));
    }

    public function changeSizeIfIsSmall($width)
    {
        if ($width < 520) {
            $newValue = get_option('medium_large_size_w', 520);
            add_filter("option_medium_size_h", function ($height) use ($width, $newValue) {
                return get_option(
                    'medium_large_size_h',
                    ($newValue * $height) / $width
                );
            });
            return $newValue;
        }
        return $width;
    }
}
