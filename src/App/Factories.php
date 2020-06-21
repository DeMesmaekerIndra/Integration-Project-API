<?php

declare(strict_types=1);

use App\Factory\ResponseFactory;

$container['ResponseFactory'] = static function (): ResponseFactory {
    return new ResponseFactory();
};
