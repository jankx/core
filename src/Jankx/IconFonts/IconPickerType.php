<?php

namespace Jankx\IconFonts;

use Icon_Picker_Type_Font;
use Icon_Picker_Loader;

class IconPickerType extends Icon_Picker_Type_Font
{
    /**
     * Icon type ID
     *
     * @since  0.1.0
     * @access protected
     * @var    string
     */
    protected $id = '';

    /**
     * Icon type name
     *
     * @since  0.1.0
     * @access protected
     * @var    string
     */
    protected $name;

    /**
     * Icon type version
     *
     * @since  0.1.0
     * @access protected
     * @var    string
     */
    protected $version = '4.7.0';

    /**
     * Stylesheet ID
     *
     * @since  0.1.0
     * @access protected
     * @var    string
     */
    protected $stylesheet_id;

    /**
     * @var \Jankx\IconFonts\FontIconGenerator
     */
    protected $fontGenerator;

    public function register_assets(Icon_Picker_Loader $loader)
    {
    }


    public function set_font_data($data)
    {
        $fontGenerator = GeneratorManager::detectGenerator($data['name'], $data['path'], $data['font-family'], $data['version']);

        $this->id = trim($fontGenerator->detectPrefix(), '-');
        $this->stylesheet_id = $fontGenerator->getFontFamily();
        $this->name = $fontGenerator->getFontName();
        $this->version = $data['version'];

        $this->fontGenerator = $fontGenerator;
    }

    /**
     * Get icon groups
     *
     * @since  0.1.0
     * @return array
     */
    public function get_groups()
    {
        /**
         * Filter genericon groups
         *
         * @since 0.1.0
         * @param array $groups Icon groups.
         */
        $groups = apply_filters("icon_picker_{$this->stylesheet_id}_groups", $this->fontGenerator->getDisplayPrefix());

        return $groups;
    }



    /**
     * Get icon names
     *
     * @since  0.1.0
     * @return array
     */
    public function get_items()
    {
        $mapItems = $this->fontGenerator->getGlyphMaps();
        $items    = array_map(function ($item) {
            return [
                'group' => $this->stylesheet_id,
                'id' => $item,
                'name' => $item,
            ];
        }, $mapItems);

        /**
         * Filter genericon items
         *
         * @since 0.1.0
         * @param array $items Icon names.
         */
        $items = apply_filters("icon_picker_{$this->stylesheet_id}_items", array_values($items));

        return $items;
    }
}
