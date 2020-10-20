<?php
namespace Jankx\Integration\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

abstract class BaseWidget extends Widget_Base {
    protected function getImageSizeName($sizeName) {
        switch ($sizeName) {
            case 'thumbnail':
                return __('Thumbnail');
            default:
                return preg_replace_callback(array(
                    '/^(\w)/',
                    '/(\w)([\-|_]{1,})/'
                ), function($matches){
                    if (isset($matches[2])) {
                        return sprintf('%s ', $matches[1]) ;
                    } elseif(isset($matches[1])) {
                        return strtoupper($matches[1]);
                    }
                }, $sizeName);
        }
    }

    protected function getImageSizes() {
        $ret = array();
        foreach(get_intermediate_image_sizes() as $imageSize) {
            $ret[$imageSize] = $this->getImageSizeName($imageSize);
        }
        $ret['full'] = __('Full size', 'jankx');
        $ret['custom'] = __('Custom Size', 'jankx');

        return $ret;
    }

    public function addThumbnailControls() {
        $this->add_control(
            'show_post_thumbnail',
            [
                'label' => __('Show Thumbnail', 'jankx'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'jankx'),
                'label_off' => __('Hide', 'jankx'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'thumbnail_size',
            [
                'label' => __('Image size', 'jankx'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getImageSizes(),
                'default' => 'thumbnail',
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label' => __('Image Width', 'jankx'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' =>5,
                'default' => 400,
                'condition' => array(
                    'thumbnail_size' => 'custom'
                )
            ]
        );

        $this->add_control(
            'image_height',
            [
                'label' => __('Image Height', 'jankx'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' =>5,
                'default' => 320,
                'condition' => array(
                    'thumbnail_size' => 'custom'
                )
            ]
        );
    }
}
