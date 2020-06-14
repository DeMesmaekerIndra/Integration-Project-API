<?php

declare (strict_types = 1);

use App\Factory\ResponseFactory;
use Pimple\Psr11\Container as Psr11Container;

$container['ResponseFactory'] = static function (): ResponseFactory {
    return new ResponseFactory();
};
