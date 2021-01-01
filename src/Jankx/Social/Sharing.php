<?php
namespace Jankx\Social;

use Jankx\Option\Option;

final class Sharing
{
    protected static $_instance;
    protected static $initialized;

    protected static $social_mappings = array(
        'fbFeed' => 'fbFeed',
        'fb_feed' => 'fbFeed',
        'facebook_feed' => 'fbFeed',
        'facebook feed' => 'fbFeed',
        'fbShare' => 'fbShare',
        'fb' => 'fbShare',
        'facebook' => 'fbShare',
        'fbButton' => 'fbButton',
        'fb_button' => 'fbButton',
        'facebook_button' => 'fbButton',
        'facebook button' => 'fbButton',
        'messenger' => 'messenger',
        'fb_messenger' => 'messenger',
        'facebook_messenger' => 'messenger',
        'facebook messenger' => 'messenger',
        'tw' => 'tw',
        'twitter' => 'tw',
        'reddit' => 'true',
        'pinterest' => 'pinterest',
        'tumblr' => 'tumblr',
        'vk' => 'vk',
        'ok' => 'ok',
        'mail' => 'mail',
        'email' => 'email',
        'linkedin' => 'linkedin',
        'whatsapp' => 'whatsapp',
        'viber' => 'viber',
        'telegram' => 'telegram',
        'line' => 'line',
    );

    protected static $default_social_names = array(
        'fbFeed'    => 'Facebook Feed',
        'fbShare'   => 'Facebook',
        'fbButton'  => 'Facebook Button',
        'messenger' => 'Messenger',
        'tw'        => 'Twitter',
        'reddit'    => 'Reddit',
        'pinterest' => 'Pinterest',
        'tumblr'    => 'Tumblr',
        'vk'        => 'Vk',
        'ok'        => 'Ok',
        'mail'      => 'Mail',
        'email'     => 'Email',
        'linkedin'  => 'Linkedin',
        'whatsapp'  => 'Whatsapp',
        'viber'     => 'Viber',
        'telegram'  => 'Telegram',
        'line'      => 'Line',
    );

    protected static $social_meta_mapping = array(
        'fbFeed'=> array(
            'url' => 'url',
            'redirectUri' => 'url',
            'fbAppId' => 'facebook_app_id'
        ),
        'fbShare' => array(
            'url' => 'url',
            'redirectUri' => 'url',
            'hashtag' => 'hashtag',
            'fbAppId' => 'facebook_app_id',
        ),
        'fbButton' => array(
            'url' => 'url',
        ),
        'messenger' => array(
            'url' => 'url',
            'fbAppId' => 'facebook_app_id',
        ),
        'tw' => array(
            'url' => 'url',
            'title' => 'title',
            'hashtags' => 'hashtags'
        ),
        'reddit' => array(
            'url' => 'url',
            'title' => 'title',
        ),
        'pinterest' => array(
            'url' => 'url',
            'description' => 'description',
            'media' => 'media'
        ),
        'tumblr' => array(
            'url' => 'url',
            'title' => 'title',
            'caption' => 'description',
            'tags' => 'tags',
        ),
        'vk' => array(
            'url' => 'url',
            'title' => 'title',
            'description' => 'description',
            'image' => 'image',
            'isVkParse' => 'dataset::isVkParse',
        ),
        'ok' => array(
            'url' => 'url',
            'title' => 'title',
            'image' => 'image',
        ),
        'mail' => array(
            'url' => 'url',
            'title' => 'title',
            'description' => 'description',
            'image' => 'image',
        ),
        'email' => array(
            'url' => 'url',
            'title' => 'title',
            'description' => 'description',
            'subject' => 'title',
        ),
        'linkedin' => array(
            'url' => 'url',
            'title' => 'title',
            'description' => 'description',
        ),
        'whatsapp' => array(
            'url' => 'url',
            'title' => 'title',
        ),
        'viber' => array(
            'url' => 'url',
            'title' => 'title',
        ),
        'telegram' => array(
            'url' => 'url',
            'title' => 'title',
        ),
        'line' => array(
            'url' => 'url',
            'title' => 'title'
        ),
    );

    protected static $enabled_socials = array();

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
            $thumbnail_id = get_post_thumbnail_id($post->ID);
            $sharing_metas = array_merge($sharing_metas, array(
                'url' => get_permalink($post),
                'title' => get_the_title($post),
                'image' => $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '',
                'description' => get_the_excerpt($post),
                'hashtag' => array(),
                'tags' => array(),
                'facebook_app_id' => Option::get('facebook_app_id'),
            ));
        }

        $sharing_metas = apply_filters('jankx_socials_sharing_metas', $sharing_metas, $this);
        if (empty($sharing_metas)) {
            return;
        }

        ?>
        <script>
            var jankx_socials_sharing_metas = <?php echo json_encode($sharing_metas); ?>;
        </script>
        <?php
    }

    /**
     * Global socials setting
     */
    protected function enabled_socials()
    {
        if (empty(static::$enabled_socials)) {
            $default_socials = array(
                'facebook' => __('Facebook', 'jankx'),
                'twitter' => __('Twitter', 'jankx'),
            );
            static::$enabled_socials = $default_socials;
        }
        return apply_filters(
            'jankx_socials_sharing_enabled_socials',
            $default_socials
        );
    }

    protected function map_social_type($social)
    {
        if (isset(static::$social_mappings[$social])) {
            return static::$social_mappings[$social];
        }
        return false;
    }

    public function render_social_share_buttons($socials = null)
    {
        if (is_null($socials)) {
            $socials = $this->enabled_socials();
        }

        foreach ($socials as $maybe_social => $label_or_social) {
            $social = $maybe_social;
            $social_name = '';
            if (is_numeric($maybe_social)) {
                $social = $label_or_social;
            } else {
                $social_name = $label_or_social;
            }
            // Correct Sharing API method
            $shareAPIKey = $this->map_social_type($social);
            // The social is invalid
            if (!$shareAPIKey) {
                continue;
            }
            if (empty($social_name)) {
                $social_name = isset(static::$default_social_names[$shareAPIKey])
                    ? static::$default_social_names[$shareAPIKey]
                    : ucfirst(preg_replace('/_|-/', ' ', $social));
            }
            ?>
            <div class="social-sharing-button" data-type="<?php echo $shareAPIKey; ?>">
                <?php jankx_template(array(
                    'socials/sharing/' . $social . '-button',
                    'socials/sharing/default-button',
                ), array(
                                                          'name' => $social_name,
                                                          'type' => $social,
                )); ?>
            </div>
            <?php
        }
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
                <div class="social-buttons">
                    <?php $this->render_social_share_buttons($socials); ?>
                </div>
            </div>
        </div>

        <?php
        execute_script(jankx_template(
            'socials/sharing-script',
            array(),
            null,
            false
        ));
        ob_start();
        ?>
        <script>
            var socials_sharing_buttons = document.querySelectorAll('.social-sharing-button');
            if (socials_sharing_buttons.length > 0) {
                for (i = 0; i < socials_sharing_buttons.length; i++) {
                    social_sharing_button = socials_sharing_buttons[i];
                    if (!social_sharing_button.dataset.type) {
                        continue;
                    }
                    api = social_sharing_button.dataset.type;
                    VanillaSharing[api]
                }
            }
        </script>
        <?php
        execute_script(ob_get_clean());
    }
}
