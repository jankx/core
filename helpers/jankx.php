<?php
use Jankx\Template\Template;

function jankx_template($tempates, $data = [], $echo = true)
{
    $jankxDefaultTemplateDir = realpath(sprintf('%s/../template/default', dirname(JANKX_FRAMEWORK_FILE_LOADER)));
    $templateDirectoryName = apply_filters('jankx_core_template_directory', 'templates');
    $loader = Template::getInstance(
        $jankxDefaultTemplateDir,
        $templateDirectoryName
    );

    $loader->search($tempates);

    return $loader->load($data, $echo);
}

function jankx_component($componentName, $args = [])
{
}
