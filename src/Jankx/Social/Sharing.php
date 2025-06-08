<?php

namespace Jankx\Social;

use Jankx\Extra\BrandColors;
use WP_Post;
use WP_Term;
use Jankx\Option;

final class Sharing
{
    protected static $_instance;
    protected static $initialized;
    protected static $enabled_socials = array();
    protected static $agruments = array();

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
        'fbFeed' => array(
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
        return ! apply_filters('jankx/socials/sharing/disable', false);
    }

    public function load_social_share_js_deps($deps)
    {
        array_push($deps, 'sharing');

        return $deps;
    }

    public function load_social_share_css_deps($deps)
    {
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
        $sharing_metas = array(
            'facebook_app_id' => Option::get('facebook_app_id'),
        );
        $queried_object = get_queried_object();
        if (is_a($queried_object, WP_Post::class)) {
            $thumbnail_id = get_post_thumbnail_id($queried_object->ID);
            $sharing_metas = array_merge($sharing_metas, array(
                'url' => get_permalink($queried_object),
                'title' => get_the_title($queried_object),
                'image' => $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '',
                'description' => get_the_excerpt($queried_object),
                'hashtag' => array(),
                'tags' => array(),
            ));
        } elseif (is_a($queried_object, WP_Term::class)) {
            $thumbnail_id = get_term_meta($queried_object->ID, '_thumbnail_id', true);
            $sharing_metas = array_merge($sharing_metas, array(
                'url' => get_term_link($queried_object),
                'title' => apply_filters('single_term_title', $queried_object->name),
                'image' => $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '',
                'description' => apply_filters('the_content', $queried_object->description),
                'hashtag' => array(),
                'tags' => array(),
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

    protected function createAgrument($type, $variable_name)
    {
        if (!isset(static::$social_meta_mapping[$type])) {
            return;
        }

        $agrument = '{';
        foreach (static::$social_meta_mapping[$type] as $key => $mapto) {
            $agrument .= sprintf('%s: jankx_socials_sharing_metas.%s,' . PHP_EOL, $key, $mapto);
        }
        $agrument .= '}';

        static::$agruments[$variable_name] = $agrument;
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
            $this->createAgrument($shareAPIKey, sprintf('%s_agrument', $social));
            if (empty($social_name)) {
                $social_name = isset(static::$default_social_names[$shareAPIKey])
                    ? static::$default_social_names[$shareAPIKey]
                    : ucfirst(preg_replace('/_|-/', ' ', $social));
            }
            ?>
            <div class="social-sharing-button" data-type="<?php echo $shareAPIKey; ?>" data-agrument="<?php echo $social; ?>_agrument">
                <?php
                $brandColor = BrandColors::getBrandColorByName($social);
                $brandColorValue = $brandColor->getColorById(
                    apply_filters("jankx/social/sharing/{$social}/background/id", 'primary')
                );

                jankx_template(
                    array(
                        'socials/sharing/' . $social . '-button',
                        'socials/sharing/default-button',
                    ),
                    array(
                        'name' => $social_name,
                        'type' => $social,
                        'background_color' => is_null($brandColor) ? '' : $brandColor->getCssBackgroundStyle(is_null($brandColorValue) ? 'primary' : $brandColorValue->getId()),
                        'border_color' => is_null($brandColor) ? '' : $brandColor->getCssBorderStyle(is_null($brandColorValue) ? 'primary' : $brandColorValue->getId()),
                        'appearance' => $brandColorValue->getAppearance(),
                        'text_appearance' => $brandColorValue->getTextAppearance()
                    )
                ); ?>
            </div>
            <?php
        }
    }

    public function share_buttons($socials = null)
    {
        // When social sharing is not initialized log the error
        if (!static::$initialized) {
            error_log(__('Jankx social sharing is not initialized yet', 'jankx'));
            return;
        }
        $wraperClasses = ['jankx-socials-sharing'];
        ?>
        <div <?php echo jankx_generate_html_attributes([
            'class' => $wraperClasses
        ]); ?>>
            <?php $this->render_social_share_buttons($socials); ?>
        </div>

        <?php
        ob_start();
        ?>
        <script>
            function jankx_socials_sharing_find_button_from_target(ele) {
                if (!ele.dataset.type) {
                    return jankx_socials_sharing_find_button_from_target(ele.parentElement);
                }
                return ele;
            }

            <?php foreach (static::$agruments as $variable_name => $agrument) : ?>
                var <?php echo $variable_name; ?> = <?php echo $agrument; ?>;
            <?php endforeach; ?>

            var socials_sharing_buttons = document.querySelectorAll('.jankx-socials-sharing .social-sharing-button');
                if (socials_sharing_buttons.length > 0) {
                    for (i = 0; i < socials_sharing_buttons.length; i++) {
                        if (!socials_sharing_buttons[i].dataset.type) {
                            continue;
                        }
                        socials_sharing_buttons[i].addEventListener('click', function(e) {
                            button = jankx_socials_sharing_find_button_from_target(e.target);
                            VanillaSharing[button.dataset.type](
                                window[button.dataset.agrument]
                            );
                        });
                    }
                }
        </script>
        <?php
        execute_script(ob_get_clean());
    }
}
