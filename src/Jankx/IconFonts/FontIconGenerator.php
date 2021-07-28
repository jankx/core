<?php
namespace Jankx\IconFonts;

abstract class FontIconGenerator implements FontIconGeneratorConstract
{
    protected $fontPath;
    protected $fontName;
    protected $fontFamily;

    public function setFontName($fontName)
    {
        if (trim($fontName)) {
            $this->fontName = $fontName;
        }
    }

    public function getFontName()
    {
        return $this->fontName;
    }

    public function setFontPath($path)
    {
        if (file_exists($path)) {
            $this->fontPath = $path;
        }
    }

    public function setFontFamily($font_family)
    {
        if (trim($font_family)) {
            $this->fontFamily = $font_family;
        }
    }

    public function getFontFamily()
    {
        return $this->fontFamily;
    }
}
