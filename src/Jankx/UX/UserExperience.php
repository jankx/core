<?php

namespace Jankx\UX;

class UserExperience
{
    protected static $instance;

    public $audit;
    protected $customize;

    protected $mobile;

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
        $this->mobile = new Mobile();

        // register modules
        $this->customize->registerCustomizers();
    }

    public function optimize()
    {
        $this->customize->showLoading();
        $this->customize->loadPresetPalettes();
        $this->customize->loadCustomizers();

        if (wp_is_request('frontend') && jankx_is_mobile()) {
            $this->mobile->makeImageLookGood();
        }

        add_filter(
            'jankx_template_num_of_footer_widgets',
            array($this->customize, 'footerWidgets')
        );
    }
}
