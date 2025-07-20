<?php

namespace Jankx\Bootstrap;

use Illuminate\Container\Container;

abstract class Bootstrapper
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    abstract public function bootstrap();

    protected function defineConstants()
    {
        // Định nghĩa các hằng số cơ bản cho hệ thống
        if (!defined('JANKX_ABSPATH')) {
            define('JANKX_ABSPATH', dirname(dirname(dirname(__DIR__))));
        }
        // Thêm các hằng số khác nếu cần
    }

    protected function loadCoreHelpers()
    {
        // Load các helper cơ bản không phụ thuộc vào context
        $helperPath = JANKX_ABSPATH . '/vendor/jankx/helpers/src/';
        $coreHelpers = [
            // Thêm các helper cơ bản tại đây
        ];

        foreach ($coreHelpers as $helper) {
            $file = $helperPath . $helper;
            if (file_exists($file)) {
                require_once $file;
            } else {
                error_log("Core helper file not found: {$file}");
            }
        }
    }

    protected function registerCoreBindings()
    {
        // Đăng ký các binding cơ bản vào container
        // Ví dụ: $this->container->bind('SomeCoreClass', function() { return new SomeCoreClass(); });
    }
}
