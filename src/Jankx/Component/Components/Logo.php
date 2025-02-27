<?php
namespace Jankx\Component\Components;

use Jankx\Component\Abstracts\Component;

class Logo extends Component
{
    const COMPONENT_NAME = 'logo';

    protected static $defaultProps;

    public function __construct($props = array())
    {
        if (is_null(static::$defaultProps)) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            $logo_tag = apply_filters('jankx/html/logo/tag', 'div');

            static::$defaultProps = array(
                'type' => has_custom_logo() > 0 ? 'image' : 'text',
                'show_text' => get_theme_mod('header_text', true),
                'text' => get_bloginfo('name'),
                'logo_image_id' => $custom_logo_id,
                'image_url' => isset($logo[0]) ? (string) $logo[0] : '',
                'url'       => site_url(),
                'wrap_tag'  => $logo_tag,
                'class' => '',
            );
        }

        parent::__construct($props);
    }

    public function getName()
    {
        return static::COMPONENT_NAME;
    }

    public function parseProps($props)
    {
        // Parse component props
        $this->props = wp_parse_args(
            $props,
            static::$defaultProps
        );
    }

    public function render()
    {
        if ($this->props['type'] === 'image') {
            $logo_height = get_theme_mod('logo_height') ? get_theme_mod('logo_height') : 60;
            $logo_stat   = get_option('jankx_logo_image_stat', array());

            if ($logo_height != array_get($logo_stat, 'height')) {
                $logo_image_id = array_get($this->props, 'logo_image_id');
                $height = false;
                $width  = false;
                $is_svg = false;

                if ($logo_image_id > 0) {
                    $metadata = wp_get_attachment_metadata($logo_image_id);

                    if (empty($metadata)) {
                        $image_file  = get_attached_file($logo_image_id);
                        if ($image_file) {
                            $is_svg = mime_content_type($image_file) === 'image/svg';
                            if ($is_svg) {
                                $xmlget = simplexml_load_file($image_file);
                                $xmlattributes = $xmlget->attributes();
                                $width = (string) $xmlattributes->width;
                                $height = (string) $xmlattributes->height;
                            } else {
                                $image_sizes =  getimagesize($image_file);
                                if (!empty($image_sizes)) {
                                    $width  = array_get($image_sizes, 0);
                                    $height = array_get($image_sizes, 1);
                                }
                            }
                        }
                    } else {
                        $width  = $metadata['width'];
                        $height = $metadata['height'];
                    }
                }

                if ($is_svg || $height > $logo_height) {
                    $logo_stat = array(
                        'width'  => ($width * $logo_height) / $height,
                        'height' => $logo_height
                    );
                } else {
                    $logo_stat = array(
                        'width'  => $width,
                        'height' => $height
                    );
                }

                update_option('jankx_logo_image_stat', $logo_stat);
            }

            $this->props['logo_size']        = $logo_stat;
            $this->props['logo_size_styles'] = '';

            if ($logo_stat['width']) {
                $this->props['logo_size_styles'] = sprintf(
                    ';width: %spx; height:%spx',
                    $logo_stat['width'],
                    $logo_stat['height']
                );
            }

            return $this->renderViaEngine('logo/image', $this->props, false);
        }

        return $this->renderViaEngine('logo/text', $this->props, false);
    }
}
