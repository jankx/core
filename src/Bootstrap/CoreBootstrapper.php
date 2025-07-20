<?php

namespace Jankx\Bootstrap;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Context\ContextualServiceRegistry;
use Jankx\Providers\GoogleFontsServiceProvider;
use Jankx\Providers\SocialSharingServiceProvider;
use Jankx\Providers\AdminMenuServiceProvider;
use Jankx\Providers\ThemeOptionsServiceProvider;
use Jankx\Providers\PostLayoutServiceProvider;

/**
 * Class CoreBootstrapper
 *
 * Khởi tạo các dịch vụ ban đầu và đăng ký chúng theo ngữ cảnh.
 *
 * @package Jankx\Bootstrap
 * @author Puleeno Nguyen <puleeno@gmail.com>
 */
class CoreBootstrapper
{
    /**
     * Khởi tạo các dịch vụ và đăng ký chúng theo ngữ cảnh
     */
    public function bootstrap()
    {
        // Đăng ký dịch vụ dùng chung (shared)
        ContextualServiceRegistry::register(ContextualServiceRegistry::SHARED, GoogleFontsServiceProvider::class);
        ContextualServiceRegistry::register(ContextualServiceRegistry::SHARED, ThemeOptionsServiceProvider::class);

        // Đăng ký dịch vụ frontend
        ContextualServiceRegistry::register(ContextualServiceRegistry::FRONTEND, SocialSharingServiceProvider::class);
        ContextualServiceRegistry::register(ContextualServiceRegistry::FRONTEND, PostLayoutServiceProvider::class);

        // Đăng ký dịch vụ dashboard
        ContextualServiceRegistry::register(ContextualServiceRegistry::DASHBOARD, AdminMenuServiceProvider::class);
    }
}
