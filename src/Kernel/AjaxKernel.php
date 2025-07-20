<?php

namespace Jankx\Kernel;

use Jankx\Kernel\AbstractKernel;

class AjaxKernel extends AbstractKernel
{
    public function boot()
    {
        // Logic xử lý cho các yêu cầu AJAX
        $this->registerHooks();
        $this->loadServices();
    }

    protected function registerHooks()
    {
        // Đăng ký các hook liên quan đến AJAX
        add_action('wp_ajax_fetch_options', [$this, 'handleFetchOptions']);
        // Thêm các action khác nếu cần
    }

    public function handleFetchOptions()
    {
        // Xử lý logic cho action fetch_options
        // Ví dụ: trả về dữ liệu tùy chỉnh cho yêu cầu AJAX
        wp_send_json(['status' => 'success', 'data' => 'Options fetched']);
    }

    protected function loadServices()
    {
        // Load các dịch vụ liên quan đến AJAX
        $services = $this->getServices();
        foreach ($services as $index => $serviceData) {
            if (is_array($serviceData) && isset($serviceData['class']) && is_string($serviceData['class']) && !empty($serviceData['class'])) {
                $service = $serviceData['class'];
                $params = isset($serviceData['params']) ? $serviceData['params'] : [];
                try {
                    $this->container->make($service, $params);
                } catch (\Exception $e) {
                    error_log(sprintf("%s: Không thể khởi tạo service {$service}: " . $e->getMessage(), get_class($this)));
                }
            } else {
                error_log(sprintf("%s: Bỏ qua service có cấu trúc không hợp lệ tại index {$index}", get_class($this)));
            }
        }
    }

    protected function getServices(): array
    {
        return [
            // Danh sách các dịch vụ cho AJAX
        ];
    }
}
