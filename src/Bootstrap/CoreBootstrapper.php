<?php

namespace Jankx\Bootstrap;

class CoreBootstrapper extends Bootstrapper
{
    public function bootstrap()
    {
        // Khởi tạo các thành phần cốt lõi của hệ thống
        $this->defineConstants();
        $this->loadCoreHelpers();
        $this->registerCoreBindings();
    }

    protected function defineConstants()
    {
        // Định nghĩa các hằng số cơ bản
        parent::defineConstants();
        // Thêm các hằng số khác nếu cần
    }

    protected function loadCoreHelpers()
    {
        // Load các helper cơ bản
        parent::loadCoreHelpers();
        // Có thể thêm logic để load các helper không phụ thuộc context
    }

    protected function registerCoreBindings()
    {
        // Đăng ký các binding cơ bản vào container
        parent::registerCoreBindings();
        // Ví dụ: Đăng ký container chính nó
        $this->container->instance('Illuminate\Container\Container', $this->container);
    }
}
