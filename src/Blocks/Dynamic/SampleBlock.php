<?php

namespace Jankx\Blocks\Dynamic;

use WP_Block;

/**
 * Sample Block
 *
 * A sample dynamic Gutenberg Block for demonstration purposes.
 *
 * @package Jankx\Blocks\Dynamic
 */
class SampleBlock
{
    /**
     * Block name
     *
     * @var string
     */
    protected $name = 'jankx/sample-block';

    /**
     * Register the block
     */
    public function register(): void
    {
        register_block_type($this->name, [
            'api_version' => 2,
            'title' => __('Sample Block', 'jankx'),
            'description' => __('A sample dynamic block for Jankx framework', 'jankx'),
            'category' => 'common',
            'icon' => 'smiley',
            'keywords' => ['sample', 'jankx', 'dynamic'],
            'supports' => [
                'html' => false,
                'align' => true,
            ],
            'attributes' => [
                'title' => [
                    'type' => 'string',
                    'default' => __('Sample Title', 'jankx'),
                ],
                'content' => [
                    'type' => 'string',
                    'default' => __('This is a sample content for the Jankx dynamic block.', 'jankx'),
                ],
                'alignment' => [
                    'type' => 'string',
                    'default' => 'none',
                ],
            ],
            'render_callback' => [$this, 'render'],
            'editor_script' => 'jankx-sample-block-editor',
            'editor_style' => 'jankx-sample-block-editor',
            'style' => 'jankx-sample-block',
        ]);

        // Register block assets
        $this->registerAssets();
    }

    /**
     * Register block assets
     */
    protected function registerAssets(): void
    {
        // Editor script
        wp_register_script(
            'jankx-sample-block-editor',
            JANKX_ASSETS_URI . '/blocks/sample-block/editor.js',
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'],
            JANKX_FRAMEWORK_VERSION,
            true
        );

        // Editor style
        wp_register_style(
            'jankx-sample-block-editor',
            JANKX_ASSETS_URI . '/blocks/sample-block/editor.css',
            ['wp-edit-blocks'],
            JANKX_FRAMEWORK_VERSION
        );

        // Frontend style
        wp_register_style(
            'jankx-sample-block',
            JANKX_ASSETS_URI . '/blocks/sample-block/style.css',
            [],
            JANKX_FRAMEWORK_VERSION
        );
    }

    /**
     * Render the block content
     *
     * @param array $attributes Block attributes
     * @param string $content Block content
     * @param WP_Block $block Block instance
     * @return string Rendered block HTML
     */
    public function render(array $attributes, string $content, WP_Block $block): string
    {
        $title = !empty($attributes['title']) ? esc_html($attributes['title']) : __('Sample Title', 'jankx');
        $content = !empty($attributes['content']) ? wp_kses_post($attributes['content']) : __('This is a sample content for the Jankx dynamic block.', 'jankx');
        $alignment = !empty($attributes['alignment']) ? esc_attr($attributes['alignment']) : 'none';
        $align_class = $alignment !== 'none' ? " align{$alignment}" : '';

        $output = '<div class="wp-block-jankx-sample-block' . $align_class . '">';
        $output .= '<h3 class="sample-block-title">' . $title . '</h3>';
        $output .= '<div class="sample-block-content">' . $content . '</div>';
        $output .= '</div>';

        return $output;
    }
}