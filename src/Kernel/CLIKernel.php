<?php

namespace Jankx\Kernel;

use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\CLIBootstrapper;
use Jankx\Kernel\Bootstrappers\ThemeBootstrapper;
use Jankx\Command\CommandManager;
use Jankx\Command\Commands\CacheCommand;
use Jankx\Command\Commands\OptimizeCommand;
use Jankx\Command\Commands\SecurityCommand;

/**
 * CLI Kernel
 *
 * Handles CLI-specific features and commands
 *
 * @package Jankx\Kernel
 */
class CLIKernel extends AbstractKernel implements KernelInterface
{
    /**
     * Get kernel type
     */
    public function getKernelType(): string
    {
        return 'cli';
    }

    /**
     * Register bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        // Theme bootstrapper (highest priority)
        $this->addBootstrapper(ThemeBootstrapper::class);

        // CLI bootstrapper
        $this->addBootstrapper(CLIBootstrapper::class);

        // Allow child themes to add custom bootstrappers
        $customBootstrappers = apply_filters('jankx/cli/bootstrappers', []);
        foreach ($customBootstrappers as $bootstrapper) {
            $this->addBootstrapper($bootstrapper);
        }
    }

    /**
     * Register services
     */
    protected function registerServices(): void
    {
        // Command manager
        $this->addService(CommandManager::class);

        // Cache command
        $this->addService(CacheCommand::class);

        // Optimize command
        $this->addService(OptimizeCommand::class);

        // Security command
        $this->addService(SecurityCommand::class);
    }

    /**
     * Register hooks
     */
    protected function registerHooks(): void
    {
        // CLI commands
        $this->addHook('cli_init', [$this, 'registerCLICommands']);

        // WP-CLI commands
        if (defined('WP_CLI') && WP_CLI) {
            $this->addHook('cli_init', [$this, 'registerWPCLICommands']);
        }

        // Cron jobs
        $this->addHook('jankx_cron_optimize', [$this, 'runOptimizationCron']);
        $this->addHook('jankx_cron_security_scan', [$this, 'runSecurityScanCron']);
        $this->addHook('jankx_cron_cache_cleanup', [$this, 'runCacheCleanupCron']);
    }

    /**
     * Register filters
     */
    protected function registerFilters(): void
    {
        // CLI output formatting
        $this->addFilter('jankx_cli_output', [$this, 'formatCLIOutput']);

        // Command help text
        $this->addFilter('jankx_command_help', [$this, 'formatCommandHelp']);
    }

    /**
     * Register CLI commands
     */
    public function registerCLICommands(): void
    {
        $command_manager = $this->container->make(CommandManager::class);

        // Register built-in commands
        $command_manager->registerCommand('cache:clear', CacheCommand::class);
        $command_manager->registerCommand('optimize', OptimizeCommand::class);
        $command_manager->registerCommand('security:scan', SecurityCommand::class);

        // Allow child themes to register custom commands
        do_action('jankx_cli_register_commands', $command_manager);
    }

    /**
     * Register WP-CLI commands
     */
    public function registerWPCLICommands(): void
    {
        if (!class_exists('WP_CLI')) {
            return;
        }

        // Cache commands
        \WP_CLI::add_command('jankx cache', 'Jankx\Command\Commands\CacheCommand');

        // Optimize commands
        \WP_CLI::add_command('jankx optimize', 'Jankx\Command\Commands\OptimizeCommand');

        // Security commands
        \WP_CLI::add_command('jankx security', 'Jankx\Command\Commands\SecurityCommand');

        // Allow child themes to register custom WP-CLI commands
        do_action('jankx_wpcli_register_commands');
    }

    /**
     * Run optimization cron
     */
    public function runOptimizationCron(): void
    {
        $optimize_command = $this->container->make(OptimizeCommand::class);
        $optimize_command->execute(['--cron' => true]);
    }

    /**
     * Run security scan cron
     */
    public function runSecurityScanCron(): void
    {
        $security_command = $this->container->make(SecurityCommand::class);
        $security_command->execute(['--cron' => true]);
    }

    /**
     * Run cache cleanup cron
     */
    public function runCacheCleanupCron(): void
    {
        $cache_command = $this->container->make(CacheCommand::class);
        $cache_command->execute(['cleanup' => true]);
    }

    /**
     * Format CLI output
     */
    public function formatCLIOutput(string $output): string
    {
        // Add timestamp
        $timestamp = date('Y-m-d H:i:s');
        $output = "[{$timestamp}] {$output}";

        // Add color coding for different message types
        if (strpos($output, 'ERROR') !== false) {
            $output = "\033[31m{$output}\033[0m"; // Red
        } elseif (strpos($output, 'SUCCESS') !== false) {
            $output = "\033[32m{$output}\033[0m"; // Green
        } elseif (strpos($output, 'WARNING') !== false) {
            $output = "\033[33m{$output}\033[0m"; // Yellow
        } elseif (strpos($output, 'INFO') !== false) {
            $output = "\033[36m{$output}\033[0m"; // Cyan
        }

        return $output;
    }

    /**
     * Format command help
     */
    public function formatCommandHelp(string $help): string
    {
        $help = str_replace(
            ['{version}', '{framework}'],
            [Jankx::FRAMEWORK_VERSION, Jankx::FRAMEWORK_NAME],
            $help
        );

        return $help;
    }

    /**
     * Get CLI environment info
     */
    public function getEnvironmentInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'wordpress_version' => get_bloginfo('version'),
            'jankx_version' => Jankx::FRAMEWORK_VERSION,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    }

    /**
     * Check CLI requirements
     */
    public function checkRequirements(): bool
    {
        $requirements = [
            'php_version' => version_compare(PHP_VERSION, '7.4', '>='),
            'memory_limit' => $this->checkMemoryLimit(),
            'execution_time' => $this->checkExecutionTime(),
        ];

        $failed_requirements = array_filter($requirements, function($met) {
            return !$met;
        });

        if (!empty($failed_requirements)) {
            $this->logError('CLI requirements not met: ' . implode(', ', array_keys($failed_requirements)));
            return false;
        }

        return true;
    }

    /**
     * Check memory limit
     */
    protected function checkMemoryLimit(): bool
    {
        $memory_limit = ini_get('memory_limit');
        $memory_limit_bytes = $this->convertToBytes($memory_limit);
        return $memory_limit_bytes >= 128 * 1024 * 1024; // 128MB minimum
    }

    /**
     * Check execution time
     */
    protected function checkExecutionTime(): bool
    {
        $max_execution_time = ini_get('max_execution_time');
        return $max_execution_time == 0 || $max_execution_time >= 30; // 30 seconds minimum
    }

    /**
     * Convert memory string to bytes
     */
    protected function convertToBytes(string $memory_string): int
    {
        $memory_string = trim($memory_string);
        $last = strtolower($memory_string[strlen($memory_string) - 1]);
        $value = (int) $memory_string;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Log error message
     */
    protected function logError(string $message): void
    {
        error_log("Jankx CLI Error: {$message}");
    }

    /**
     * Log info message
     */
    protected function logInfo(string $message): void
    {
        error_log("Jankx CLI Info: {$message}");
    }

    /**
     * Log success message
     */
    protected function logSuccess(string $message): void
    {
        error_log("Jankx CLI Success: {$message}");
    }

    /**
     * Log warning message
     */
    protected function logWarning(string $message): void
    {
        error_log("Jankx CLI Warning: {$message}");
    }
}
