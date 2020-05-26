<?php

declare (strict_types = 1);

use App\Repository\OpoRepository as OpoRepository;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;

$container['OpoRepository'] = static function (Container $c): OpoRepository {
    return new OpoRepository(new Psr11Container($c));
};
