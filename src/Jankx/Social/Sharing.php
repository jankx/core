<?php
namespace Jankx\Social;

use Jankx\Option\Option;

final class Sharing
{
    protected static $_instance;
    protected static $initialized;

    protected static $social_mappings = array(
        'fbFeed' => array(
            'fb_feed',
            'facebook_feed',
            'facebook feed',
        ),
        'fbShare' => array(
            'fb',
            'facebook',
        ),
        'fbButton' => array(
            'fb_button',
            'facebook_button',
            'facebook button'
        ),
        'messenger' => array(
            'messenger',
            'fb_messenger',
            'facebook_messenger',
            'facebook messenger'
        ),
        'tw' => array(
            'tw',
            'twitter'
        ),
        'reddit' => true,
        'pinterest' => true,
        'tumblr' => true,
        'vk' => true,
        'ok' => true,
        'mail' => true,
        'email' => true,
        'linkedin' => true,
        'whatsapp' => true,
        'viber' => true,
        'telegram' => true,
        'line' => true,
    );

    public static function get_instance()
    {
        if (is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    private function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'init'), 5);
    }

    public function init()
    {
        if (!$this->current_page_is_enabled_social_share()) {
            return;
        }
        $this->init_scripts();
        $this->init_sharing_info();
    }

    public function init_scripts()
    {
        add_action('jankx_asset_js_dependences', array($this, 'load_social_share_js_deps'));
        add_action('jankx_asset_css_dependences', array($this, 'load_social_share_css_deps'));
    }

    protected function current_page_is_enabled_social_share()
    {
        if (is_single()) {
            global $post;

            $allowe_post_types = apply_filters(
                'jankx_socials_sharing_allowed_post_types',
                array('post')
            );
            if (in_array($post->post_type, $allowe_post_types)) {
                return true;
            }
        }
        return false;
    }

    public function load_social_share_js_deps($deps)
    {
        array_push($deps, 'tether-drop');
        array_push($deps, 'sharing');

        return $deps;
    }

    public function load_social_share_css_deps($deps)
    {
        array_push($deps, 'tether-drop');
        return $deps;
    }

    public function init_sharing_info()
    {
        static::$initialized = true;
        add_action(
            'wp_body_open',
            array($this, 'render_global_metas')
        );
    }

    public function render_global_metas()
    {
        $sharing_metas = array();
        if (is_singular()) {
            global $post;
            $sharing_metas = array_merge($sharing_metas, array(
                'url' => get_permalink($post),
                'title' => get_the_title($post),
                'description' => get_the_excerpt($post),
                'facebook_app_id' => Option::get('facebook_app_id'),
            ));
        }
        if (empty($sharing_metas)) {
            return;
        }

        ?>
        <script>
            var jankx_socials_sharing_metas = <?php echo json_encode($sharing_metas); ?>;
        </script>
        <?php
    }

    protected function enabled_socials()
    {
    }

    public function render_social_share_buttons($socials = null)
    {
    }

    public function share_buttons($socials = null)
    {
        // When social sharing is not initialized log the error
        if (!static::$initialized) {
            return error_log(__('Jankx social sharing is not initialized yet', 'jankx'));
        }
        ?>
        <div class="jankx-socials-sharing drop-styles">
            <div class="jankx-sharing-button">
                <?php jankx_template('socials/sharing-button'); ?>
            </div>
            <div id="jankx-sharing-content" class="sharing-content">
                <?php $this->render_social_share_buttons($socials); ?>
            </div>
        </div>

        <?php
        execute_script(jankx_template(
            'socials/sharing-script',
            array(),
            null,
            false
        ));
    }
}
