<?php

namespace Jankx\Command\Commands;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Command\Abstracts\Command;

class PublishCommand extends Command
{
    const COMMAND_NAME = 'publish';

    public function get_name()
    {
        return static::COMMAND_NAME;
    }

    public function print_help()
    {
    }
}
