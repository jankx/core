<?php

namespace Illuminate\Contracts\Container;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Exception;
use Psr\Container\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
