<?php

namespace Jankx\Command\Abstracts;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Command\Interfaces\CommandInterface;
use Jankx\Command\Interfaces\Subcommand;
use WP_CLI;

abstract class Command implements CommandInterface
{
    protected $subCommands = [];

    public function __construct()
    {
        add_action('jankx/command/init', [$this, 'initSubCommands']);
        do_action('jankx/command/before_execute', [$this, 'registerSubCommands']);
    }

    /**
     * @param \Jankx\Command\Interfaces\Subcommand $command
     */
    public function addSubCommand(Subcommand $command)
    {
        array_push($this->subCommands, $command);
    }

    public function initSubCommands($thisCommand)
    {
    }

    public function handle($args, $assoc_args)
    {
    }

    /**
     * @return array
     */
    public function parameters()
    {
        return [];
    }

    public function registerSubCommands()
    {
        if (count($this->subCommands) > 0) {
            foreach ($this->subCommands as $subCommandClass) {
                if (!class_exists($subCommandClass) || !is_a($subCommandClass, Subcommand::class, true)) {
                    continue;
                }
                /**
                 * @var \Jankx\Command\Interfaces\Subcommand
                 */
                $subcommand = new $subCommandClass();
                WP_CLI::add_command(sprintf('jankx %s %s', $this->get_name(), $subcommand->get_name()), [$subcommand, 'handle']);
            }
        }
    }
}
