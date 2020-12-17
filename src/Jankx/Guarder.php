<?php
namespace Jankx;

if (!defined('ABSPATH')) {
    exit('The silent is golden.');
}

use Jankx\Security\Upload;

class Guarder
{
    protected static $instance;

    protected $upload;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->upload = new Upload();
    }

    public function watch()
    {
        // Allow upload more file types
        add_action('init', array($this->upload, 'allow_new_file_types'));
    }
}
