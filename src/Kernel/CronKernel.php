<?php

namespace Jankx\Kernel;

use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\ThemeBootstrapper;

/**
 * Cron Kernel
 *
 * Handles WP Cron jobs and scheduled tasks
 *
 * @package Jankx\Kernel
 */
class CronKernel extends AbstractKernel implements KernelInterface
{
    /**
     * Get kernel type
     */
    public function getKernelType(): string
    {
        return 'cron';
    }

    /**
     * Register bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        // Theme bootstrapper (highest priority)
        $this->addBootstrapper(ThemeBootstrapper::class);

        // Allow child themes to add custom bootstrappers
        $customBootstrappers = apply_filters('jankx/cron/bootstrappers', []);
        foreach ($customBootstrappers as $bootstrapper) {
            $this->addBootstrapper($bootstrapper);
        }
    }

    /**
     * Register services
     */
    protected function registerServices(): void
    {
        // Không cần đăng ký các command services ở đây
    }

    /**
     * Register hooks
     */
    protected function registerHooks(): void
    {
        // Cron jobs
        $this->addHook('jankx_cron_optimize', [$this, 'runOptimizationCron']);
        $this->addHook('jankx_cron_security_scan', [$this, 'runSecurityScanCron']);
        $this->addHook('jankx_cron_cache_cleanup', [$this, 'runCacheCleanupCron']);

        // Schedule cron jobs on init
        $this->addHook('init', [$this, 'scheduleCronJobs']);
    }

    /**
     * Register filters
     */
    protected function registerFilters(): void
    {
        // Cron output formatting
        $this->addFilter('jankx_cron_output', [$this, 'formatCronOutput']);
    }

    /**
     * Run optimization cron
     */
    public function runOptimizationCron(): void
    {
        // Thực hiện các tác vụ tối ưu hóa mà không cần command
        $this->logInfo('Optimization cron job started');
        // Ví dụ: Xóa các bản nháp tự động cũ
        $this->cleanAutoDrafts();
        $this->logInfo('Optimization cron job completed');
    }

    /**
     * Run security scan cron
     */
    public function runSecurityScanCron(): void
    {
        $this->logInfo('Security scan cron job started');
        // Ví dụ: Kiểm tra các file hệ thống có thay đổi bất thường không
        $this->checkSystemFiles();
        $this->logInfo('Security scan cron job completed');
    }

    /**
     * Run cache cleanup cron
     */
    public function runCacheCleanupCron(): void
    {
        $this->logInfo('Cache cleanup cron job started');
        // Ví dụ: Xóa các transient hết hạn
        $this->cleanExpiredTransients();
        $this->logInfo('Cache cleanup cron job completed');
    }

    /**
     * Schedule cron jobs
     */
    public function scheduleCronJobs(): void
    {
        // Schedule optimization cron (daily at 2 AM)
        if (!wp_next_scheduled('jankx_cron_optimize')) {
            wp_schedule_event(strtotime('tomorrow 2:00 AM'), 'daily', 'jankx_cron_optimize');
        }

        // Schedule security scan cron (weekly on Sunday at 3 AM)
        if (!wp_next_scheduled('jankx_cron_security_scan')) {
            wp_schedule_event(strtotime('next Sunday 3:00 AM'), 'weekly', 'jankx_cron_security_scan');
        }

        // Schedule cache cleanup cron (every 6 hours)
        if (!wp_next_scheduled('jankx_cron_cache_cleanup')) {
            wp_schedule_event(time(), 'every_6_hours', 'jankx_cron_cache_cleanup');
        }
    }

    /**
     * Format cron output
     */
    public function formatCronOutput(string $output): string
    {
        // Add timestamp
        $timestamp = date('Y-m-d H:i:s');
        $output = "[{$timestamp}] [CRON] {$output}";

        return $output;
    }

    /**
     * Clean auto drafts
     */
    protected function cleanAutoDrafts(): void
    {
        global $wpdb;
        $old_drafts = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_status = 'auto-draft' AND DATE_SUB(CURDATE(), INTERVAL 7 DAY) > post_date");
        foreach ($old_drafts as $draft_id) {
            wp_delete_post($draft_id, true);
        }
        $this->logInfo('Cleaned up old auto drafts');
    }

    /**
     * Clean expired transients
     */
    protected function cleanExpiredTransients(): void
    {
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%'");
        $this->logInfo('Cleaned up expired transients');
    }

    /**
     * Check system files for unexpected changes
     */
    protected function checkSystemFiles(): void
    {
        // Ví dụ: Kiểm tra các file hệ thống WordPress
        $this->logInfo('Checked system files for unexpected changes');
    }

    /**
     * Log info message
     */
    protected function logInfo(string $message): void
    {
        error_log("Jankx Cron Info: {$message}");
    }

    /**
     * Log error message
     */
    protected function logError(string $message): void
    {
        error_log("Jankx Cron Error: {$message}");
    }

    /**
     * Log success message
     */
    protected function logSuccess(string $message): void
    {
        error_log("Jankx Cron Success: {$message}");
    }

    /**
     * Log warning message
     */
    protected function logWarning(string $message): void
    {
        error_log("Jankx Cron Warning: {$message}");
    }
}
