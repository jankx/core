<?php

namespace Jankx\Kernel\Interfaces;

/**
 * Service Registry Interface
 *
 * Defines the contract for service registry classes in the Jankx framework.
 *
 * @package Jankx\Kernel\Interfaces
 */
interface ServiceRegistryInterface
{
    /**
     * Register services
     */
    public function registerServices(): void;
}