<?php

namespace Jankx\Extra\Features;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\Adapter\Options\Helper;

class AuthorBoxFeature
{
    public function __construct()
    {
        $appendToHook = Helper::getOption('author_box_place', 'jankx/template/main_content_sidebar/end');
        $appendToHookPosition = Helper::getOption('author_box_position', 55);

        add_action($appendToHook, [$this, 'registerAuthorBox'], $appendToHookPosition);
    }


    protected function getSocialLinks($userId)
    {
        $activeSocialTypes = apply_filters('jankx/author/socials', ['facebook', 'instagram', 'linkedin', 'myspace', 'pinterest', 'soundcloud', 'tumblr', 'wikipedia', 'twitter', 'youtube', 'mastodon']);
        $links = [];
        foreach ($activeSocialTypes as $activeSocialType) {
            $link = jankx_get_user_link($userId, $activeSocialType);
            if (!empty($link)) {
                $links[$activeSocialType] = $link;
            }
        }

        return $links;
    }

    public function registerAuthorBox()
    {
        $post = &$GLOBALS['post'];
        $user = get_user($post->post_author);
        if (empty($user)) {
            return;
        }

        $biographical_info = get_user_meta($user->ID, 'description', true);
        $links = $this->getSocialLinks($user->ID);

        if ($biographical_info || !empty($links)) {
            jankx_template(
                'author/author_box',
                apply_filters('jankx/post/author_box/data', [
                    'author_name' => $user->display_name,
                    'avatar_url' => jankx_get_user_avatar_url($user),
                    'url' => $user->user_url,
                    'links' => $links,
                    'biographical_info' => apply_filters('the_content', $biographical_info)
                ])
            );
        }
    }
}
