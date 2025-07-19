<?php

namespace Jankx\Command\Commands\Cache;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Command\Abstracts\Subcommand;
use WP_CLI;

class FlushCacheCommand extends Subcommand
{
    public function get_name()
    {
    }

    public function print_help()
    {
    }

    /**
     * Safely delete file with validation
     *
     * @param string $file_path
     * @return bool
     */
    private function safe_delete_file($file_path)
    {
        // Validate path
        if (empty($file_path) || !is_string($file_path)) {
            return false;
        }

        // Prevent directory traversal
        $real_path = realpath($file_path);
        if ($real_path === false) {
            return false;
        }

        // Check if file exists and is writable
        if (!file_exists($real_path) || !is_writable($real_path)) {
            return false;
        }

        // Delete file with error handling
        try {
            return unlink($real_path);
        } catch (Exception $e) {
            error_log('Jankx Cache: Failed to delete file - ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Safely delete directory with validation
     *
     * @param string $dir_path
     * @return bool
     */
    private function safe_delete_dir($dir_path)
    {
        // Validate path
        if (empty($dir_path) || !is_string($dir_path)) {
            return false;
        }

        // Prevent directory traversal
        $real_path = realpath($dir_path);
        if ($real_path === false) {
            return false;
        }

        // Check if directory exists and is writable
        if (!is_dir($real_path) || !is_writable($real_path)) {
            return false;
        }

        // Delete directory with error handling
        try {
            return rmdir($real_path);
        } catch (Exception $e) {
            error_log('Jankx Cache: Failed to delete directory - ' . $e->getMessage());
            return false;
        }
    }

    private function clean_files($path)
    {
        // Validate path
        if (empty($path) || !is_string($path)) {
            return;
        }

        // Prevent directory traversal
        $real_path = realpath($path);
        if ($real_path === false || !is_dir($real_path)) {
            return;
        }

        $filesAndDirs = glob(sprintf('%s/*', $real_path));
        if ($filesAndDirs === false) {
            return;
        }

        foreach ($filesAndDirs as $fileOrDir) {
            if (is_dir($fileOrDir)) {
                $this->clean_files($fileOrDir);
            } else {
                $this->safe_delete_file($fileOrDir);
            }
        }

        $this->safe_delete_dir($real_path);
    }

    protected function flush_css_cache()
    {
        WP_CLI::line(__('Clean cached CSS files', 'jankx'));

        $cache_dir = rtrim(JANKX_CACHE_DIR, '/');
        if (empty($cache_dir) || !is_dir($cache_dir)) {
            WP_CLI::warning(__('Cache directory not found', 'jankx'));
            return;
        }

        // Validate cache directory path
        if (function_exists('jankx_validate_path')) {
            $valid_cache_dir = jankx_validate_path($cache_dir);
            if ($valid_cache_dir === false) {
                WP_CLI::error(__('Invalid cache directory path', 'jankx'));
                return;
            }
            $cache_dir = $valid_cache_dir;
        }

        $cssFiles = glob(sprintf('%s/{*,*/*}.css', $cache_dir), GLOB_BRACE);
        if ($cssFiles === false) {
            WP_CLI::warning(__('No CSS files found to clean', 'jankx'));
            return;
        }

        $deleted_count = 0;
        foreach ($cssFiles as $cssFile) {
            // Validate file path if helper available
            if (function_exists('jankx_validate_path')) {
                $valid_file = jankx_validate_path($cssFile, $cache_dir);
                if ($valid_file === false) {
                    WP_CLI::warning(sprintf(__('Skipping invalid file path: %s', 'jankx'), $cssFile));
                    continue;
                }
                $cssFile = $valid_file;
            }

            if ($this->safe_delete_file($cssFile)) {
                WP_CLI::line(sprintf(__('%s is removed', 'jankx'), $cssFile));
                $deleted_count++;
            } else {
                WP_CLI::warning(sprintf(__('Failed to remove %s', 'jankx'), $cssFile));
            }
        }

        WP_CLI::success(sprintf(__('Cleaned %d CSS files', 'jankx'), $deleted_count));
    }

    protected function flush_templates_cache()
    {
        WP_CLI::line(__('Clean cached templates', 'jankx'));

        $cache_dir = rtrim(JANKX_CACHE_DIR, '/');
        if (empty($cache_dir) || !is_dir($cache_dir)) {
            WP_CLI::warning(__('Cache directory not found', 'jankx'));
            return;
        }

        // Validate cache directory path
        if (function_exists('jankx_validate_path')) {
            $valid_cache_dir = jankx_validate_path($cache_dir);
            if ($valid_cache_dir === false) {
                WP_CLI::error(__('Invalid cache directory path', 'jankx'));
                return;
            }
            $cache_dir = $valid_cache_dir;
        }

        $templateDirs = array('twig');
        foreach ($templateDirs as $templateDir) {
            $template_cache_dir = sprintf('%s/%s', $cache_dir, $templateDir);

            // Validate template cache directory path
            if (function_exists('jankx_validate_path')) {
                $valid_template_dir = jankx_validate_path($template_cache_dir, $cache_dir);
                if ($valid_template_dir === false) {
                    WP_CLI::warning(sprintf(__('Invalid template directory path: %s', 'jankx'), $templateDir));
                    continue;
                }
                $template_cache_dir = $valid_template_dir;
            }

            if (!file_exists($template_cache_dir)) {
                WP_CLI::line(sprintf(__('Template cache directory %s not found', 'jankx'), $templateDir));
                continue;
            }

            WP_CLI::line(sprintf(__('Clean %s caches', 'jankx'), ucfirst($templateDir)));

            $this->clean_files($template_cache_dir);

            WP_CLI::line(sprintf(__('%s caching is clean up', 'jankx'), ucfirst($templateDir)));
        }
    }

    public function handle($args, $assoc_args)
    {
        if (empty($args) && empty($assoc_args)) {
            return $this->print_help();
        }

        if (array_get($assoc_args, 'all')) {
            $assoc_args['css'] = true;
            $assoc_args['template'] = true;
        }

        if (array_get($assoc_args, 'css', false)) {
            $this->flush_css_cache();
        }
        if (array_get($assoc_args, 'template', false)) {
            $this->flush_templates_cache();
        }
    }
}
