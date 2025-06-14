<?php

namespace Illuminate\Container;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class EntryNotFoundException extends Exception implements NotFoundExceptionInterface
{
    //
}
