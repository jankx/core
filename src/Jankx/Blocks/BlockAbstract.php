<?php

namespace Jankx\Blocks;

use Jankx\Interfaces\BlockInterface;

abstract class BlockAbstract implements BlockInterface
{
    protected $type;
    protected $baseDirectory;

    protected $isServerSideRender = true;

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
}
