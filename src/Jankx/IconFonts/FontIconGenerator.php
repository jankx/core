<?php
namespace Jankx\IconFonts;

abstract class FontIconGenerator implements FontIconGeneratorConstract
{
    protected $fontPath;
    protected $fontName;
    protected $fontFamily;
    protected $version;

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

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getDisplayPrefix()
    {
        return $this->prefix;
    }
}
