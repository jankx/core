<?php

namespace Illuminate\Container;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class EntryNotFoundException extends Exception implements NotFoundExceptionInterface
{
    //
}
