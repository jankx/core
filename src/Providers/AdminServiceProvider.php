<?php

namespace Jankx\Providers;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các dịch vụ cho admin
        // Hiện tại không có dịch vụ cụ thể nào được đăng ký

        // Đăng ký helper provider cho admin
        $helperProvider = new AdminHelperProvider($this->container);
        $helperProvider->register();
    }

    public function boot()
    {
        // Khởi động các dịch vụ nếu cần
    }
}