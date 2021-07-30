<?php
namespace Jankx\IconFonts\Generator;

use Jankx\IconFonts\FontIconGenerator;

class Fontastic extends FontIconGenerator
{
    protected $fontPath;
    protected $fontName;
    protected $content;
    protected $prefix;

    public function isMatched()
    {
        $icons_reference = sprintf('%s/icons-reference.html', dirname($this->fontPath));
        if (file_exists($icons_reference)) {
            return true;
        }
    }

    protected function detectPrefix()
    {
        $this->content = file_get_contents($this->fontPath);
        if (preg_match('/\[class\^\=\"([^\-]+\-)/', $this->content, $matches)) {
            return $matches[1];
        }
    }

    public function iconSelector()
    {
        $return  = "<div class='disabled'><input id='disabled' class='radio' type='radio' rel='disabled' name='settings[icon]' value='disabled' " . checked($this->menu_item_meta['icon'], 'disabled', false) . ' />';
        $return .= "<label for='disabled'></label></div>";

        foreach ($this->getGlyphMaps() as $code => $class) {
            $bits = explode('-', $code);
            $code = '&#x' . $bits[1] . '';

            $return .= "<div class='{$this->fontFamily}'>";
            $return .= "    <input class='radio' id='{$class}' type='radio' rel='{$code}' name='settings[icon]' value='{$class}' " . checked($this->menu_item_meta['icon'], $class, false) . ' />';
            $return .= "    <label rel='{$code}' for='{$class}' title='{$class}'></label>";
            $return .= '</div>';
        }

        return $return;
    }

    public function getGlyphMaps()
    {
        $prefix = $this->detectPrefix();
        $this->prefix = $prefix;
        if ($prefix) {
            if (preg_match_all(
                '/\.(atz-[^\:]+)\:before ?\{\s{1,}content\:\s?\"\\\\([^\"]+)/',
                $this->content,
                $matches
            )) {
                return array_combine(array_map(function ($code) use ($prefix) {
                    return sprintf('%s%s', $prefix, $code);
                }, $matches[2]), $matches[1]);
            }
        }
        return array();
    }

    public function getDisplayPrefix()
    {
        return $this->prefix;
    }
}
