<?php
namespace Jankx\UX;

class UserExperience
{
    protected static $instance;

    public $audit;
    protected $customize;

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
        $this->customize = new Customize();
    }

    public function optimize()
    {
        $this->customize->showLoading();
        $this->customize->loadPresetPalettes();
    }
}
