<?php

declare (strict_types = 1);

use App\Repository\OlaRepository as OlaRepository;
use App\Repository\OpoRepository as OpoRepository;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container['OpoRepository'] = static function (Container $c): OpoRepository {
    return new OpoRepository(new Psr11Container($c));
};

$container['OlaRepository'] = static function (Container $c): OlaRepository {
    return new OlaRepository(new Psr11Container($c));
};
