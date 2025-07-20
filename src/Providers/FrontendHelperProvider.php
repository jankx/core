<?php

namespace Jankx\Providers;

class FrontendHelperProvider extends HelperServiceProvider
{
    protected function loadHelpers()
    {
        // Load các helper cho frontend
        $helperPath = JANKX_ABSPATH . '/vendor/jankx/helpers/src/';
        $helpers = [
            'Mobile_Detect.php',
            // Thêm các helper khác nếu cần
        ];

        foreach ($helpers as $helper) {
            $file = $helperPath . $helper;
            if (file_exists($file)) {
                require_once $file;
            } else {
                error_log("Helper file not found: {$file}");
            }
        }
    }
}
