<?php

namespace Jankx\Providers;

class FrontendServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các dịch vụ cho frontend
        $this->singleton('Jankx\UX\UserExperience', function ($container) {
            // Logic khởi tạo dịch vụ, có thể trả về null nếu không khả dụng
            try {
                return new \Jankx\UX\UserExperience();
            } catch (\Exception $e) {
                error_log('Không thể khởi tạo Jankx\UX\UserExperience: ' . $e->getMessage());
                return null;
            }
        });

        // Đăng ký helper provider cho frontend
        $helperProvider = new FrontendHelperProvider($this->container);
        $helperProvider->register();

        // Thêm các dịch vụ khác cho frontend tại đây
    }

    public function boot()
    {
        // Khởi động các dịch vụ nếu cần
    }
}