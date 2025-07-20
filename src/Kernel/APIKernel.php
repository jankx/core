<?php

namespace Jankx\Kernel;

use Jankx\Kernel\AbstractKernel;
use Jankx\Kernel\Interfaces\KernelInterface;
use Jankx\Kernel\Bootstrappers\APIBootstrapper;

/**
 * API Kernel - Xử lý các API requests
 */
class APIKernel extends AbstractKernel implements KernelInterface
{
    protected $name = 'api';
    protected $context = 'api';

    public function __construct()
    {
        parent::__construct();
        $this->bootstrappers = [
            APIBootstrapper::class,
        ];
    }

    /**
     * Boot kernel cho API context
     */
    public function boot(): void
    {
        // Kiểm tra nếu đang trong API context
        if (!$this->isApiRequest()) {
            return;
        }

        parent::boot();

        // Thiết lập headers cho API
        $this->setupApiHeaders();

        // Đăng ký API routes
        $this->registerApiRoutes();
    }

    /**
     * Lấy loại kernel
     */
    public function getKernelType(): string
    {
        return 'api';
    }

    /**
     * Đăng ký bootstrappers
     */
    protected function registerBootstrappers(): void
    {
        $this->bootstrappers = [
            APIBootstrapper::class,
        ];
    }

    /**
     * Đăng ký services
     */
    protected function registerServices(): void
    {
        // Đăng ký các services cho API
        $this->container->singleton('api.router', function () {
            return new \Jankx\Kernel\Services\APIRouter();
        });
    }

    /**
     * Đăng ký hooks cho API
     */
    protected function registerHooks(): void
    {
        // Đăng ký các hooks cần thiết cho API
        add_action('rest_api_init', [$this, 'registerApiRoutes']);
    }

    /**
     * Đăng ký filters cho API
     */
    protected function registerFilters(): void
    {
        // Đăng ký các filters cần thiết cho API
    }

    /**
     * Kiểm tra xem có phải API request không
     */
    protected function isApiRequest()
    {
        return defined('REST_REQUEST') && REST_REQUEST;
    }

    /**
     * Thiết lập headers cho API
     */
    protected function setupApiHeaders()
    {
        // CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Content type
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * Đăng ký API routes
     */
    public function registerApiRoutes()
    {
        // Đăng ký các route API
        // Ví dụ: register_rest_route('my-namespace/v1', '/my-endpoint', [
        //     'methods' => 'GET',
        //     'callback' => [$this, 'handleMyEndpoint'],
        // ]);
    }

    /**
     * API endpoint: Lấy danh sách sách
     */
    public function getBooks($request)
    {
        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $request->get_param('per_page') ?: 10,
            'paged' => $request->get_param('page') ?: 1,
        ];

        $query = new \WP_Query($args);
        $books = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $product = wc_get_product(get_the_ID());

                $books[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'price' => $product ? $product->get_price() : '',
                    'sale_price' => $product ? $product->get_sale_price() : '',
                    'regular_price' => $product ? $product->get_regular_price() : '',
                    'featured_image' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'permalink' => get_permalink(),
                    'categories' => wp_get_post_terms(get_the_ID(), 'product_cat', ['fields' => 'names']),
                ];
            }
        }

        wp_reset_postdata();

        return [
            'success' => true,
            'data' => $books,
            'total' => $query->found_posts,
            'total_pages' => $query->max_num_pages,
        ];
    }

    /**
     * API endpoint: Lấy danh sách danh mục
     */
    public function getCategories($request)
    {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ]);

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->term_id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'count' => $category->count,
                'image' => get_term_meta($category->term_id, 'thumbnail_id', true),
            ];
        }

        return [
            'success' => true,
            'data' => $data,
        ];
    }

    /**
     * API endpoint: Lấy danh sách tác giả
     */
    public function getAuthors($request)
    {
        $authors = get_users([
            'role' => 'author',
            'orderby' => 'display_name',
        ]);

        $data = [];
        foreach ($authors as $author) {
            $data[] = [
                'id' => $author->ID,
                'name' => $author->display_name,
                'email' => $author->user_email,
                'bio' => get_user_meta($author->ID, 'description', true),
                'avatar' => get_avatar_url($author->ID),
                'posts_count' => count_user_posts($author->ID),
            ];
        }

        return [
            'success' => true,
            'data' => $data,
        ];
    }

    /**
     * Shutdown kernel
     */
    public function shutdown()
    {
        parent::shutdown();

        // Cleanup API specific resources
        remove_all_actions('rest_api_init');
    }
}
