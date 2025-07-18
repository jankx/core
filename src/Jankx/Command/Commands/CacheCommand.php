<?php

namespace Jankx\Command\Commands;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Command\Abstracts\Command;
use WP_CLI;

class CacheCommand extends Command
{
    const COMMAND_NAME = 'cache';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    public function print_help()
    {
        echo WP_ClI::colorize(__("Do you want to clean all cache please use \n%ywp jankx cache flush --all%n", 'jankx'));
    }
}
