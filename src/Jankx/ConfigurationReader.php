<?php
namespace Jankx;

/**
 * Configurations reader
 *
 * This class use to read file theme.yml in root directory of theme.
 * Child theme can override configs
 */
class ConfigurationReader
{
    public function __construct($config_file = '.theme.yml')
    {
    }

    protected function read_child_configs()
    {
    }

    protected function read_template_configs()
    {
    }

    protected function combine_configs($template_configs, $child_configs)
    {
    }

    public function read()
    {
    }
}
