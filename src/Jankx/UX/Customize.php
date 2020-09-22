<?php
namespace Jankx\UX;

class Customize
{
    public function loadPresetPalettes()
    {
    }

    public function showLoading()
    {
        add_action('wp_head', array($this, 'renderLoadingCSS'));
        add_action('wp_body_open', array($this, 'renderLoading'));
    }

    public function renderLoading()
    {
        echo '<div class="jankx-loading">';
            jankx_template('common/loading');
        echo '</div>';
    }

    public function renderLoadingCSS()
    {
        jankx_template('common/loading-css');
    }
}
