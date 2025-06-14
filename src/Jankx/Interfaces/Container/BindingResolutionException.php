<?php

namespace Illuminate\Contracts\Container;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Exception;
use Psr\Container\ContainerExceptionInterface;

class BindingResolutionException extends Exception implements ContainerExceptionInterface
{
    //
}
