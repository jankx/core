<?php
namespace Jankx;

class Initialize
{
    public static function init()
    {
        self::theme_supports();
    }

    public static function theme_supports() {
        add_theme_support('post-thumbnails');
    }
}
