<?php
namespace Jankx\UX;

class Mobile
{
    public function makeImageLookGood()
    {
        add_filter("option_medium_size_w", array($this, 'changeSizeIfIsSmall'));
    }

    public function changeSizeIfIsSmall($width)
    {
        if ($width < 520) {
            $newValue = 520;
            add_filter("option_medium_size_h", function ($height) use ($width, $newValue) {
                return ($newValue * $height) / $width;
            });
            return $newValue;
        }
        return $width;
    }
}
