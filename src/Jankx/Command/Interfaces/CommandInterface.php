<?php

namespace Jankx\Command\Interfaces;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

interface CommandInterface extends BaseCommand
{
    public function addSubCommand(Subcommand $command);

    public function initSubCommands($thisCommand);

    public function registerSubCommands();
}
