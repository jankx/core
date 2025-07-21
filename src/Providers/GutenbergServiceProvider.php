<?php

namespace Jankx\Providers;

class GutenbergServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các dịch vụ liên quan đến Gutenberg
        $this->singleton('Jankx\Gutenberg\BlockManager', function ($container) {
            // Logic khởi tạo dịch vụ, có thể trả về null nếu không khả dụng
            try {
                return new \Jankx\Gutenberg\BlockManager();
            } catch (\Exception $e) {
                error_log('Không thể khởi tạo Jankx\Gutenberg\BlockManager: ' . $e->getMessage());
                return null;
            }
        });

        // Thêm logic để chỉ load Gutenberg khi cần thiết
        add_action('enqueue_block_editor_assets', [$this, 'loadGutenbergAssets']);
    }

    public function boot()
    {
        // Khởi động các dịch vụ nếu cần
    }

    public function loadGutenbergAssets()
    {
        // Load các tài nguyên Gutenberg chỉ khi editor được sử dụng
        // Ví dụ: wp_enqueue_script, wp_enqueue_style cho Gutenberg blocks
    }
}
