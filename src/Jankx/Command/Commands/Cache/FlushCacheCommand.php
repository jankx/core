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


    private function clean_files($path)
    {
        $filesAndDirs = glob(sprintf('%s/*', $path));
        foreach ($filesAndDirs as $fileOrDir) {
            if (is_dir($fileOrDir)) {
                $this->clean_files($fileOrDir);
            } else {
                @unlink($fileOrDir);
            }
        }
        @rmdir($path);
    }

    protected function flush_css_cache()
    {
        WP_CLI::line(__('Clean cached CSS files', 'jankx'));
        $cssFiles = glob(sprintf('%s/{*,*/*}.css', rtrim(JANKX_CACHE_DIR, '/')), GLOB_BRACE);
        foreach ($cssFiles as $cssFile) {
            if (unlink($cssFile)) {
                WP_CLI::line(sprintf(__('%s is removed', 'jankx'), $cssFile));
            }
        }
    }

    protected function flush_templates_cache()
    {
        WP_CLI::line(__('Clean cached templates', 'jankx'));
        $templateDirs = array('twig');
        foreach ($templateDirs as $templateDir) {
            $cacheDir = sprintf('%s/%s', rtrim(JANKX_CACHE_DIR, '/'), $templateDir);
            if (!file_exists($cacheDir)) {
                continue;
            }
            WP_CLI::line(sprintf(__('Clean %s caches', 'jankx'), ucfirst($templateDir)));

            $this->clean_files($cacheDir);

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
