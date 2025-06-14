<?php

namespace Jankx\IconFonts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface FontIconGeneratorConstract
{
    public function setFontName($fontName);
    public function setFontPath($path);
    public function setFontFamily($path);
    public function isMatched();
    public function getGlyphMaps();
    public function detectPrefix();
    public function getDisplayPrefix();
}
