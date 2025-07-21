<?php

namespace Jankx\Providers;

class PostLayoutServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các dịch vụ liên quan đến post-layout
        $this->singleton('Jankx\PostLayout\PostLayoutManager', function ($container) {
            // Logic khởi tạo dịch vụ, có thể trả về null nếu không khả dụng
            try {
                // Giả sử template engine đã được đăng ký ở nơi khác
                $templateEngine = $container->make('Jankx\TemplateEngine\Engine\Plates');
                if ($templateEngine) {
                    return new \Jankx\PostLayout\PostLayoutManager($templateEngine);
                }
                return null;
            } catch (\Exception $e) {
                error_log('Không thể khởi tạo Jankx\PostLayout\PostLayoutManager: ' . $e->getMessage());
                return null;
            }
        });

        // Chỉ load tài nguyên post-layout khi cần thiết
        add_action('wp_enqueue_scripts', [$this, 'enqueuePostLayoutAssets']);
    }

    public function boot()
    {
        // Khởi động các dịch vụ nếu cần
    }

    public function enqueuePostLayoutAssets()
    {
        // Kiểm tra nếu cần load tài nguyên post-layout
        if (!is_admin() && !defined('REST_REQUEST') && !defined('DOING_AJAX') && !defined('WP_CLI')) {
            $postLayoutManager = $this->container->make('Jankx\PostLayout\PostLayoutManager');
            if ($postLayoutManager && $this->isLayoutNeeded()) {
                // Load JS và CSS cho post-layout
                wp_enqueue_script('jankx-post-layout', JANKX_ABSPATH . '/vendor/jankx/post-layout/assets/js/post-layout.min.js', [], '1.0.0', true);
                wp_enqueue_style('jankx-post-layout', JANKX_ABSPATH . '/vendor/jankx/post-layout/assets/css/post-layout.min.css', [], '1.0.0');
            }
        }
    }

    protected function isLayoutNeeded()
    {
        // Kiểm tra nếu trang hiện tại cần layout (ví dụ: archive, home, single)
        return is_archive() || is_home() || is_single();
    }
}
