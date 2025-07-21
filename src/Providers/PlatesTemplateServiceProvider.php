<?php

namespace Jankx\Providers;

class PlatesTemplateServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các dịch vụ liên quan đến Plates templates
        $this->singleton('Jankx\Templates\PlatesEngine', function ($container) {
            // Logic khởi tạo dịch vụ, có thể trả về null nếu không khả dụng
            try {
                if (class_exists('\Jankx\Templates\PlatesEngine')) {
                    $engine = new \Jankx\Templates\PlatesEngine();
                    // Thêm logic caching nếu có
                    return $engine;
                } else {
                    error_log('Class Jankx\Templates\PlatesEngine không tồn tại.');
                    return null;
                }
            } catch (\Exception $e) {
                error_log('Không thể khởi tạo Jankx\Templates\PlatesEngine: ' . $e->getMessage());
                return null;
            }
        });

        // Chỉ load Plates templates khi cần thiết
        add_filter('template_include', [$this, 'loadPlatesTemplate'], 99);
    }

    public function boot()
    {
        // Khởi động các dịch vụ nếu cần
    }

    public function loadPlatesTemplate($template)
    {
        // Kiểm tra nếu cần load Plates template
        // Ví dụ: chỉ load nếu là frontend và template không phải là mặc định của WordPress
        if (!is_admin() && !defined('REST_REQUEST') && !defined('DOING_AJAX') && !defined('WP_CLI')) {
            $platesEngine = $this->container->make('Jankx\Templates\PlatesEngine');
            if ($platesEngine) {
                $customTemplate = $platesEngine->findTemplate();
                if ($customTemplate) {
                    return $customTemplate;
                }
            }
        }
        return $template;
    }
}
