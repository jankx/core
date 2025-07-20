<?php

namespace Jankx\Providers;

class CLIServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các dịch vụ cho CLI
        $this->singleton('Jankx\Command\CommandManager', function ($container) {
            // Logic khởi tạo dịch vụ, có thể trả về null nếu không khả dụng
            try {
                return new \Jankx\Command\CommandManager();
            } catch (\Exception $e) {
                error_log('Không thể khởi tạo Jankx\Command\CommandManager: ' . $e->getMessage());
                return null;
            }
        });

        // Thêm các dịch vụ khác cho CLI tại đây
    }

    public function boot()
    {
        // Khởi động các dịch vụ nếu cần
    }
}