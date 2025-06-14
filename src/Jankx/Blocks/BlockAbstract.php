<?php

namespace Jankx\Blocks;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Jankx\Blocks\BlockInterface as BlocksBlockInterface;

abstract class BlockAbstract implements BlocksBlockInterface
{
    protected $type;
    protected $baseDirectory;

    protected $isServerSideRender = true;

    public function __construct()
    {
        do_action_ref_array('jankx/gutenberg/block/' . $this->getType(), [
            &$this
        ]);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setBlockBaseDirectory($directory)
    {
        $this->baseDirectory = $directory;
    }

    public function getBlockBaseDirectory()
    {
        return $this->baseDirectory;
    }

    public function getJsonFile()
    {
        $baseName = basename($this->getType());
        return implode(
            DIRECTORY_SEPARATOR,
            [$this->getBlockBaseDirectory(), $baseName, 'block.json']
        );
    }

    public function getBlockJson(): array
    {
        $jsonFile = $this->getJsonFile();
        if (!file_exists($jsonFile)) {
            return [];
        }

        $attributes = json_decode(file_get_contents($jsonFile), true);
        if ($this->isServerSideRender()) {
            $attributes['render_callback'] = [$this, 'render'];
        }
        return $attributes;
    }

    public function register()
    {
        register_block_type($this->getType(), $this->getBlockJson());
    }

    public function isServerSideRender(): bool
    {
        return $this->isServerSideRender;
    }

    public function get_responsive_setting($key, $defaultValue)
    {
        return $defaultValue;
    }

    public function isEnabled(): bool
    {
        return true;
    }
}
