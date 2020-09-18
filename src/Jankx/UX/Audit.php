<?php
namespace Jankx\UX;

class Audit
{
    public function firstContentfulPaint()
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
