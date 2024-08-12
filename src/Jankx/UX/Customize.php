<?php

namespace Jankx\UX;

use Jankx\Asset\CustomizableAsset;
use Jankx\Asset\Cache;
use Jankx\Customizers\DefaultPostThumbnail;
use Jankx\GlobalConfigs;
use Jankx\Interfaces\CustomizerInterface;

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
        $widget_areas = GlobalConfigs::get('site.layout.footer.sidebars');
        if ($widget_areas > 0) {
            return $widget_areas;
        }
        return $numberOfAreas;
    }

    public function registerCustomizers()
    {
        $this->customizers =  apply_filters(
            'jankx/ux/customizers',
            [
                DefaultPostThumbnail::class,
            ]
        );
    }


    public function loadCustomizers()
    {
        if (is_array($this->customizers) && count($this->customizers) > 0) {
            foreach ($this->customizers as $customizerCls) {
                if (is_a($customizerCls, CustomizerInterface::class, true)) {
                    /**
                     * @var \Jankx\Interfaces\CustomizerInterface
                     */
                    $customizer = \call_user_func([$customizerCls, 'getInstance']);
                    if (!$customizer->isEnabled()) {
                        continue;
                    }

                    array_push($this->activatedCustomizers, $customizer);
                }
            }
        }
        do_action('jankx/customizers/custom/start');

        foreach ($this->activatedCustomizers as $customizer) {
            add_action($customizer->getExecuteHook(), [$customizer, 'custom']);
        }
    }
}
