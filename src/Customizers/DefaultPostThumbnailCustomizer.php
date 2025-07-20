<?php

namespace Jankx\Customizers;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\GlobalConfigs;
use WP_Error;

class DefaultPostThumbnailCustomizer extends BaseCustomizer
{
    public function isEnabled(): bool
    {
        return boolval(
            apply_filters(
                'jankx/ux/thubmbnail/default',
                true
            )
        );
    }

    public function getExecuteHook(): ?string
    {
        return 'wp';
    }

    public function allowSvg($mimes)
    {
        if (in_array('image/svg+xml', array_values($mimes))) {
            $mimes['svg'] = 'image/svg+xml';
        }
        return $mimes;
    }

    public function getDefaultThumbnailId()
    {
        $thumbnailSettings = GlobalConfigs::get('customs.thumbnail', [
            'default' => 'parent::assets/img/no-image.svg',
        ]);
        $defaultSettingUri = array_get($thumbnailSettings, 'default', 'parent::assets/img/no-image.svg');
        $defaultUrlArr = explode("::", $defaultSettingUri);
        $defaultThumbnailOptionKey = 'jankx/thumbnail/default/' . $defaultSettingUri;
        $defaultThumbnailId = get_option($defaultThumbnailOptionKey, null);
        if (!is_null($defaultThumbnailId)) {
            if ($defaultThumbnailId instanceof WP_Error) {
                return 0;
            }
            return $defaultThumbnailId;
        }

        $filePath = is_child_theme() && in_array($defaultUrlArr[0], ['child', 'theme']) ? get_stylesheet_directory() : get_template_directory();
        $filePath .= DIRECTORY_SEPARATOR . end($defaultUrlArr);
        $defaultThumbnailId = 0;
        if (file_exists($filePath)) {
            $tempFile = tmpfile();
            $fileMetadata = stream_get_meta_data($tempFile);
            copy($filePath, $fileMetadata['uri']);

            if (!function_exists('wp_handle_sideload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            if (!function_exists('media_handle_sideload')) {
                require_once ABSPATH . 'wp-admin/includes/media.php';
            }
            if (!function_exists('wp_read_image_metadata')) {
                require_once ABSPATH . 'wp-admin/includes/image.php';
            }

            add_filter('upload_mimes', [$this, 'allowSvg']);
            $file = [
                'name' => basename($filePath),
                'tmp_name' => $fileMetadata['uri'],
                'size' => filesize($filePath),
                'type' => mime_content_type($filePath),
                'error' => 0
            ];
            $defaultThumbnailId = media_handle_sideload($file);

            remove_filter('upload_mimes', [$this, 'allowSvg']);
        }

        update_option($defaultThumbnailOptionKey, $defaultThumbnailId, true);

        return $defaultThumbnailId;
    }

    public function applyDefaultThumbnailId($thumbnail_id)
    {
        if ($thumbnail_id > 0) {
            return $thumbnail_id;
        }
        return $this->getDefaultThumbnailId();
    }

    public function custom()
    {
        add_filter('has_post_thumbnail', '__return_true', 999);
        add_filter('post_thumbnail_id', [$this, 'applyDefaultThumbnailId']);
    }

    public function unload()
    {
        remove_filter('has_post_thumbnail', '__return_true', 999);
        remove_filter('post_thumbnail_id', [$this, 'applyDefaultThumbnailId']);
    }
}
