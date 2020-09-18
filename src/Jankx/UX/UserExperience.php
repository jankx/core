<?php
namespace Jankx\UX;

class UserExperience
{
    protected static $instance;

    public $audit;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->audit = new Audit();
    }

    public function optimize()
    {
        $this->audit->firstContentfulPaint();
    }
}
