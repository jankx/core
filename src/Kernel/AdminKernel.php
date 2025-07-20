<?php

namespace Jankx\Kernel;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Context\ContextualServiceRegistry;

/**
 * Class AdminKernel
 *
 * Khởi tạo các dịch vụ dành riêng cho dashboard và các dịch vụ dùng chung.
 *
 * @package Jankx\Kernel
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */
class AdminKernel
{
    protected $container;

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
     * Khởi tạo các dịch vụ theo ngữ cảnh dashboard
     */
    public function boot()
    {
        $services = ContextualServiceRegistry::getServices(ContextualServiceRegistry::DASHBOARD);
        foreach ($services as $serviceProviderClass) {
            if (class_exists($serviceProviderClass)) {
                $serviceProvider = new $serviceProviderClass($this->container);
                $serviceProvider->register();
            }
        }
    }
}
