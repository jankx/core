<?php

namespace Jankx\Command;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Command\Interfaces\CommandInterface;
use WP_CLI;
use Jankx\Command\Commands\OptionCommand;
use Jankx\Command\Commands\CacheCommand;
use Jankx\Command\Commands\PublishCommand;
use Jankx\Command\Commands\SecureCommand;

class CommandManager
{
    const COMMAND_NAMESPACE = 'jankx';

    protected static $instance;

    protected $command_providers = array();

    /**
     * @var \Jankx\Command\Abstracts\Command[]
     */
    protected $commands = array();

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct()
    {
        $this->bootstrap();
        $this->init_commands();
    }


    protected function bootstrap()
    {
        $default_jankx_commands = array(
            OptionCommand::class,
            CacheCommand::class,
            PublishCommand::class,
            SecureCommand::class
        );

        $this->command_providers = apply_filters(
            'jankx_command_providers',
            $default_jankx_commands
        );
    }

    protected function init_commands()
    {
        foreach ($this->command_providers as $cls_command) {
            $command = new $cls_command();
            if (!is_a($command, CommandInterface::class)) {
                continue;
            }
            $this->commands[$command->get_name()] = $command;

            do_action("jankx/command/{$command->get_name()}/init", $command);
        }
        add_action('cli_init', array($this, 'register_commands'));
    }

    public function print_help()
    {
    }

    public function register_commands()
    {
        foreach ($this->commands as $command) {
            WP_CLI::add_command('jankx ' . $command->get_name(), [$command, 'handle'], $command->parameters());
            do_action('jankx/command/before_execute', $command);
        }
    }
}
