<?php

namespace Jankx\UX;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Asset\CustomizableAsset;
use Jankx\Asset\Cache;
use Jankx\Customizers\DefaultPostThumbnailCustomizer;
use Jankx\Customizers\ExtraFeaturesCustomizer;
use Jankx\Customizers\PostThumbnailEffectCustomizer;
use Jankx\Customizers\SEO\LogoWrapTagCustomizer;
use Jankx\Customizers\SocialSharingCustomizer;
use Jankx\GlobalConfigs;
use Jankx\Interfaces\CustomizerInterface;
use ReflectionFunction;
use ReflectionMethod;

class Customize
{
    /**
     * @var string[]
     */
    protected $customizers = [];

    /**
     * @var \Jankx\Interfaces\CustomizerInterface[]
     */
    protected $activatedCustomizers = [];

    public function loadPresetPalettes()
    {
    }

    public function showLoading()
    {
        add_action('wp_body_open', array($this, 'renderLoading'));
        add_action('init', array($this, 'renderLoadingCSS'));
    }

    public function renderLoading()
    {
        echo '<div class="jankx-loading">';
        jankx_template('common/loading');
        echo '</div>';
    }

    public function renderLoadingCSS()
    {
        if (Cache::globalCssIsExists()) {
            return;
        }
        $css = CustomizableAsset::loadCustomize('loading.php');
        Cache::addGlobalCss($css);
    }

    public function footerWidgets($numberOfAreas)
    {
        $widget_areas = GlobalConfigs::get('customs.layout.footer.sidebars');
        if ($widget_areas > 0) {
            return $widget_areas;
        }
        return $numberOfAreas;
    }

    public function registerCustomizers()
    {
        $this->customizers = apply_filters(
            'jankx/ux/customizers',
            [
                DefaultPostThumbnailCustomizer::class,
                PostThumbnailEffectCustomizer::class,
                ExtraFeaturesCustomizer::class,
                LogoWrapTagCustomizer::class,
                SocialSharingCustomizer::class
            ]
        );
    }

    public function getTotalAgruments($callback)
    {
        if (is_array($callback)) {
            $ref = new ReflectionMethod($callback[0], $callback[1]);
        } else {
            $ref = new ReflectionFunction($callback);
        }
        $parameters = $ref->getParameters();
        $numberOfAgruments = count($parameters);

        unset($parameters, $ref);

        return $numberOfAgruments;
    }

    public function loadCustomizers()
    {
        if (is_array($this->customizers) && count($this->customizers) > 0) {
            foreach ($this->customizers as $customizerCls) {
                if (is_a($customizerCls, CustomizerInterface::class, true)) {
                    /**
                     * @var \Jankx\Interfaces\CustomizerInterface
                     */
                    $customizer = new $customizerCls();
                    if (!$customizer->isEnabled()) {
                        continue;
                    }

                    array_push($this->activatedCustomizers, $customizer);
                }
            }
        }
        do_action('jankx/customizers/custom/start');

        foreach ($this->activatedCustomizers as $customizer) {
            $callback = $customizer->getMethod();
            if (!is_callable($callback)) {
                continue;
            }

            if (!$customizer->isFilterHook()) {
                add_action(
                    $customizer->getExecuteHook(),
                    $callback,
                    $customizer->getPriority(),
                    $this->getTotalAgruments($callback)
                );
            } else {
                add_filter(
                    $customizer->getExecuteHook(),
                    $callback,
                    $customizer->getPriority(),
                    $this->getTotalAgruments($callback)
                );
            }
        }
    }
}
