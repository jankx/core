<?php
namespace Jankx\UX;

use Jankx\Asset\CssItem;
use Jankx\Asset\Cache;

class Customize
{
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
        $css = CssItem::loadCustomize('loading.php');
        Cache::addGlobalCss($css);
    }
}
