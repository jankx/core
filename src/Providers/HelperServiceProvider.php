<?php

namespace Jankx\Providers;

class HelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Đăng ký các helper
        $this->loadHelpers();
    }

    public function boot()
    {
        // Không cần boot cho helper
    }

    protected function loadHelpers()
    {
        // Phương thức này sẽ được ghi đè bởi các class con để load helper cụ thể
    }
}
