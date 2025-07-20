<?php

namespace Jankx\Widget\Renderers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Option\Option;

abstract class FacebookRenderer extends Base
{
    protected static $facebook_app_id;
    protected static $isRendered = false;

    public function __construct()
    {
        static::loadFacebookAppId();
        static::writeFacebookSdkScript();
    }

    protected static function loadFacebookAppId()
    {
        if (!is_null(static::$facebook_app_id)) {
            return;
        }
        static::$facebook_app_id = Option::get('facebook_app_id');
    }

    public static function _script()
    {
        static::$isRendered = true;
        ?>
        <div id="fb-root"></div>
        <script
            async
            defer
            crossorigin="anonymous"
            src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v9.0&appId=<?php echo static::$facebook_app_id; ?>&autoLogAppEvents=1"
            nonce="vceBT42E"
        >
        </script>
        <?php
    }

    public static function writeFacebookSdkScript()
    {
        if (is_null(static::$facebook_app_id) || static::$isRendered) {
            return;
        }
        add_action('wp_footer', array(__CLASS__, '_script'));
    }
}
