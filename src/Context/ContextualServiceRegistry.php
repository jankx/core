<?php

namespace Jankx\Context;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

/**
 * Class ContextualServiceRegistry
 *
 * Quản lý việc đăng ký các dịch vụ theo ngữ cảnh (frontend, dashboard, shared).
 * Đảm bảo chỉ các dịch vụ phù hợp được khởi tạo trong từng ngữ cảnh.
 *
 * @package Jankx\Context
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */
class ContextualServiceRegistry
{
    const FRONTEND = 'frontend';
    const DASHBOARD = 'dashboard';
    const SHARED = 'shared';

    /**
     * Danh sách các dịch vụ được đăng ký theo ngữ cảnh
     *
     * @var array
     */
    protected static $services = [
        self::FRONTEND => [],
        self::DASHBOARD => [],
        self::SHARED => []
    ];

    /**
     * Đăng ký một dịch vụ với ngữ cảnh cụ thể
     *
     * @param string $context Ngữ cảnh (frontend, dashboard, shared)
     * @param string $serviceProviderClass Tên class của service provider
     */
    public static function register($context, $serviceProviderClass)
    {
        if (in_array($context, [self::FRONTEND, self::DASHBOARD, self::SHARED])) {
            self::$services[$context][] = $serviceProviderClass;
        }
    }

    /**
     * Lấy danh sách các dịch vụ theo ngữ cảnh
     *
     * @param string $context Ngữ cảnh hiện tại
     * @return array Danh sách các service provider class
     */
    public static function getServices($context)
    {
        $sharedServices = self::$services[self::SHARED];
        if ($context === self::FRONTEND) {
            return array_merge($sharedServices, self::$services[self::FRONTEND]);
        } elseif ($context === self::DASHBOARD) {
            return array_merge($sharedServices, self::$services[self::DASHBOARD]);
        }
        return $sharedServices;
    }
}