<?php

namespace Jankx\Kernel;

use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\APIBootstrapper;
use Jankx\Kernel\Bootstrappers\ThemeBootstrapper;
use Jankx\API\APIManager;
use Jankx\API\Endpoints\PostsEndpoint;
use Jankx\API\Endpoints\PagesEndpoint;
use Jankx\API\Endpoints\CategoriesEndpoint;
use Jankx\API\Endpoints\TagsEndpoint;
use Jankx\API\Endpoints\UsersEndpoint;
use Jankx\API\Endpoints\SettingsEndpoint;

/**
 * API Kernel
 *
 * Handles API-specific features and endpoints
 *
 * @package Jankx\Kernel
 */
class APIKernel extends AbstractKernel implements KernelInterface
{
    /**
     * Get kernel type
     */
    public function getKernelType(): string
    {
        return 'api';
    }

    /**
     * Register bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        // Theme bootstrapper (highest priority)
        $this->addBootstrapper(ThemeBootstrapper::class);

        // API bootstrapper
        $this->addBootstrapper(APIBootstrapper::class);

        // Allow child themes to add custom bootstrappers
        $customBootstrappers = apply_filters('jankx/api/bootstrappers', []);
        foreach ($customBootstrappers as $bootstrapper) {
            $this->addBootstrapper($bootstrapper);
        }
    }

    /**
     * Register services
     */
    protected function registerServices(): void
    {
        // API manager
        $this->addService(APIManager::class);

        // Core endpoints
        $this->addService(PostsEndpoint::class);
        $this->addService(PagesEndpoint::class);
        $this->addService(CategoriesEndpoint::class);
        $this->addService(TagsEndpoint::class);
        $this->addService(UsersEndpoint::class);
        $this->addService(SettingsEndpoint::class);
    }

    /**
     * Register hooks
     */
    protected function registerHooks(): void
    {
        // REST API initialization
        $this->addHook('rest_api_init', [$this, 'registerAPIEndpoints']);

        // CORS headers
        $this->addHook('rest_pre_serve_request', [$this, 'addCORSHeaders']);

        // API authentication
        $this->addHook('rest_authentication_errors', [$this, 'authenticateAPI']);

        // API rate limiting
        $this->addHook('rest_pre_dispatch', [$this, 'checkRateLimit']);

        // API logging
        $this->addHook('rest_post_dispatch', [$this, 'logAPIRequest']);

        // Custom endpoints
        $this->addHook('jankx_api_register_endpoints', [$this, 'registerCustomEndpoints']);
    }

    /**
     * Register filters
     */
    protected function registerFilters(): void
    {
        // API response formatting
        $this->addFilter('jankx_api_response', [$this, 'formatAPIResponse']);

        // API error handling
        $this->addFilter('jankx_api_error', [$this, 'formatAPIError']);

        // API permissions
        $this->addFilter('jankx_api_permissions', [$this, 'checkAPIPermissions']);
    }

    /**
     * Register API endpoints
     */
    public function registerAPIEndpoints(): void
    {
        $api_manager = $this->container->make(APIManager::class);

        // Register core endpoints
        $api_manager->registerEndpoint('posts', PostsEndpoint::class);
        $api_manager->registerEndpoint('pages', PagesEndpoint::class);
        $api_manager->registerEndpoint('categories', CategoriesEndpoint::class);
        $api_manager->registerEndpoint('tags', TagsEndpoint::class);
        $api_manager->registerEndpoint('users', UsersEndpoint::class);
        $api_manager->registerEndpoint('settings', SettingsEndpoint::class);

        // Allow child themes to register custom endpoints
        do_action('jankx_api_register_endpoints', $api_manager);
    }

    /**
     * Add CORS headers
     */
    public function addCORSHeaders(): void
    {
        // Allow cross-origin requests
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        // Handle preflight requests
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    /**
     * Authenticate API
     */
    public function authenticateAPI($result): mixed
    {
        // Skip authentication for public endpoints
        $public_endpoints = apply_filters('jankx_api_public_endpoints', [
            'posts',
            'pages',
            'categories',
            'tags'
        ]);

        $current_endpoint = $this->getCurrentEndpoint();
        if (in_array($current_endpoint, $public_endpoints)) {
            return true;
        }

        // Check for API key
        $api_key = $this->getAPIKey();
        if (!$api_key) {
            return new \WP_Error(
                'jankx_api_no_key',
                __('API key is required', 'jankx'),
                ['status' => 401]
            );
        }

        // Validate API key
        if (!$this->validateAPIKey($api_key)) {
            return new \WP_Error(
                'jankx_api_invalid_key',
                __('Invalid API key', 'jankx'),
                ['status' => 401]
            );
        }

        return true;
    }

    /**
     * Check rate limit
     */
    public function checkRateLimit($result): mixed
    {
        if (is_wp_error($result)) {
            return $result;
        }

        $ip = $this->getClientIP();
        $endpoint = $this->getCurrentEndpoint();

        // Check rate limit
        if ($this->isRateLimited($ip, $endpoint)) {
            return new \WP_Error(
                'jankx_api_rate_limited',
                __('Rate limit exceeded', 'jankx'),
                ['status' => 429]
            );
        }

        // Update rate limit counter
        $this->updateRateLimit($ip, $endpoint);

        return $result;
    }

    /**
     * Log API request
     */
    public function logAPIRequest($response, $handler, $request): void
    {
        $log_data = [
            'timestamp' => current_time('mysql'),
            'method' => $_SERVER['REQUEST_METHOD'],
            'endpoint' => $this->getCurrentEndpoint(),
            'ip' => $this->getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'response_code' => wp_remote_retrieve_response_code($response),
        ];

        // Log to database or file
        $this->logToDatabase($log_data);
    }

    /**
     * Register custom endpoints
     */
    public function registerCustomEndpoints($api_manager): void
    {
        // Register theme-specific endpoints here
        // Example: $api_manager->registerEndpoint('custom', CustomEndpoint::class);
    }

    /**
     * Format API response
     */
    public function formatAPIResponse($response): array
    {
        $formatted = [
            'success' => true,
            'data' => $response,
            'timestamp' => current_time('timestamp'),
            'version' => Jankx::FRAMEWORK_VERSION,
        ];

        return apply_filters('jankx_api_response_formatted', $formatted);
    }

    /**
     * Format API error
     */
    public function formatAPIError($error): array
    {
        $formatted = [
            'success' => false,
            'error' => [
                'code' => $error->get_error_code(),
                'message' => $error->get_error_message(),
                'data' => $error->get_error_data(),
            ],
            'timestamp' => current_time('timestamp'),
            'version' => Jankx::FRAMEWORK_VERSION,
        ];

        return apply_filters('jankx_api_error_formatted', $formatted);
    }

    /**
     * Check API permissions
     */
    public function checkAPIPermissions($permissions, $endpoint): bool
    {
        // Default permissions
        $default_permissions = [
            'posts' => 'read',
            'pages' => 'read',
            'categories' => 'read',
            'tags' => 'read',
            'users' => 'read',
            'settings' => 'manage_options',
        ];

        $required_permission = $default_permissions[$endpoint] ?? 'read';
        return current_user_can($required_permission);
    }

    /**
     * Get current endpoint
     */
    protected function getCurrentEndpoint(): string
    {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($request_uri, PHP_URL_PATH);
        $path_parts = explode('/', trim($path, '/'));

        // Find 'wp-json' in path
        $wp_json_index = array_search('wp-json', $path_parts);
        if ($wp_json_index !== false && isset($path_parts[$wp_json_index + 2])) {
            return $path_parts[$wp_json_index + 2];
        }

        return '';
    }

    /**
     * Get API key from request
     */
    protected function getAPIKey(): ?string
    {
        // Check Authorization header
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
            return $matches[1];
        }

        // Check X-API-Key header
        $api_key_header = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if (!empty($api_key_header)) {
            return $api_key_header;
        }

        // Check query parameter
        return $_GET['api_key'] ?? null;
    }

    /**
     * Validate API key
     */
    protected function validateAPIKey(string $api_key): bool
    {
        $valid_keys = apply_filters('jankx_api_valid_keys', []);
        return in_array($api_key, $valid_keys);
    }

    /**
     * Get client IP
     */
    protected function getClientIP(): string
    {
        $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];

        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Check if request is rate limited
     */
    protected function isRateLimited(string $ip, string $endpoint): bool
    {
        $rate_limit_key = "jankx_api_rate_limit_{$ip}_{$endpoint}";
        $rate_limit = get_transient($rate_limit_key);

        if (!$rate_limit) {
            return false;
        }

        $max_requests = apply_filters('jankx_api_rate_limit_max', 100);
        $time_window = apply_filters('jankx_api_rate_limit_window', 3600); // 1 hour

        return $rate_limit['count'] >= $max_requests;
    }

    /**
     * Update rate limit counter
     */
    protected function updateRateLimit(string $ip, string $endpoint): void
    {
        $rate_limit_key = "jankx_api_rate_limit_{$ip}_{$endpoint}";
        $rate_limit = get_transient($rate_limit_key);

        if (!$rate_limit) {
            $rate_limit = [
                'count' => 1,
                'window_start' => time(),
            ];
        } else {
            $rate_limit['count']++;
        }

        set_transient($rate_limit_key, $rate_limit, 3600); // 1 hour
    }

    /**
     * Log to database
     */
    protected function logToDatabase(array $log_data): void
    {
        // Log to WordPress options or custom table
        $logs = get_option('jankx_api_logs', []);
        $logs[] = $log_data;

        // Keep only last 1000 logs
        if (count($logs) > 1000) {
            $logs = array_slice($logs, -1000);
        }

        update_option('jankx_api_logs', $logs);
    }
}
