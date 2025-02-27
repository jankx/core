<?php
namespace Jankx\Component\Constracts;

interface Component
{
    public function getName();

    public static function getTemplateEngine();

    public function parseProps($props);

    public function buildComponentData();

    public function renderViaEngine();

    public function render();

    public function __toString();

    public function echo();
}
