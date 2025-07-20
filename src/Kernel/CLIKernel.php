<?php

namespace Jankx\Kernel;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Context\ContextualServiceRegistry;

/**
 * Class CLIKernel
 *
 * Khởi tạo các dịch vụ dành riêng cho CLI và các dịch vụ dùng chung.
 *
 * @package Jankx\Kernel
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */
class CLIKernel
{
    protected $container;
    protected $booted = false;

    /**
     * Constructor
     *
     * @param mixed $container Container để resolve các dịch vụ
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Khởi tạo các dịch vụ theo ngữ cảnh CLI
     */
    public function boot()
    {
        $services = ContextualServiceRegistry::getServices(ContextualServiceRegistry::SHARED); // CLI chỉ dùng các dịch vụ dùng chung
        foreach ($services as $serviceProviderClass) {
            if (class_exists($serviceProviderClass)) {
                $serviceProvider = new $serviceProviderClass($this->container);
                $serviceProvider->register();
            }
        }
        $this->booted = true;
    }

    /**
     * Kiểm tra xem kernel đã được khởi tạo hay chưa
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }
}
