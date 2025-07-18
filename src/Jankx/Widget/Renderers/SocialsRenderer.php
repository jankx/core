<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx;
use Jankx\Adapter\Options\Helper;
use Jankx\Template\Template;

class SocialsRenderer extends Base
{
    protected $iconFontPrefix = 'fa fa-';

    protected $options = [
        'icon-style' => 'regular',
        'icon-type' => 'file'
    ];

    protected function getIcons()
    {
        $icons = [];

        $iconDirectories = [
            implode(DIRECTORY_SEPARATOR, [get_template_directory(), 'assets', 'icons', 'socials']),
        ];
        if (is_child_theme()) {
            $iconDirectories[] = implode(DIRECTORY_SEPARATOR, [get_stylesheet_directory(), 'assets', 'icons', 'socials']);
        }

        foreach ($iconDirectories as $iconDirectory) {
            if (PHP_OS === 'WINNT') {
                $iconDirectory = str_replace('/', DIRECTORY_SEPARATOR, $iconDirectory);
            }
            foreach (glob($iconDirectory . '/*.{png,gif,jpeg,jpg,svg}', GLOB_BRACE) as $iconFile) {
                $iconInfos = pathinfo($iconFile);
                if (empty($iconInfos)) {
                    continue;
                }

                $icon = [
                    'path' => $iconFile,
                    'type' => $iconInfos['extension'],
                ];
                if ($this->getIconType() === 'font') {
                    $icon = $this->resolveIconFont($icon, $iconInfos);
                }
                $icons[$iconInfos['filename']] = $icon;
            }
        }

        return apply_filters('jankx/socials/icons', $icons);
    }


    protected function resolveIconFont($currentIcon, $iconInfos)
    {
        /**
         * Default font is Fontwesome 6
         * Reference: https://fontawesome.com/search?f=brands&o=r
         */
        $fontPrefix = apply_filters(
            'jankx/socials/icons/font/prefix',
            $this->iconFontPrefix,
            $currentIcon,
            $iconInfos
        );

        return [
            'font' => $fontPrefix . $iconInfos['filename'],
        ];
    }


    protected function resolveIcon($name, $listIcons, $svgContentIfAvailable)
    {
        if (!isset($listIcons[$name])) {
            return $name;
        }

        $socialInfo = $listIcons[$name];
        if (isset($socialInfo['font'])) {
            return $socialInfo['font'];
        }
        if (!($socialInfo['type'] === 'svg' && $svgContentIfAvailable)) {
            return [
                'type' => 'image',
                'url' => jankx_get_path_url($socialInfo['path']),
            ];
        }

        return [
            'type' => 'html',
            'html' => file_get_contents($socialInfo['path'])
        ];
    }

    public function getIconType()
    {
        return apply_filters(
            'jankx/socials/icons/style',
            $this->getOption('icon-type', 'file')
        );
    }

    public function render()
    {
        $engine = Template::getEngine(Jankx::ENGINE_ID);
        $svgContentIfAvailable = apply_filters('jankx/socials/icons/svg/enabled', true);

        $socials        = [];
        $socialIcons    = $this->getIcons();
        $socialSupports = apply_filters(
            'jankx/socials/list',
            array_unique(array_keys($socialIcons))
        );

        foreach ($socialSupports as $socialName) {
            $optionKey = sprintf('%s_url', str_replace('-', '_', $socialName));
            $value = Helper::getOption(
                $optionKey,
                apply_filters("jankx/social/{$socialName}/url/default", '')
            );

            $socials[$socialName]['url'] = $value;
            $socials[$socialName]['icon'] = $this->resolveIcon($socialName, $socialIcons, $svgContentIfAvailable);
            $socials[$socialName]['target'] = '_blank';
        }

        /**
         * Template of font icons
         */
        $templateFile = 'socials/connects';

        if ($this->getIconType() === 'file') {
            $templateFile = 'socials/icon-file-connects';
        }

        echo $engine->render(
            $templateFile,
            [
                'socials' => apply_filters('jankx/social/networks', $socials, $socialSupports, $socialIcons),
                'svg_content_if_available' => $svgContentIfAvailable,
            ]
        );
    }
}
